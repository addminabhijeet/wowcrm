<?php

namespace Tests\Feature;

use App\Http\Controllers\CallDurationController;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
            $t->id(); $t->dateTime('call_date'); $t->string('source'); $t->string('destination'); $t->integer('call_duration');
        });
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Leader', 'role' => 'senior', 'mail' => '[2]', 'phone' => '183 and 193'],
            ['id' => 2, 'name' => '=Recruiter & Name', 'role' => 'junior', 'mail' => null, 'phone' => '170'],
            ['id' => 3, 'name' => 'Unassigned', 'role' => 'junior', 'mail' => null, 'phone' => '171'],
        ]);
        // Shift 27 Oct: 20:00 IST / 10:30 Eastern through 05:00 IST / 19:30 Eastern, exclusive.
        DB::table('call_duration_records')->insert([
            ['call_date' => '2026-10-27 10:29:59', 'source' => '170', 'destination' => '9729270064', 'call_duration' => 999],
            ['call_date' => '2026-10-27 10:30:00', 'source' => '170', 'destination' => '9729270064', 'call_duration' => 90000],
            ['call_date' => '2026-10-27 19:29:59', 'source' => '9729270064', 'destination' => '170', 'call_duration' => 10],
            ['call_date' => '2026-10-27 19:30:00', 'source' => '170', 'destination' => '9729270064', 'call_duration' => 999],
            ['call_date' => '2026-10-27 12:00:00', 'source' => '183', 'destination' => '193', 'call_duration' => 30],
        ]);
        DB::table('google_sheet_data')->insert([
            ['created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 11:00:00', 'Exe_Remarks' => 'Called & Mailed'],
            ['created_by' => '2|junior', 'followup' => '2026-10-27', 'updated_at' => '2026-10-27 19:30:00', 'Exe_Remarks' => 'Called & Mailed'],
            ['created_by' => '2|junior', 'followup' => '2026-10-26', 'updated_at' => '2026-10-27 11:00:00', 'Exe_Remarks' => 'Called & Mailed'],
        ]);
        $this->actingAs((new User())->forceFill(['id' => 99, 'name' => 'Admin', 'role' => 'admin']));
    }

    protected function tearDown(): void
    {
        DB::disconnect('sqlite');
        parent::tearDown();
    }

    public function test_excel_uses_total_card_counts_shift_boundaries_and_the_template(): void
    {
        $teams = app(CallDurationController::class)->groupData('2026-10-27');
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
            $this->assertEqualsWithDelta(90010 / 86400, (float) $x->evaluate('string(//s:c[@r="G4"]/s:v)'), 0.000000001);
            $this->assertEqualsWithDelta(30 / 86400, (float) $x->evaluate('string(//s:c[@r="G3"]/s:v)'), 0.000000001);
            $this->assertSame(4, $x->query('//s:sheetData/s:row')->length);
            $this->assertSame(0, $x->query('//s:f')->length);
            $this->assertSame('9', $x->evaluate('string(//s:pageSetup/@paperSize)'));
            $this->assertSame('landscape', $x->evaluate('string(//s:pageSetup/@orientation)'));
            $template = new PharData(resource_path('exports/daily-group-call-report-template.xlsx'));
            $this->assertSame($template['xl/styles.xml']->getContent(), $archive['xl/styles.xml']->getContent());
            $this->assertStringContainsString("'Daily Calls'!\$A\$1:\$G\$4", $archive['xl/workbook.xml']->getContent());
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
            'rows' => [['name' => '<script>', 'leader' => true, 'called_mailed' => null, 'incoming' => 0, 'outgoing' => 1, 'duration' => 90010]],
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
}
