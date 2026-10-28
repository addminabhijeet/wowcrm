<?php

namespace Tests\Unit;

use App\Services\DailyGroupCallReport;
use PHPUnit\Framework\TestCase;

class DailyGroupCallReportTest extends TestCase
{
    public function test_multiple_extensions_count_each_direction_and_duration_once(): void
    {
        $teams = [[
            'name' => 'Lead', 'extensions' => ['183', '193'],
            'members' => [['name' => 'Recruiter', 'extensions' => ['170'], 'cm_total' => 6, 'absent' => true]],
        ]];
        $records = [
            ['source' => '193', 'destination' => '9729270064', 'call_duration' => 26],
            ['source' => '183', 'destination' => '193', 'call_duration' => 4],
            ['source' => '13146141600', 'destination' => '183', 'call_duration' => 10],
            ['source' => '170', 'destination' => '*97', 'call_duration' => 90],
            ['source' => '9729270064', 'destination' => '170', 'call_duration' => 60],
            ['source' => '999', 'destination' => '9729270064', 'call_duration' => 500],
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
                ['source' => '193', 'destination' => '193', 'call_duration' => 30],
                ['source' => '183', 'destination' => '193', 'call_duration' => 20],
                ['source' => '0193', 'destination' => '9729270064', 'call_duration' => -5],
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
