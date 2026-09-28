<?php

namespace App\Http\Controllers;

use App\Exports\WeeklyCallReportExport;
use App\Models\GoogleSheetData;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class WeeklyCallReportExportController extends Controller
{
    public function __invoke(Request $request, string $userId, WeeklyCallReportExport $export)
    {
        $validated = $request->validate([
            'selected_week' => ['required', 'string', 'regex:/^\d{4}-W(?:0[1-9]|[1-4]\d|5[0-3])$/D'],
        ]);

        [$year, $week] = explode('-W', $validated['selected_week']);
        $weekStart = CarbonImmutable::now()->setISODate((int) $year, (int) $week, 1)->startOfDay();
        abort_unless($weekStart->format('o-\WW') === $validated['selected_week'], 422, 'Invalid ISO week.');
        $weekEnd = $weekStart->addDays(6)->endOfDay();

        $juniors = User::whereIn('id', explode(',', $userId))
            ->where('role', 'junior')
            ->where('is_deleted', 0)
            ->orderBy('id')
            ->get(['id', 'name', 'phone']);
        abort_if($juniors->isEmpty(), 404);

        $members = $juniors->mapWithKeys(function ($junior) use ($weekStart, $weekEnd) {
            // Match allreportWeekly: follow-up dates, including hierarchical ownership keys.
            $count = GoogleSheetData::where('created_by', 'like', "{$junior->id}|junior%")
                ->where('Exe_Remarks', 'Called & Mailed')
                ->whereDate('followup', '>=', $weekStart->toDateString())
                ->whereDate('followup', '<=', $weekEnd->toDateString())
                ->count();

            return [$junior->id => [
                'id' => $junior->id,
                'name' => $junior->name,
                'phone' => $junior->phone,
                'count' => $count,
            ]];
        });

        $seniors = User::where('role', 'senior')->where('is_deleted', 0)
            ->orderBy('id')->get(['id', 'name', 'phone', 'mail']);
        $groups = [];
        $assigned = [];

        foreach ($seniors as $senior) {
            // Match seniorgroupmailchart: use mail assignments, not the separate group field.
            $juniorIds = is_array($senior->mail) ? $senior->mail : [];
            $groupMembers = $members->whereIn('id', $juniorIds);
            if ($groupMembers->isEmpty()) {
                continue;
            }
            $groups[] = [
                'leader' => ['name' => $senior->name, 'phone' => $senior->phone],
                'members' => $groupMembers->values()->all(),
            ];
            $assigned = array_merge($assigned, $groupMembers->keys()->all());
        }

        $unassigned = $members->except($assigned);
        if ($unassigned->isNotEmpty()) {
            $groups[] = ['leader' => null, 'members' => $unassigned->values()->all()];
        }

        $file = $export->generate($groups, $weekStart, $weekEnd);
        $fileName = 'Call_Report_' . $weekStart->format('j-n-y') . '_to_' . $weekEnd->format('j-n-y') . '.xlsx';

        return response()->download($file, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'private, no-store',
        ])->deleteFileAfterSend(true);
    }
}
