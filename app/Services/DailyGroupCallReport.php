<?php

namespace App\Services;

/** Builds the shared seven-column daily report from the Group Report's total card. */
class DailyGroupCallReport
{
    public static function extensions(?string $value): array
    {
        // Accept users.phone values such as "183 and 193", "183, 193", or "183/193".
        $parts = preg_split('/\D+/', trim($value ?? ''), -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_unique(array_filter($parts, fn ($v) => strlen($v) < 7)));
    }

    public function rows(array $teams, iterable $records): array
    {
        $records = is_array($records) ? $records : iterator_to_array($records);
        $result = [];
        foreach ($teams as $team) {
            if (!$team['members']) {
                continue;
            }
            $result[] = $this->row($team['name'] . ' (Team Lead)', $team['extensions'], null, false, true, $records);
            foreach ($team['members'] as $member) {
                $result[] = $this->row(
                    $member['name'], $member['extensions'], (int) $member['cm_total'],
                    (bool) $member['absent'], false, $records
                );
            }
        }

        return $result;
    }

    private function row(string $name, array $extensions, ?int $cm, bool $absent, bool $leader, array $records): array
    {
        $incoming = $outgoing = $seconds = 0;
        foreach ($records as $record) {
            $record = (array) $record;
            $in = in_array((string) $record['destination'], $extensions, true);
            $out = in_array((string) $record['source'], $extensions, true);
            $incoming += (int) $in;
            $outgoing += (int) $out;
            if ($in || $out) {
                // An internal call between two of this user's extensions contributes duration once.
                $seconds += max(0, (int) $record['call_duration']);
            }
        }

        return [
            'name' => $name . ($absent ? ' (Absent)' : ''), 'leader' => $leader,
            'called_mailed' => $cm,
            'incoming' => $extensions ? $incoming : null,
            'outgoing' => $extensions ? $outgoing : null,
            'duration' => $extensions ? $seconds : null,
        ];
    }
}
