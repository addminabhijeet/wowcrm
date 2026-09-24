<?php

namespace App\Services;

use App\Models\GoogleSheetData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CandidateListRestrictions
{
    public function apply(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            return;
        }

        $emails = $rows->pluck('Email_Address')->filter()->unique()->values();
        $phones = $rows->pluck('Phone_Number')->map(fn ($value) => preg_replace('/\\D/', '', (string) $value))
            ->filter(fn ($value) => strlen($value) === 10)->unique()->values();
        // Match the existing checkEmail phone normalization exactly.
        $phoneSql = "REPLACE(REPLACE(REPLACE(Phone_Number, '-', ''), ' ', ''), '(', '')";
        $records = collect();
        if ($emails->isNotEmpty() || $phones->isNotEmpty()) {
            $records = GoogleSheetData::query()->where(function ($query) use ($emails, $phones, $phoneSql) {
                $query->whereIn('Email_Address', $emails)
                    ->orWhereIn(\Illuminate\Support\Facades\DB::raw($phoneSql), $phones);
            })->orderBy('id')->get(['id', 'sheet_row_number', 'Email_Address', 'Phone_Number', 'created_by', 'Remark']);
        }

        $byEmail = $records->groupBy(fn ($record) => mb_strtolower((string) $record->Email_Address));
        $byPhone = $records->groupBy(fn ($record) => str_replace(['-', ' ', '('], '', (string) $record->Phone_Number));
        $histories = [];
        $names = [];
        foreach ($rows as $row) {
            $email = mb_strtolower((string) $row->Email_Address);
            $phone = preg_replace('/\\D/', '', (string) $row->Phone_Number);
            foreach (['email' => $email, 'phone' => $phone] as $type => $value) {
                if ($value === '' || ($type === 'phone' && strlen($value) !== 10)) {
                    continue;
                }
                $key = $type . ':' . $value;
                if (!isset($histories[$key])) {
                    $matches = ($type === 'email' ? $byEmail : $byPhone)->get($value, collect());
                    $histories[$key] = $this->history($matches);
                    if ($histories[$key]['name'] !== null) {
                        $names[] = $histories[$key]['name'];
                    }
                }
            }
        }

        // Include deleted users: is_deleted, not status, controls the exception.
        $users = empty($names) ? collect() : User::whereIn('name', array_unique($names))
            ->orderBy('id')->get(['id', 'name', 'is_deleted'])->groupBy(fn ($user) => mb_strtolower($user->name));

        foreach ($rows as $row) {
            $states = [];
            foreach (['email:' . mb_strtolower((string) $row->Email_Address),
                'phone:' . preg_replace('/\\D/', '', (string) $row->Phone_Number)] as $key) {
                if (isset($histories[$key])) {
                    $history = $histories[$key];
                    $user = $users->get(mb_strtolower((string) $history['name']), collect())->first();
                    $states[] = $this->decision($history, $user && (int) $user->is_deleted === 1);
                }
            }
            $blocked = collect($states)->sortByDesc(fn ($state) => $state['restricted'] ? PHP_INT_MAX : $state['days'])->first();
            $row->contact_restriction_message = $blocked['message'] ?? '';
        }
    }

    public function history(Collection $records): array
    {
        $latest = $records->sortByDesc('sheet_row_number')->first();
        $result = ['restricted' => $latest && strpos((string) $latest->created_by, 'accountant') !== false,
            'name' => null, 'date' => null];
        if ($result['restricted']) {
            return $result;
        }
        $remarks = $records->pluck('Remark')->implode(' || ');
        if (preg_match_all('/Called\\s*&\\s*Mailed\\s*\\|\\s*(?:Added|Updated)\\s+by\\s+(.+?)\\s+on\\s+(\\d{2})-(\\d{2})-(\\d{4})\\s+\\d{2}:\\d{2}/', $remarks, $matches, PREG_PATTERN_ORDER)) {
            foreach ($matches[0] as $index => $match) {
                $date = Carbon::createFromDate((int) $matches[4][$index], (int) $matches[3][$index], (int) $matches[2][$index]);
                if ($result['date'] === null || $date->isAfter($result['date'])) {
                    $result['date'] = $date;
                    $result['name'] = trim($matches[1][$index]);
                }
            }
        }
        return $result;
    }

    public function decision(array $history, bool $deleted): array
    {
        if ($history['restricted']) {
            return ['restricted' => true, 'days' => 0, 'message' => 'Candidate already enrolled.'];
        }
        $days = 0;
        if ($history['date'] !== null && !$deleted) {
            $difference = (int) abs(Carbon::now('Asia/Kolkata')->startOfDay()->diffInDays($history['date']));
            if ($difference < 30) {
                $days = 30 - $difference;
            }
        }
        return ['restricted' => false, 'days' => $days,
            'message' => $days ? "Please contact after {$days} days" : ''];
    }
}
