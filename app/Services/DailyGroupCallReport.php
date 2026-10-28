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
        $result = [];
        $owners = [];
        foreach ($teams as $team) {
            if (!$team['members']) {
                continue;
            }
            $this->addRow($result, $owners, $team['name'] . ' (Team Lead)', $team['extensions'], null, false, true);
            foreach ($team['members'] as $member) {
                $this->addRow($result, $owners,
                    $member['name'], $member['extensions'], (int) $member['cm_total'],
                    (bool) $member['absent'], false
                );
            }
        }

        if (!$result) {
            return [];
        }

        // One pass regardless of roster size. A shared extension still counts for each owner.
        foreach ($records as $record) {
            $record = (array) $record;
            $incoming = $owners[(string) $record['destination']] ?? [];
            $outgoing = $owners[(string) $record['source']] ?? [];
            foreach ($incoming as $index => $_) {
                $result[$index]['incoming']++;
            }
            foreach ($outgoing as $index => $_) {
                $result[$index]['outgoing']++;
            }
            $seconds = max(0, (int) $record['call_duration']);
            // Array union counts duration once when both endpoints belong to the same person.
            foreach ($incoming + $outgoing as $index => $_) {
                $result[$index]['duration'] += $seconds;
            }
        }

        return $result;
    }

    private function addRow(array &$result, array &$owners, string $name, array $extensions, ?int $cm, bool $absent, bool $leader): void
    {
        $index = count($result);
        foreach ($extensions as $extension) {
            $owners[$extension][$index] = true;
        }
        $result[] = [
            'name' => $name . ($absent ? ' (Absent)' : ''), 'leader' => $leader,
            'called_mailed' => $cm,
            'incoming' => $extensions ? 0 : null,
            'outgoing' => $extensions ? 0 : null,
            'duration' => $extensions ? 0 : null,
        ];
    }
}
