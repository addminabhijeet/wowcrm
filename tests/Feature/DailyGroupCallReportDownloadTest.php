<?php

namespace Tests\Feature;

use App\Http\Controllers\CallDurationController;
use App\Http\Controllers\CallReportController;
use App\Models\User;
use App\Services\NavbarCallDuration;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use PharData;
use Tests\TestCase;

class DailyGroupCallReportDownloadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        config(['database.default' => 'sqlite', 'database.connections.sqlite' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ]]);
        DB::purge('sqlite');
        DB::connection()->getPdo()->sqliteCreateFunction('HOUR', fn ($value) => (int) substr($value, 11, 2));
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('role'); $t->integer('is_deleted')->default(0);
            $t->text('mail')->nullable(); $t->string('phone')->nullable();
        });
        Schema::create('logins', function (Blueprint $t) { $t->id(); $t->integer('user_id'); $t->dateTime('logged_in_at'); });
        Schema::create('google_sheet_data', function (Blueprint $t) {
            $t->id(); $t->string('created_by'); $t->date('followup'); $t->dateTime('updated_at'); $t->string('Exe_Remarks');
        });
        Schema::create('call_duration_records', function (Blueprint $t) {
            $t->id(); $t->dateTime('call_date'); $t->string('source'); $t->string('destination');
            $t->integer('call_duration')->default(999999);
            $t->integer('answered_duration');
        });
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Leader', 'role' => 'senior', 'mail' => '[2]', 'phone' => '183 and 193'],
            ['id' => 2, 'name' => '=Recruiter & Name', 'role' => 'junior', 'mail' => null, 'phone' => '170'],
            ['id' => 3, 'name' => 'Unassigned', 'role' => 'junior', 'mail' => null, 'phone' => '171'],
        ]);
        // Group page: 18:00 IST / 08:30 Eastern through 06:00 IST / 20:30 Eastern, exclusive.
        DB::table('call_duration_records')->insert([
            ['call_date' => '2026-10-27 08:29:59', 'source' => '170', 'destination' => '9729270064', 'answered_duration' => 999],
            ['call_date' => '2026-10-27 10:30:00', 'source' => '170', 'destination' => '9729270064', 'answered_duration' => 90000],
            ['call_date' => '2026-10-27 19:29:59', 'source' => '9729270064', 'destination' => '170', 'answered_duration' => 10],
            ['call_date' => '2026-10-27 20:30:00', 'source' => '170', 'destination' => '9729270064', 'answered_duration' => 999],
            ['call_date' => '2026-10-27 12:00:00', 'source' => '183', 'destination' => '193', 'answered_duration' => 30],
        ]);
        DB::table('google_sheet_data')->insert([
            ['created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 11:00:00', 'Exe_Remarks' => 'Called & Mailed'],
            ['created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 20:30:00', 'Exe_Remarks' => 'Called & Mailed'],
            ['created_by' => '2|junior', 'followup' => '2026-10-26', 'updated_at' => '2026-10-27 11:00:00', 'Exe_Remarks' => 'Called & Mailed'],
        ]);
        $this->actingAs((new User())->forceFill(['id' => 99, 'name' => 'Admin', 'role' => 'admin']));
    }

    protected function tearDown(): void
    {
        DB::disconnect('sqlite');
        parent::tearDown();
    }

    public function test_upload_rejects_missing_answered_duration_before_storing_calls(): void
    {
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('Export_Callrecords.csv',
            "Sl.No,Call Date,Source,Destination,Call Duration,CallerID,DID,Disposition,TimeZone\n"
            . "1,2026-10-28 13:36:52,195,5859574994,36,16896895472,,ANSWERED,2026-10-28 13:36:52\n"
        );
        $before = DB::table('call_duration_records')->count();
        $this->from(route('senior.excelupload'))
            ->post(route('senior.excelupload.store'), ['file' => $file])
            ->assertRedirect(route('senior.excelupload'))
            ->assertSessionHasErrors(['file' => 'Column "Answered Duration (or Amswered Duration)" not found. Upload the Export_Callrecords sheet as .csv.']);
        $this->assertSame($before, DB::table('call_duration_records')->count());
    }

    public function test_pbx_answered_duration_header_spellings_are_supported(): void
    {
        $method = new \ReflectionMethod(CallDurationController::class, 'headerKey');
        foreach (['Amswered Duration', 'Answered Duration', ' answered duration '] as $header) {
            $this->assertSame('answered_duration', $method->invoke(null, $header));
        }
        // The exact header order used by the recurring PBX export must remain supported.
        $headers = str_getcsv('Sl.No,"Call Date",Source,Destination,"Call Duration","Amswered Duration",CallerID,DID,Disposition,TimeZone');
        $this->assertSame(['sl_no', 'call_date', 'source', 'destination', 'call_duration',
            'answered_duration', 'caller_id', 'did', 'disposition', 'time_zone'],
            array_map(fn ($header) => $method->invoke(null, $header), $headers));
    }

    public function test_report_queries_deduplicate_presence_and_skip_discarded_legacy_counts(): void
    {
        DB::table('logins')->insert([
            ['user_id' => 2, 'logged_in_at' => '2026-10-27 00:00:00'],
            ['user_id' => 2, 'logged_in_at' => '2026-10-27 23:59:59'],
        ]);
        foreach ([true, false] as $extended) {
            DB::connection()->enableQueryLog();
            DB::connection()->flushQueryLog();
            $teams = app(CallDurationController::class)->groupData('2026-10-27', $extended);
            $queries = array_column(DB::connection()->getQueryLog(), 'query');
            $this->assertFalse($teams[0]['members'][0]['absent']);
            $this->assertSame(1, $teams[0]['members'][0]['cm_total']);
            $this->assertCount(1, array_filter($queries, fn ($q) => str_contains($q, 'from "google_sheet_data"')));
            $presence = array_values(array_filter($queries, fn ($q) => str_contains($q, 'from "logins"')));
            $this->assertCount(1, $presence);
            $this->assertStringContainsString('distinct', strtolower($presence[0]));
            $this->assertStringNotContainsString('date(', strtolower($presence[0]));
        }
        DB::table('logins')->delete();
        DB::table('logins')->insert(['user_id' => 2, 'logged_in_at' => '2026-10-28 00:00:00']);
        $teams = app(CallDurationController::class)->groupData('2026-10-27', true);
        $this->assertTrue($teams[0]['members'][0]['absent']);
    }

    public function test_excel_uses_total_card_counts_shift_boundaries_and_the_template(): void
    {
        $teams = app(CallDurationController::class)->groupData('2026-10-27', true);
        $this->assertSame(1, $teams[0]['members'][0]['cm_total']);
        $response = $this->get(route('senior.excelgroup.excel', ['date' => '2026-10-27']));
        $response->assertOk()->assertDownload('recruiter-call-report-2026-10-27.xlsx');
        $file = $response->baseResponse->getFile()->getPathname();
        try {
            $archive = new PharData($file);
            $sheet = new DOMDocument(); $sheet->loadXML($archive['xl/worksheets/sheet1.xml']->getContent());
            $x = new DOMXPath($sheet); $x->registerNamespace('s', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $this->assertSame('=Recruiter & Name (Absent)', $x->evaluate('string(//s:c[@r="A4"]/s:is/s:t)'));
            $this->assertSame('1', $x->evaluate('string(//s:c[@r="D4"]/s:v)'));
            $this->assertSame('1', $x->evaluate('string(//s:c[@r="E4"]/s:v)'));
            $this->assertSame('1', $x->evaluate('string(//s:c[@r="F4"]/s:v)'));
            $this->assertEqualsWithDelta(10 / 86400, (float) $x->evaluate('string(//s:c[@r="G4"]/s:v)'), 0.000000001);
            $this->assertEqualsWithDelta(90000 / 86400, (float) $x->evaluate('string(//s:c[@r="H4"]/s:v)'), 0.000000001);
            $this->assertSame('170', $x->evaluate('string(//s:c[@r="I4"]/s:is/s:t)'));
            $this->assertEqualsWithDelta(30 / 86400, (float) $x->evaluate('string(//s:c[@r="G3"]/s:v)'), 0.000000001);
            $this->assertSame(4, $x->query('//s:sheetData/s:row')->length);
            $this->assertSame(0, $x->query('//s:f')->length);
            $this->assertSame('9', $x->evaluate('string(//s:pageSetup/@paperSize)'));
            $this->assertSame('landscape', $x->evaluate('string(//s:pageSetup/@orientation)'));
            $template = new PharData(resource_path('exports/daily-group-call-report-template.xlsx'));
            $this->assertSame($template['xl/styles.xml']->getContent(), $archive['xl/styles.xml']->getContent());
            $this->assertStringContainsString("'Daily Calls'!\$A\$1:\$I\$4", $archive['xl/workbook.xml']->getContent());
            unset($archive, $template);
        } finally { @unlink($file); }
    }

    public function test_pdf_download_and_view_use_the_same_report_columns(): void
    {
        $response = $this->get(route('senior.excelgroup.pdf', ['date' => '2026-10-27']));
        $response->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $response->getContent());
        $this->assertStringContainsString('841.89', $response->getContent());
        $html = view('user.callduration-group-download', [
            'rows' => [['name' => '<script>', 'leader' => true, 'called_mailed' => null, 'incoming' => 0, 'outgoing' => 1, 'duration' => 90010,
                'inbound_duration' => 10, 'outbound_duration' => 90010, 'extension' => '170']],
            'dateLabel' => '27-Oct-2026', 'day' => 'Tuesday', 'fmt' => [CallDurationController::class, 'durationText'],
        ])->render();
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('25:00:10', $html);
    }

    public function test_downloads_reject_unauthorized_roles_and_invalid_dates(): void
    {
        $this->actingAs((new User())->forceFill(['id' => 2, 'role' => 'junior']));
        $this->get(route('senior.excelgroup.excel'))->assertForbidden();
        $this->get(route('senior.excelgroup.pdf'))->assertForbidden();
        $this->actingAs((new User())->forceFill(['id' => 99, 'role' => 'admin']));
        $this->getJson(route('senior.excelgroup.excel', ['date' => '2026-02-30']))->assertUnprocessable();
        $this->getJson(route('senior.excelgroup.pdf', ['date' => 'bad']))->assertUnprocessable();
    }

    public function test_download_reads_calls_once_without_hourly_aggregation_and_sees_new_uploads(): void
    {
        DB::connection()->enableQueryLog();
        DB::connection()->flushQueryLog();
        $response = $this->get(route('senior.excelgroup.excel', ['date' => '2026-10-27']));
        $response->assertOk();
        $queries = array_column(DB::connection()->getQueryLog(), 'query');
        $callReads = array_filter($queries, fn ($query) => str_contains($query, 'from "call_duration_records"'));
        $this->assertCount(1, $callReads);
        $this->assertCount(5, $queries);
        $this->assertFalse((bool) array_filter($queries, fn ($query) => str_contains(strtoupper($query), 'HOUR(')));
        @unlink($response->baseResponse->getFile()->getPathname());

        DB::table('call_duration_records')->insert([
            'call_date' => '2026-10-27 13:00:00', 'source' => '170', 'destination' => '9729270064', 'answered_duration' => 60,
        ]);
        $fresh = $this->get(route('senior.excelgroup.excel', ['date' => '2026-10-27']));
        $fresh->assertOk();
        $file = $fresh->baseResponse->getFile()->getPathname();
        try {
            $archive = new PharData($file);
            $sheet = new DOMDocument(); $sheet->loadXML($archive['xl/worksheets/sheet1.xml']->getContent());
            $x = new DOMXPath($sheet); $x->registerNamespace('s', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $this->assertSame('2', $x->evaluate('string(//s:c[@r="F4"]/s:v)'));
            $this->assertEqualsWithDelta(90070 / 86400, (float) $x->evaluate('sum(//s:c[@r="G4" or @r="H4"]/s:v)'), 0.000000001);
            unset($archive);
        } finally { @unlink($file); }
    }

    public function test_page_has_two_early_slots_strict_eight_pm_boundary_and_complete_total(): void
    {
        $events = [
            ['08:29:58', 1000], // Before 6pm IST: excluded from every page slot and its total.
            ['08:30:00', 10], ['09:29:59', 20], // 6-7pm IST.
            ['09:30:00', 30], ['10:29:59', 40], // 7-8pm IST.
            ['10:30:00', 50], ['11:29:59', 60], // 8-9pm IST, separate from earlier activity.
            ['11:30:00', 70], // 9-10pm IST.
            ['14:30:00', 80], // Midnight next morning IST, still the selected shift.
            ['19:30:00', 90], ['20:29:59', 100], // 5am-6am IST is included in the final slot.
        ];
        foreach ($events as [$time, $seconds]) {
            DB::table('call_duration_records')->insert([
                'call_date' => '2026-10-27 ' . $time, 'source' => '170', 'destination' => '9729270064', 'answered_duration' => $seconds,
            ]);
            DB::table('google_sheet_data')->insert([
                'created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 ' . $time, 'Exe_Remarks' => 'Called & Mailed',
            ]);
        }
        DB::table('google_sheet_data')->insert([
            'created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 19:29:59', 'Exe_Remarks' => 'Called & Mailed',
        ]);
        $controller = app(CallDurationController::class);
        $view = $controller->group(Request::create('/dashboard/senior/call-duration/group', 'GET', ['date' => '2026-10-27']));
        $data = $view->getData();
        $this->assertCount(12, $data['slotTitles']);
        $this->assertSame(['6:00pm - 7:00pm', '7:00pm - 8:00pm', '8:00pm - 9:00pm', '9:00pm - 10:00pm'], array_slice($data['slotTitles'], 0, 4));
        $this->assertSame('4:00am - 5:00am', $data['slotTitles'][10]);
        $this->assertSame('5:00am - 6:00am', $data['slotTitles'][11]);
        $member = $data['teams'][0]['members'][0];
        $this->assertSame([30, 70, 90110, 70, 0, 0, 80, 0, 0, 0, 10, 190], $member['slots']);
        $this->assertSame([2, 2, 3, 1, 0, 0, 1, 0, 0, 0, 1, 2], $member['cm_slots']);
        $this->assertSame(90560, $member['total']);
        $this->assertSame(array_sum($member['slots']), $member['total']);
        $this->assertSame(12, $member['cm_total']);
        $this->assertSame(array_sum($member['cm_slots']), $member['cm_total']);

        // Existing scheduler/chart callers retain the original nine slots and indexes.
        $legacy = $controller->groupData('2026-10-27')[0]['members'][0];
        $this->assertSame(array_slice($member['slots'], 2, 9), $legacy['slots']);
        $this->assertSame(array_slice($member['cm_slots'], 2, 9), $legacy['cm_slots']);
        $this->assertSame(90270, $legacy['total']);
        $this->assertSame(6, $legacy['cm_total']);

        $response = $this->get(route('senior.excelgroup.excel', ['date' => '2026-10-27']));
        $response->assertOk();
        $file = $response->baseResponse->getFile()->getPathname();
        try {
            $archive = new PharData($file);
            $sheet = new DOMDocument(); $sheet->loadXML($archive['xl/worksheets/sheet1.xml']->getContent());
            $x = new DOMXPath($sheet); $x->registerNamespace('s', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $this->assertSame('12', $x->evaluate('string(//s:c[@r="D4"]/s:v)'));
            $this->assertSame('11', $x->evaluate('string(//s:c[@r="F4"]/s:v)'));
            $this->assertEqualsWithDelta($member['total'] / 86400, (float) $x->evaluate('sum(//s:c[@r="G4" or @r="H4"]/s:v)'), 0.000000001);
            unset($archive);
        } finally { @unlink($file); }

        $pdfRows = [];
        View::composer('user.callduration-group-download', function ($view) use (&$pdfRows) {
            $pdfRows = $view->getData()['rows'];
        });
        $this->get(route('senior.excelgroup.pdf', ['date' => '2026-10-27']))
            ->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertSame(12, $pdfRows[1]['called_mailed']);
        $this->assertSame(11, $pdfRows[1]['outgoing']);
        $this->assertSame($member['total'], $pdfRows[1]['duration']);
    }

    public function test_early_slots_follow_ist_when_eastern_daylight_saving_has_ended(): void
    {
        foreach ([['07:29:59', 999], ['07:30:00', 10], ['08:30:00', 20], ['09:30:00', 30], ['18:29:59', 40], ['18:30:00', 50], ['19:29:59', 60], ['19:30:00', 999]] as [$time, $seconds]) {
            DB::table('call_duration_records')->insert([
                'call_date' => '2026-11-08 ' . $time, 'source' => '170', 'destination' => '9729270064', 'answered_duration' => $seconds,
            ]);
        }
        $member = app(CallDurationController::class)->groupData('2026-11-08', true)[0]['members'][0];
        $this->assertSame([10, 20, 30, 0, 0, 0, 0, 0, 0, 0, 40, 110], $member['slots']);
        $this->assertSame(210, $member['total']);
    }

    public function test_group_page_uses_five_queries_without_discarded_hourly_query(): void
    {
        DB::connection()->enableQueryLog();
        DB::connection()->flushQueryLog();
        $view = app(CallDurationController::class)->group(Request::create(
            '/dashboard/senior/call-duration/group', 'GET', ['date' => '2026-10-27']
        ));
        $queries = array_column(DB::connection()->getQueryLog(), 'query');
        $this->assertCount(5, $queries);
        $this->assertCount(1, array_filter($queries, fn ($q) => str_contains($q, 'from "call_duration_records"')));
        $this->assertCount(1, array_filter($queries, fn ($q) => str_contains($q, 'from "google_sheet_data"')));
        $this->assertFalse((bool) array_filter($queries, fn ($q) => str_contains(strtoupper($q), 'HOUR(')));
        $member = $view->getData()['teams'][0]['members'][0];
        $this->assertSame(90010, $member['total']);
        $this->assertSame(1, $member['cm_total']);
    }

    public function test_separate_total_duration_page_and_downloads_include_early_and_final_hours(): void
    {
        foreach ([['08:30:00', 10], ['09:30:00', 20], ['19:30:00', 30], ['20:29:59', 40]] as [$time, $seconds]) {
            DB::table('call_duration_records')->insert([
                'call_date' => '2026-10-27 ' . $time, 'source' => '170', 'destination' => '9729270064', 'answered_duration' => $seconds,
            ]);
            DB::table('google_sheet_data')->insert([
                'created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 ' . $time, 'Exe_Remarks' => 'Called & Mailed',
            ]);
        }
        DB::connection()->enableQueryLog();
        DB::connection()->flushQueryLog();
        $data = app(CallDurationController::class)->total(Request::create(
            '/dashboard/senior/call-duration/total', 'GET', ['date' => '2026-10-27']
        ))->getData();
        $queries = array_column(DB::connection()->getQueryLog(), 'query');
        $this->assertCount(4, $queries);
        $this->assertFalse((bool) array_filter($queries, fn ($q) => str_contains($q, 'call_duration_summaries') || str_contains($q, 'call_duration_shifts') || str_contains($q, 'from "logins"')));
        $this->assertCount(12, $data['slotTitles']);
        $this->assertSame('6:00pm - 7:00pm', $data['slotTitles'][0]);
        $this->assertSame('5:00am - 6:00am', $data['slotTitles'][11]);
        $member = $data['teams']['Leader'][0];
        $this->assertSame([10, 20, 90000, 0, 0, 0, 0, 0, 0, 0, 10, 70], $member['slots']);
        $this->assertSame(90110, $member['total']);
        $this->assertSame(5, $member['cm_total']);
        $group = app(CallDurationController::class)->groupData('2026-10-27', true)[0]['members'][0];
        $this->assertSame($group['total'], $member['total']);
        $this->assertSame($group['cm_total'], $member['cm_total']);

        $response = $this->get(route('senior.totalcall.excel', ['date' => '2026-10-27']));
        $response->assertOk()->assertDownload('total-call-duration-2026-10-27.xlsx');
        $file = $response->baseResponse->getFile()->getPathname();
        try {
            $archive = new PharData($file);
            $sheet = new DOMDocument(); $sheet->loadXML($archive['xl/worksheets/sheet1.xml']->getContent());
            $x = new DOMXPath($sheet); $x->registerNamespace('s', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $this->assertSame('Called & Mailed', $x->evaluate('string(//s:c[@r="F2"]/s:is/s:t)'));
            $this->assertSame('5', $x->evaluate('string(//s:c[@r="F3"]/s:v)'));
            $this->assertSame('25:01:50', $x->evaluate('string(//s:c[@r="E3"]/s:is/s:t)'));
            $this->assertSame('0:00:10', $x->evaluate('string(//s:c[@r="G3"]/s:is/s:t)'));
            $this->assertSame('0:01:10', $x->evaluate('string(//s:c[@r="R3"]/s:is/s:t)'));
            unset($archive);
        } finally { @unlink($file); }

        $pdfData = [];
        View::composer('user.callduration-total-pdf', function ($view) use (&$pdfData) { $pdfData = $view->getData(); });
        $this->get(route('senior.totalcall.pdf', ['date' => '2026-10-27']))->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertCount(12, $pdfData['slotTitles']);
        $this->assertSame(90110, $pdfData['teams']['Leader'][0]['total']);
        $this->assertSame(5, $pdfData['teams']['Leader'][0]['cm_total']);
    }

    public function test_total_duration_default_shift_starts_at_six_pm_and_duplicate_members_are_counted_once(): void
    {
        DB::table('users')->insert(['id' => 4, 'name' => 'Second Leader', 'role' => 'senior', 'mail' => '[2]', 'phone' => '207']);
        $controller = app(CallDurationController::class);
        $teams = $controller->total(Request::create('/dashboard/senior/call-duration/total', 'GET', ['date' => '2026-10-27']))->getData()['teams'];
        $this->assertCount(1, $teams);
        $this->assertArrayHasKey('Second Leader', $teams);
        $this->assertCount(1, $teams['Second Leader']);
        try {
            Carbon::setTestNow(Carbon::parse('2026-10-28 17:59:59', 'Asia/Kolkata'));
            $this->assertSame('2026-10-27', $controller->total(Request::create('/dashboard/senior/call-duration/total'))->getData()['date']);
            Carbon::setTestNow(Carbon::parse('2026-10-28 18:00:00', 'Asia/Kolkata'));
            $this->assertSame('2026-10-28', $controller->total(Request::create('/dashboard/senior/call-duration/total'))->getData()['date']);
        } finally { Carbon::setTestNow(); }
    }

    public function test_total_duration_omits_presence_query_without_changing_results_or_caching_uploads(): void
    {
        $controller = app(CallDurationController::class);
        $request = Request::create('/dashboard/senior/call-duration/total', 'GET', ['date' => '2026-10-27']);
        $before = $controller->total($request)->getData()['teams'];
        $this->assertTrue($controller->groupData('2026-10-27', true)[0]['members'][0]['absent']);
        DB::table('logins')->insert(['user_id' => 2, 'logged_in_at' => '2026-10-27 11:00:00']);
        DB::connection()->enableQueryLog();
        DB::connection()->flushQueryLog();
        $after = $controller->total($request)->getData()['teams'];
        $queries = array_column(DB::connection()->getQueryLog(), 'query');
        $this->assertSame($before, $after);
        $this->assertCount(4, $queries);
        $this->assertFalse((bool) array_filter($queries, fn ($q) => str_contains($q, 'from "logins"')));
        $this->assertFalse($controller->groupData('2026-10-27', true)[0]['members'][0]['absent']);

        DB::table('call_duration_records')->insert([
            'call_date' => '2026-10-27 20:00:00', 'source' => '170', 'destination' => '9729270064', 'answered_duration' => 60,
        ]);
        DB::table('google_sheet_data')->insert([
            'created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 20:00:00', 'Exe_Remarks' => 'Called & Mailed',
        ]);
        $fresh = $controller->total($request)->getData()['teams']['Leader'][0];
        $this->assertSame($before['Leader'][0]['total'] + 60, $fresh['total']);
        $this->assertSame($before['Leader'][0]['cm_total'] + 1, $fresh['cm_total']);
        $this->assertSame(60, $fresh['slots'][11]);
    }

    public function test_senior_mail_chart_page_has_twelve_exact_slots_total_and_no_view_queries(): void
    {
        $date = date('Y-m-d');
        $from = Carbon::parse($date . ' 18:00:00', 'Asia/Kolkata');
        DB::table('google_sheet_data')->delete();
        DB::table('logins')->delete();
        DB::table('users')->insert(['id' => 5, 'name' => 'Absent Recruiter', 'role' => 'junior', 'mail' => null, 'phone' => '171']);
        DB::table('users')->where('id', 1)->update(['mail' => '[2,5]']);
        DB::table('logins')->insert(['user_id' => 2, 'logged_in_at' => $date . ' 11:00:00']);
        for ($slot = 0; $slot < 12; $slot++) {
            DB::table('google_sheet_data')->insert([
                'created_by' => '2|junior', 'followup' => $date,
                'updated_at' => $from->copy()->addHours($slot)->setTimezone('America/New_York')->format('Y-m-d H:i:s'),
                'Exe_Remarks' => 'Called & Mailed',
            ]);
        }
        foreach ([$from->copy()->subSecond(), $from->copy()->addHours(12)] as $outside) {
            DB::table('google_sheet_data')->insert([
                'created_by' => '2|junior', 'followup' => $date,
                'updated_at' => $outside->setTimezone('America/New_York')->format('Y-m-d H:i:s'), 'Exe_Remarks' => 'Called & Mailed',
            ]);
        }
        DB::table('google_sheet_data')->insert([
            'created_by' => '5|junior', 'followup' => $date,
            'updated_at' => $from->copy()->setTimezone('America/New_York')->format('Y-m-d H:i:s'), 'Exe_Remarks' => 'Called & Mailed',
        ]);
        DB::connection()->enableQueryLog();
        DB::connection()->flushQueryLog();
        $data = app(CallReportController::class)->seniorgroupmailchartPage(Request::create('/', 'GET'))->getData();
        $this->assertCount(4, DB::connection()->getQueryLog());
        $this->assertSame('seniorgroupmailchartPage', Route::getRoutes()->getByName('senior.groupmailchart')->getActionMethod());
        $this->assertCount(12, $data['slotTitles']);
        $this->assertSame('6:00pm - 7:00pm', $data['slotTitles'][0]);
        $this->assertSame('5:00am - 6:00am', $data['slotTitles'][11]);
        $junior = $data['seniors'][0]->juniors->firstWhere('id', 2);
        $this->assertSame(array_fill(0, 12, 1), $junior->cm_slots);
        $this->assertSame(12, $junior->cm_total);
        $group = app(CallDurationController::class)->groupData($date, true)[0]['members'][0];
        $this->assertSame($group['cm_slots'], $junior->cm_slots);
        $this->assertSame($group['cm_total'], $junior->cm_total);
        $this->assertTrue($data['loggedInIds']->has(2));
        $this->assertFalse($data['loggedInIds']->has(5));

        // Render the content without the unrelated application layout/database composers.
        $source = str_replace("@extends('layout.layout')", '', file_get_contents(resource_path('views/user/seniorgroupmailchart.blade.php')));
        DB::connection()->flushQueryLog();
        $html = Blade::render($source . "\n@yield('content')", $data);
        $this->assertCount(0, DB::connection()->getQueryLog());
        $this->assertSame(13, substr_count($html, 'id="copySection'));
        $this->assertGreaterThan(strpos($html, '5:00am - 6:00am'), strpos($html, 'Total C&amp;M Count'));
        $this->assertMatchesRegularExpression('/Absent Recruiter\s*-\s*ab/', $html);
        $this->assertMatchesRegularExpression('/=Recruiter &amp; Name\s*-\s*12/', $html);

        // Scheduled mail services still call the unchanged original controller method.
        $legacy = app(CallReportController::class)->seniorgroupmailchart(Request::create('/', 'GET'))->getData();
        $legacyJunior = $legacy['seniors'][0]->juniors->firstWhere('id', 2);
        $this->assertArrayNotHasKey('expandedReport', $legacy);
        $this->assertSame(1, $legacyJunior->t8to9am);
        $this->assertSame(0, $legacyJunior->t9to10am);
        $this->assertSame(0, $legacyJunior->t10to11am);
        $this->assertSame(1, $legacyJunior->t6to7pm);
        $this->assertSame(0, $legacyJunior->t7to8pm);
    }

    public function test_chart_login_lookup_deduplicates_users_and_preserves_calendar_day_boundaries(): void
    {
        $date = date('Y-m-d');
        $start = Carbon::parse($date . ' 00:00:00');
        DB::table('logins')->delete();
        DB::table('users')->insert(['id' => 5, 'name' => 'Absent Recruiter', 'role' => 'junior', 'mail' => null, 'phone' => '171']);
        DB::table('users')->where('id', 1)->update(['mail' => '[2,5]']);
        DB::table('logins')->insert([
            ['user_id' => 2, 'logged_in_at' => $start->format('Y-m-d H:i:s')],
            ['user_id' => 2, 'logged_in_at' => $date . ' 12:00:00'],
            ['user_id' => 2, 'logged_in_at' => $date . ' 23:59:59'],
            ['user_id' => 5, 'logged_in_at' => $start->copy()->subSecond()->format('Y-m-d H:i:s')],
            ['user_id' => 5, 'logged_in_at' => $start->copy()->addDay()->format('Y-m-d H:i:s')],
        ]);
        DB::connection()->enableQueryLog();
        DB::connection()->flushQueryLog();
        $data = app(CallReportController::class)->seniorgroupmailchartPage(Request::create('/', 'GET'))->getData();
        $queries = DB::connection()->getQueryLog();
        $this->assertCount(4, $queries);
        $loginQueries = array_values(array_filter($queries, fn ($q) => str_contains($q['query'], 'from "logins"')));
        $this->assertCount(1, $loginQueries);
        $this->assertStringContainsString('distinct', strtolower($loginQueries[0]['query']));
        $this->assertStringNotContainsString('date(', strtolower($loginQueries[0]['query']));
        $this->assertStringContainsString('"logged_in_at" >=', $loginQueries[0]['query']);
        $this->assertStringContainsString('"logged_in_at" <', $loginQueries[0]['query']);
        $this->assertSame([2], $data['loggedInIds']->keys()->all());
        $this->assertTrue($data['seniors'][0]->juniors->firstWhere('id', 2)->report_present);
        $this->assertFalse($data['seniors'][0]->juniors->firstWhere('id', 5)->report_present);
    }

    private function prepareNavbarUploads(): void
    {
        Schema::create('call_duration_uploads', function (Blueprint $table) {
            $table->id(); $table->dateTime('covers_from'); $table->dateTime('covers_to');
        });
        DB::connection()->getPdo()->sqliteCreateFunction('CHAR_LENGTH', fn ($value) => strlen($value));
        DB::connection()->getPdo()->sqliteCreateFunction('regexp', fn ($pattern, $value) => (int) preg_match('/' . $pattern . '/D', $value));
    }

    public function test_navbar_matches_total_duration_for_the_full_shift_using_one_query(): void
    {
        $this->prepareNavbarUploads();
        foreach ([['08:30:00', 10], ['09:30:00', 20], ['19:30:00', 30], ['20:29:59', 40]] as [$time, $seconds]) {
            DB::table('call_duration_records')->insert([
                'call_date' => '2026-10-27 ' . $time, 'source' => '170', 'destination' => '9729270064', 'answered_duration' => $seconds,
            ]);
        }
        DB::table('call_duration_records')->insert([
            ['call_date' => '2026-10-27 13:00:00', 'source' => '170', 'destination' => '170', 'answered_duration' => 5],
            ['call_date' => '2026-10-27 13:00:01', 'source' => '183', 'destination' => '170', 'answered_duration' => 500],
            ['call_date' => '2026-10-27 13:00:02', 'source' => '0170', 'destination' => '170', 'answered_duration' => 800],
            ['call_date' => '2026-10-27 13:00:03', 'source' => 'abc', 'destination' => '170', 'answered_duration' => 8],
        ]);
        DB::table('call_duration_uploads')->insert(['covers_from' => '2026-10-27 18:00:00', 'covers_to' => '2026-10-28 06:00:00']);
        try {
            Carbon::setTestNow(Carbon::parse('2026-10-28 07:00:00', 'Asia/Kolkata'));
            DB::connection()->enableQueryLog();
            DB::connection()->flushQueryLog();
            $pill = NavbarCallDuration::pill((object) ['role' => 'junior', 'phone' => '170']);
            $queries = array_column(DB::connection()->getQueryLog(), 'query');
            $this->assertCount(1, $queries);
            $this->assertFalse((bool) array_filter($queries, fn ($q) => str_contains($q, 'call_duration_summaries') || str_contains($q, 'call_duration_shifts')));
            $this->assertSame('25:02:03', $pill['value']);
            $this->assertSame('Final', $pill['tag']);
            $this->assertStringContainsString('(6pm-6am IST)', $pill['title']);
            $this->assertStringContainsString('6-7pm 0:00:10', $pill['title']);
            $this->assertStringContainsString('5-6am 0:01:10', $pill['title']);
            $total = app(CallDurationController::class)->total(Request::create('/', 'GET', ['date' => '2026-10-27']))->getData()['teams']['Leader'][0]['total'];
            $this->assertSame(CallDurationController::durationText($total), $pill['value']);
        } finally { Carbon::setTestNow(); }
    }

    public function test_navbar_shift_rollover_no_extension_and_new_uploads(): void
    {
        $this->prepareNavbarUploads();
        $user = (object) ['role' => 'junior', 'phone' => '170'];
        try {
            Carbon::setTestNow(Carbon::parse('2026-10-27 17:59:59', 'Asia/Kolkata'));
            $before = NavbarCallDuration::pill($user);
            $this->assertSame('0:00:00', $before['value']);
            $this->assertStringContainsString('shift of 26 Oct', $before['title']);
            Carbon::setTestNow(Carbon::parse('2026-10-27 18:00:00', 'Asia/Kolkata'));
            $start = NavbarCallDuration::pill($user);
            $this->assertSame('25:00:10', $start['value']);
            $this->assertSame('1h delay', $start['tag']);
            DB::table('call_duration_records')->insert([
                'call_date' => '2026-10-27 20:00:00', 'source' => '170', 'destination' => '9729270064', 'answered_duration' => 60,
            ]);
            Carbon::setTestNow(Carbon::parse('2026-10-28 05:59:59', 'Asia/Kolkata'));
            $this->assertSame('25:01:10', NavbarCallDuration::pill($user)['value']);
            Carbon::setTestNow(Carbon::parse('2026-10-28 18:00:00', 'Asia/Kolkata'));
            $this->assertSame('0:00:00', NavbarCallDuration::pill($user)['value']);
            DB::connection()->enableQueryLog(); DB::connection()->flushQueryLog();
            $this->assertNull(NavbarCallDuration::pill((object) ['role' => 'senior', 'phone' => '207']));
            $this->assertSame('No ext', NavbarCallDuration::pill((object) ['role' => 'junior', 'phone' => '-'])['tag']);
            $this->assertCount(0, DB::connection()->getQueryLog());
        } finally { Carbon::setTestNow(); }
    }

    public function test_navbar_uses_correct_ist_window_after_eastern_daylight_saving_ends(): void
    {
        $this->prepareNavbarUploads();
        foreach ([['07:29:59', 999], ['07:30:00', 10], ['08:30:00', 20], ['18:30:00', 30], ['19:29:59', 40], ['19:30:00', 999]] as [$time, $seconds]) {
            DB::table('call_duration_records')->insert([
                'call_date' => '2026-11-08 ' . $time, 'source' => '170', 'destination' => '9729270064', 'answered_duration' => $seconds,
            ]);
        }
        DB::table('call_duration_uploads')->insert(['covers_from' => '2026-11-08 18:00:00', 'covers_to' => '2026-11-09 06:00:00']);
        try {
            Carbon::setTestNow(Carbon::parse('2026-11-09 07:00:00', 'Asia/Kolkata'));
            $pill = NavbarCallDuration::pill((object) ['role' => 'junior', 'phone' => '170']);
            $this->assertSame('0:01:40', $pill['value']);
            $this->assertSame('Final', $pill['tag']);
        } finally { Carbon::setTestNow(); }
    }

    public function test_navbar_single_query_preserves_coverage_status_when_no_calls_match(): void
    {
        $this->prepareNavbarUploads();
        DB::table('call_duration_uploads')->insert(['covers_from' => '2026-10-27 18:00:00', 'covers_to' => '2026-10-27 20:00:00']);
        $user = (object) ['role' => 'junior', 'phone' => '999'];
        try {
            Carbon::setTestNow(Carbon::parse('2026-10-27 22:00:00', 'Asia/Kolkata'));
            $pill = NavbarCallDuration::pill($user);
            $this->assertSame('0:00:00', $pill['value']);
            $this->assertSame('No data', $pill['tag']);
            Carbon::setTestNow(Carbon::parse('2026-10-27 21:00:00', 'Asia/Kolkata'));
            $this->assertSame('1h delay', NavbarCallDuration::pill($user)['tag']);
            DB::table('call_duration_uploads')->update(['covers_to' => '2026-10-28 06:00:00']);
            Carbon::setTestNow(Carbon::parse('2026-10-28 07:00:00', 'Asia/Kolkata'));
            DB::connection()->enableQueryLog(); DB::connection()->flushQueryLog();
            $final = NavbarCallDuration::pill($user);
            $this->assertSame('0:00:00', $final['value']);
            $this->assertSame('Final', $final['tag']);
            $this->assertCount(1, DB::connection()->getQueryLog());
        } finally { Carbon::setTestNow(); }
    }

    public function test_navbar_cumulative_sums_preserve_every_hour_boundary_and_total(): void
    {
        $this->prepareNavbarUploads();
        $date = '2026-12-09';
        $start = Carbon::parse($date . ' 18:00:00', 'Asia/Kolkata');
        for ($slot = 0; $slot < 12; $slot++) {
            $n = $slot + 1;
            foreach ([[0, 10 * $n, '170', '9729270064'], [1800, $n, '13146141600', '170'], [3599, 2 * $n, '170', '9729270064']] as [$seconds, $duration, $source, $destination]) {
                DB::table('call_duration_records')->insert([
                    'call_date' => $start->copy()->addHours($slot)->addSeconds($seconds)->setTimezone('America/New_York')->format('Y-m-d H:i:s'),
                    'source' => $source, 'destination' => $destination, 'answered_duration' => $duration,
                ]);
            }
        }
        foreach ([$start->copy()->subSecond(), $start->copy()->addHours(12)] as $outside) {
            DB::table('call_duration_records')->insert([
                'call_date' => $outside->setTimezone('America/New_York')->format('Y-m-d H:i:s'),
                'source' => '170', 'destination' => '9729270064', 'answered_duration' => 999,
            ]);
        }
        DB::table('call_duration_uploads')->insert(['covers_from' => $date . ' 18:00:00', 'covers_to' => '2026-12-10 06:00:00']);
        try {
            Carbon::setTestNow(Carbon::parse('2026-12-10 07:00:00', 'Asia/Kolkata'));
            DB::connection()->enableQueryLog(); DB::connection()->flushQueryLog();
            $pill = NavbarCallDuration::pill((object) ['role' => 'junior', 'phone' => '170']);
            $this->assertCount(1, DB::connection()->getQueryLog());
            $this->assertSame('0:16:54', $pill['value']);
            $labels = ['6-7pm','7-8pm','8-9pm','9-10pm','10-11pm','11pm-12am','12-1am','1-2am','2-3am','3-4am','4-5am','5-6am'];
            foreach ($labels as $slot => $label) {
                $this->assertStringContainsString($label . ' ' . CallDurationController::durationText(13 * ($slot + 1)), $pill['title']);
            }
            $member = app(CallDurationController::class)->groupData($date, true)[0]['members'][0];
            $this->assertSame(array_map(fn ($n) => 13 * $n, range(1, 12)), $member['slots']);
            $this->assertSame(CallDurationController::durationText($member['total']), $pill['value']);
        } finally { Carbon::setTestNow(); }
    }
}
