<?php

namespace App\Services;

/** Builds the shared nine-column daily report from the Group Report's total card. */
class DailyGroupCallReport
{
    /** First hourly upper bound after a stored PBX timestamp (the query already applies the start bound). */
    public static function slotIndex(string $timestamp, array $slotEnds): int
    {
        $low = 0;
        $high = count($slotEnds);
        while ($low < $high) {
            $middle = intdiv($low + $high, 2);
            if ($timestamp < $slotEnds[$middle]) {
                $high = $middle;
            } else {
                $low = $middle + 1;
            }
        }

        return $low;
    }

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
            if (!$incoming && !$outgoing) {
                continue;
            }
            $seconds = max(0, (int) $record['answered_duration']);
            foreach ($incoming as $index => $_) {
                $result[$index]['incoming']++;
                $result[$index]['inbound_duration'] += $seconds;
                $result[$index]['duration'] += $seconds;
            }
            foreach ($outgoing as $index => $_) {
                $result[$index]['outgoing']++;
                $result[$index]['outbound_duration'] += $seconds;
                // Preserve counting once when both endpoints belong to the same person.
                if (!isset($incoming[$index])) {
                    $result[$index]['duration'] += $seconds;
                }
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
            'inbound_duration' => $extensions ? 0 : null,
            'outbound_duration' => $extensions ? 0 : null,
            'extension' => implode(', ', $extensions),
        ];
    }
}
