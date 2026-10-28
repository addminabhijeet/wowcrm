<?php

namespace Tests\Unit;

use App\Services\DailyGroupCallReport;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class DailyGroupCallReportTest extends TestCase
{
    public function test_confirmed_single_extension_exports_use_only_answered_seconds(): void
    {
        // Answered seconds from the user-verified exports (54) and (55).
        // Synthetic external endpoints keep personal phone numbers out of fixtures.
        $cases = [
            ['180', [60], [6, 65, 1362, 120, 97, 99, 1146, 262, 722, 27, 101, 56, 61, 13], 60, 4137],
            ['178', [905, 674], [826, 94, 296, 876, 107, 7, 74, 289, 9, 9, 28, 57, 6, 20, 58, 33, 68, 65, 18, 60, 67, 55, 460, 55, 21, 22, 10, 66, 22, 59, 27, 70], 1579, 3934],
        ];
        foreach ($cases as [$extension, $incoming, $outgoing, $expectedIn, $expectedOut]) {
            $records = [];
            foreach ($incoming as $seconds) {
                $records[] = ['source' => '15555550100', 'destination' => $extension,
                    'answered_duration' => $seconds, 'call_duration' => $seconds + 20];
            }
            foreach ($outgoing as $seconds) {
                $records[] = ['source' => $extension, 'destination' => '15555550100',
                    'answered_duration' => $seconds, 'call_duration' => $seconds + 20];
            }
            $rows = (new DailyGroupCallReport())->rows([
                ['name' => 'Lead', 'extensions' => [], 'members' => [
                    ['name' => 'Recruiter', 'extensions' => [$extension], 'cm_total' => 0, 'absent' => false],
                ]],
            ], $records);
            $this->assertSame($expectedIn, $rows[1]['inbound_duration']);
            $this->assertSame($expectedOut, $rows[1]['outbound_duration']);
            $this->assertSame($expectedIn + $expectedOut, $rows[1]['duration']);
            $this->assertSame(count($incoming), $rows[1]['incoming']);
            $this->assertSame(count($outgoing), $rows[1]['outgoing']);
        }
    }

    public function test_duration_sums_answered_inbound_and_outbound_without_ringing_time(): void
    {
        $teams = [['name' => 'Lead', 'extensions' => [], 'members' => [
            ['name' => 'Recruiter', 'extensions' => ['170'], 'cm_total' => 0, 'absent' => false],
        ]]];
        $rows = (new DailyGroupCallReport())->rows($teams, [
            ['source' => '9729270064', 'destination' => '170', 'call_duration' => 90, 'answered_duration' => 60],
            ['source' => '170', 'destination' => '9729270064', 'call_duration' => 80, 'answered_duration' => 40],
            ['source' => '170', 'destination' => '9729270064', 'call_duration' => 30, 'answered_duration' => 0],
        ]);
        $this->assertSame(100, $rows[1]['duration']);
        $this->assertSame(1, $rows[1]['incoming']);
        $this->assertSame(2, $rows[1]['outgoing']);
    }

    public function test_fast_slot_lookup_matches_timezone_conversion_at_every_hour_boundary(): void
    {
        foreach (['2026-10-27', '2026-11-08'] as $date) {
            $from = Carbon::parse($date . ' 18:00:00', 'Asia/Kolkata');
            $ends = [];
            for ($hour = 1; $hour <= 12; $hour++) {
                $ends[] = $from->copy()->addHours($hour)->setTimezone('America/New_York')->format('Y-m-d H:i:s');
            }
            for ($slot = 0; $slot < 12; $slot++) {
                foreach ([0, 1, 1799, 1800, 3599] as $second) {
                    $timestamp = $from->copy()->addHours($slot)->addSeconds($second)->setTimezone('America/New_York')->format('Y-m-d H:i:s');
                    $reference = (Carbon::parse($timestamp, 'America/New_York')->setTimezone('Asia/Kolkata')->hour - 18 + 24) % 24;
                    $this->assertSame($reference, DailyGroupCallReport::slotIndex($timestamp, $ends));
                }
            }
            $this->assertSame(12, DailyGroupCallReport::slotIndex($ends[11], $ends));
        }
    }

    public function test_multiple_extensions_count_each_direction_and_duration_once(): void
    {
        $teams = [[
            'name' => 'Lead', 'extensions' => ['183', '193'],
            'members' => [['name' => 'Recruiter', 'extensions' => ['170'], 'cm_total' => 6, 'absent' => true]],
        ]];
        $records = [
            ['source' => '193', 'destination' => '9729270064', 'answered_duration' => 26],
            ['source' => '183', 'destination' => '193', 'answered_duration' => 4],
            ['source' => '13146141600', 'destination' => '183', 'answered_duration' => 10],
            ['source' => '170', 'destination' => '*97', 'answered_duration' => 90, 'call_duration' => 120],
            ['source' => '9729270064', 'destination' => '170', 'answered_duration' => 60],
            ['source' => '999', 'destination' => '9729270064', 'answered_duration' => 500],
        ];
        $rows = (new DailyGroupCallReport())->rows($teams, $records);
        $this->assertCount(2, $rows);
        $this->assertSame(2, $rows[0]['incoming']);
        $this->assertSame(2, $rows[0]['outgoing']);
        $this->assertSame(40, $rows[0]['duration']);
        $this->assertNull($rows[0]['called_mailed']);
        $this->assertSame('Recruiter (Absent)', $rows[1]['name']);
        $this->assertSame(6, $rows[1]['called_mailed']);
        $this->assertSame(150, $rows[1]['duration']);
    }

    public function test_extension_mapping_and_missing_mapping_are_distinct_from_no_calls(): void
    {
        $this->assertSame(['183', '193'], DailyGroupCallReport::extensions('183 and 193, 183'));
        $this->assertSame([], DailyGroupCallReport::extensions('-'));
        $this->assertSame([], DailyGroupCallReport::extensions('16896896850'));
        $rows = (new DailyGroupCallReport())->rows([
            ['name' => 'Empty', 'extensions' => ['183'], 'members' => []],
            ['name' => 'Team', 'extensions' => [], 'members' => [
                ['name' => 'Zero', 'extensions' => ['170'], 'cm_total' => 0, 'absent' => false],
            ]],
        ], []);
        $this->assertCount(2, $rows);
        $this->assertNull($rows[0]['duration']);
        $this->assertSame(0, $rows[1]['duration']);
        $this->assertSame(0, $rows[1]['incoming']);
    }

    public function test_streamed_calls_keep_shared_extensions_and_same_endpoint_durations_correct(): void
    {
        $teams = [[
            'name' => 'Lead', 'extensions' => ['183', '193'],
            'members' => [
                ['name' => 'Shared', 'extensions' => ['193'], 'cm_total' => 2, 'absent' => false],
                ['name' => 'Leading zero', 'extensions' => ['0193'], 'cm_total' => 0, 'absent' => false],
            ],
        ]];
        $read = 0;
        $records = (function () use (&$read) {
            foreach ([
                ['source' => '193', 'destination' => '193', 'answered_duration' => 30],
                ['source' => '183', 'destination' => '193', 'answered_duration' => 20],
                ['source' => '0193', 'destination' => '9729270064', 'answered_duration' => -5],
            ] as $record) {
                $read++;
                yield (object) $record;
            }
        })();
        $rows = (new DailyGroupCallReport())->rows($teams, $records);
        $this->assertSame(3, $read);
        $this->assertSame([2, 2, 50], [$rows[0]['incoming'], $rows[0]['outgoing'], $rows[0]['duration']]);
        $this->assertSame([2, 1, 50], [$rows[1]['incoming'], $rows[1]['outgoing'], $rows[1]['duration']]);
        $this->assertSame([0, 1, 0], [$rows[2]['incoming'], $rows[2]['outgoing'], $rows[2]['duration']]);
    }
}
