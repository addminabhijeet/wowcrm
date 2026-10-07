<?php

namespace App\Http\Controllers;

use App\Exports\SimpleXlsx;
use App\Services\GroupCallReportMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin pages for the scheduled C&M group report mail:
 * setup (recipients + Kolkata hours), send history list with filters, Excel download.
 */
class CallReportMailController extends Controller
{
    private const TZ = 'Asia/Kolkata';

    private function authorizeAdmin(): void
    {
        abort_unless(in_array(auth()->user()->role ?? '', ['admin', 'operation'], true), 403);
    }

    public function index()
    {
        $this->authorizeAdmin();

        return view('smtp.reportmail', [
            'recipients' => GroupCallReportMail::recipients(),
            'hours'      => GroupCallReportMail::hours(),
            'nowIst'     => now(self::TZ)->format('h:i A'),
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'emails'  => ['nullable', 'string', 'max:1000'],
            'cc'      => ['nullable', 'string', 'max:1000'],
            'hours'   => ['nullable', 'array'],
            'hours.*' => ['integer', 'between:0,23'],
        ]);

        $errors = [];
        $parsed = [];
        foreach (['emails' => 'To', 'cc' => 'CC'] as $field => $label) {
            $list = collect(preg_split('/[\s,;]+/', (string) $request->input($field), -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn ($e) => strtolower(trim($e)));
            $invalid = $list->reject(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL));
            if ($invalid->isNotEmpty()) {
                $errors[$field] = "Invalid email in {$label}: " . $invalid->implode(', ');
            }
            $parsed[$field] = $list->all();
        }
        if ($errors) {
            return back()->withInput()->withErrors($errors);
        }

        GroupCallReportMail::saveRecipients($parsed['emails'], $parsed['cc']);
        GroupCallReportMail::saveHours($request->input('hours', []));

        return redirect()->route('smtp.editallcallreport')->with('success', 'Call report mail settings saved.');
    }

    public function sendNow()
    {
        $this->authorizeAdmin();
        @set_time_limit(180);

        $now = now(self::TZ);
        $result = GroupCallReportMail::send('manual', $now->toDateString(), (int) $now->format('G'));

        return redirect()->route('smtp.reportmail.list')
            ->with($result['status'] === 'sent' ? 'success' : 'error', 'Manual send: ' . $result['status'] . ' - ' . $result['message']);
    }

    /** Added: manual send of one slot's report for today (the last slot also sends the full report, like the schedule). */
    public function sendSlotNow(Request $request)
    {
        $this->authorizeAdmin();
        @set_time_limit(180);

        $request->validate(['slot' => ['required', 'integer', 'between:0,8']]);
        $slot = (int) $request->input('slot');
        $hour = array_search($slot, GroupCallReportMail::HOUR_SLOTS, true);
        $date = now(self::TZ)->toDateString();

        $result = GroupCallReportMail::sendSlot($date, (int) $hour, 'manual');
        $message = 'Manual slot send (' . GroupCallReportMail::SLOTS[$slot]['title'] . '): ' . $result['status'] . ' - ' . $result['message'];
        $ok = $result['status'] === 'sent';

        if ($slot === count(GroupCallReportMail::SLOTS) - 2) {
            $full = GroupCallReportMail::send('manual', $date, (int) $hour);
            $message .= ' | Full report: ' . $full['status'] . ' - ' . $full['message'];
            $ok = $ok && $full['status'] === 'sent';
        }

        return redirect()->route('smtp.reportmail.list')->with($ok ? 'success' : 'error', $message);
    }

    // ---------------------------------------------------------------- history list

    /** Filters (Kolkata dates) applied to the send history. */
    private function filtered(Request $request)
    {
        $today = now(self::TZ)->toDateString();
        $from = $request->input('from', $today);
        $to   = $request->input('to', $today);

        // Kolkata day bounds -> the app timezone the rows were stored in
        $start = Carbon::parse($from, self::TZ)->startOfDay()->setTimezone(config('app.timezone'));
        $end   = Carbon::parse($to, self::TZ)->endOfDay()->setTimezone(config('app.timezone'));

        $q = DB::table('call_report_mail_logs')->whereBetween('sent_at', [$start, $end]);

        if (in_array($request->input('status'), ['sent', 'failed', 'skipped'], true)) {
            $q->where('status', $request->input('status'));
        }
        if (in_array($request->input('trigger'), ['schedule', 'manual'], true)) {
            $q->where('trigger', $request->input('trigger'));
        }
        if ($request->filled('hour') && is_numeric($request->input('hour'))) {
            $q->where('report_hour', (int) $request->input('hour'));
        }

        return [$q->orderByDesc('sent_at')->orderByDesc('id'), $from, $to];
    }

    private static function ist($datetime): Carbon
    {
        return Carbon::parse($datetime, config('app.timezone'))->setTimezone(self::TZ);
    }

    public function list(Request $request)
    {
        $this->authorizeAdmin();

        [$query, $from, $to] = $this->filtered($request);
        $counts = (clone $query)->reorder()->selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
        $logs = $query->paginate(25)->withQueryString();

        return view('smtp.reportmail-list', [
            'logs'   => $logs,
            'from'   => $from,
            'to'     => $to,
            'status' => $request->input('status'),
            'trigger' => $request->input('trigger'),
            'hour'   => $request->input('hour'),
            'counts' => $counts,
        ]);
    }

    public function excel(Request $request)
    {
        $this->authorizeAdmin();

        [$query, $from, $to] = $this->filtered($request);

        $rows = $query->get()->values()->map(fn ($l, $i) => [
            $i + 1,
            Carbon::parse($l->report_date)->format('d-m-Y'),
            Carbon::createFromTime($l->report_hour)->format('h:00 A'),
            self::ist($l->sent_at)->format('d-m-Y h:i:s A'),
            ucfirst($l->trigger),
            ucfirst($l->status),
            (string) $l->recipients,
            (string) $l->message,
        ]);

        return SimpleXlsx::download(
            "call-report-mail-list-{$from}-to-{$to}.xlsx",
            "C&M Report Mail - Send List ({$from} to {$to}, IST)",
            ['#', 'Report Date', 'Slot Hour (IST)', 'Sent At (IST)', 'Trigger', 'Status', 'Recipients', 'Message'],
            $rows,
            [6, 14, 16, 22, 12, 12, 40, 60]
        );
    }
}
