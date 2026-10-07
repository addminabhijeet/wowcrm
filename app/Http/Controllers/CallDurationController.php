<?php

namespace App\Http\Controllers;

use App\Exports\SimpleXlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Sidebar "Call Duration": upload the PBX "Export_Callrecords" sheet (.csv) into call_duration_records,
 * then show / filter / download it (Group Report).
 */
class CallDurationController extends Controller
{
    private const TABLE = 'call_duration_records';
    private const ROLES = ['admin', 'operation', 'career', 'senior'];
    private const PDF_MAX_ROWS = 3000;

    /** Outbound = an extension calling an external (7+ digit) number. Everything else (incoming, internal, *97) is inbound. */
    private const OUTBOUND = "(CHAR_LENGTH(source) < 7 AND CHAR_LENGTH(destination) >= 7 AND destination REGEXP '^[0-9]+$')";

    private function authorizeAccess(): void
    {
        abort_unless(in_array(auth()->user()->role ?? '', self::ROLES, true), 403);
    }

    // ---------------------------------------------------------------- upload

    public function upload()
    {
        $this->authorizeAccess();

        return view('user.callduration-upload', [
            'stats'  => DB::table(self::TABLE)->selectRaw('COUNT(*) as total, MIN(call_date) as first_call, MAX(call_date) as last_call')->first(),
            'recent' => DB::table(self::TABLE)
                ->selectRaw('source_file, COUNT(*) as total, MAX(created_at) as uploaded_at')
                ->groupBy('source_file')->orderByDesc('uploaded_at')->limit(10)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAccess();
        @set_time_limit(300);

        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt', 'max:51200']]);
        $file = $request->file('file');

        $fh = fopen($file->getRealPath(), 'r');
        $header = $fh ? fgetcsv($fh) : false;
        if (!$header) {
            return back()->withErrors(['file' => 'The file is empty or unreadable.']);
        }
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);

        $map = [];
        foreach ($header as $i => $h) {
            $key = self::headerKey((string) $h);
            if ($key && !isset($map[$key])) {
                $map[$key] = $i;
            }
        }
        foreach (['call_date' => 'Call Date', 'source' => 'Source', 'destination' => 'Destination', 'call_duration' => 'Call Duration'] as $k => $label) {
            if (!isset($map[$k])) {
                fclose($fh);

                return back()->withErrors(['file' => "Column \"{$label}\" not found. Upload the Export_Callrecords sheet as .csv."]);
            }
        }

        $get = fn (array $row, string $k) => isset($map[$k], $row[$map[$k]]) ? trim((string) $row[$map[$k]]) : '';
        $now = now();
        $name = mb_substr($file->getClientOriginalName(), 0, 255);
        $batch = [];
        $read = $inserted = $invalid = 0;

        $flush = function () use (&$batch, &$inserted) {
            if ($batch) {
                $inserted += DB::table(self::TABLE)->insertOrIgnore(array_values($batch));
                $batch = [];
            }
        };

        while (($row = fgetcsv($fh)) !== false) {
            if ($row === [null] || !array_filter($row, fn ($v) => trim((string) $v) !== '')) {
                continue;
            }
            $read++;

            $ts = strtotime($get($row, 'call_date'));
            $source = $get($row, 'source');
            $destination = $get($row, 'destination');
            if ($ts === false || $source === '' || $destination === '') {
                $invalid++;
                continue;
            }

            $rec = [
                'sl_no'             => is_numeric($get($row, 'sl_no')) ? (int) $get($row, 'sl_no') : null,
                'call_date'         => date('Y-m-d H:i:s', $ts),
                'source'            => mb_substr($source, 0, 40),
                'destination'       => mb_substr($destination, 0, 40),
                'call_duration'     => max(0, (int) $get($row, 'call_duration')),
                'answered_duration' => max(0, (int) $get($row, 'answered_duration')),
                'caller_id'         => mb_substr($get($row, 'caller_id'), 0, 150) ?: null,
                'did'               => mb_substr($get($row, 'did'), 0, 40) ?: null,
                'disposition'       => mb_substr($get($row, 'disposition'), 0, 30) ?: null,
                'time_zone'         => mb_substr($get($row, 'time_zone'), 0, 40) ?: null,
            ];
            $rec['row_hash'] = sha1(implode('|', [
                $rec['call_date'], $rec['source'], $rec['destination'], $rec['call_duration'],
                $rec['answered_duration'], $rec['caller_id'], $rec['did'], $rec['disposition'],
            ]));
            $rec += ['source_file' => $name, 'uploaded_by' => auth()->id(), 'created_at' => $now, 'updated_at' => $now];

            $batch[$rec['row_hash']] = $rec;
            if (count($batch) >= 500) {
                $flush();
            }
        }
        fclose($fh);
        $flush();

        $duplicates = $read - $invalid - $inserted;

        return redirect()->route('senior.excelupload')->with(
            'success',
            "{$name}: {$read} rows read, {$inserted} added, {$duplicates} already stored (skipped)" . ($invalid ? ", {$invalid} invalid (skipped)" : '') . '.'
        );
    }

    /** Header text of the sheet -> column key ("Amswered Duration" is how the PBX spells it). */
    private static function headerKey(string $header): ?string
    {
        $n = strtolower(preg_replace('/[^a-z0-9]/i', '', $header));

        return match (true) {
            $n === 'slno'                                              => 'sl_no',
            $n === 'calldate'                                          => 'call_date',
            $n === 'source'                                            => 'source',
            $n === 'destination'                                       => 'destination',
            str_starts_with($n, 'callduration')                        => 'call_duration',
            str_starts_with($n, 'amswered'), str_starts_with($n, 'answered') => 'answered_duration',
            $n === 'callerid'                                          => 'caller_id',
            $n === 'did'                                               => 'did',
            $n === 'disposition'                                       => 'disposition',
            $n === 'timezone'                                          => 'time_zone',
            default                                                    => null,
        };
    }

    // ---------------------------------------------------------------- group report

    /** Effective filters (defaults to the day of the newest stored call). */
    private function filters(Request $request): array
    {
        $latest = DB::table(self::TABLE)->max('call_date');
        $default = $latest ? substr($latest, 0, 10) : now()->toDateString();

        $date = function ($v) use ($default) {
            $v = (string) $v;

            return preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $v, $m) && checkdate((int) $m[2], (int) $m[3], (int) $m[1]) ? $v : $default;
        };
        $hour = fn ($v, $d) => is_numeric($v) ? max(0, min(23, (int) $v)) : $d;

        $f = [
            'start_date' => $date($request->input('start_date')),
            'start_time' => $hour($request->input('start_time'), 0),
            'end_date'   => $date($request->input('end_date')),
            'end_time'   => $hour($request->input('end_time'), 23),
            'extension'  => mb_substr(trim((string) $request->input('extension')), 0, 40),
        ];
        if ($f['start_date'] > $f['end_date']) {
            [$f['start_date'], $f['end_date']] = [$f['end_date'], $f['start_date']];
        }

        return $f;
    }

    private function query(array $f)
    {
        $q = DB::table(self::TABLE)->whereBetween('call_date', [
            sprintf('%s %02d:00:00', $f['start_date'], $f['start_time']),
            sprintf('%s %02d:59:59', $f['end_date'], $f['end_time']),
        ]);
        if ($f['extension'] !== '') {
            $q->where(fn ($w) => $w->where('source', $f['extension'])->orWhere('destination', $f['extension']));
        }

        return $q;
    }

    private static function hms($seconds): string
    {
        $s = (int) $seconds;

        return sprintf('%d:%02d:%02d', intdiv($s, 3600), intdiv($s % 3600, 60), $s % 60);
    }

    private function totals(array $f): array
    {
        $out = self::OUTBOUND;
        $t = $this->query($f)->selectRaw(
            "COUNT(*) as calls,
             COALESCE(SUM(CASE WHEN {$out} THEN 0 ELSE call_duration END), 0) as in_call,
             COALESCE(SUM(CASE WHEN {$out} THEN call_duration ELSE 0 END), 0) as out_call,
             COALESCE(SUM(CASE WHEN {$out} THEN 0 ELSE answered_duration END), 0) as in_ans,
             COALESCE(SUM(CASE WHEN {$out} THEN answered_duration ELSE 0 END), 0) as out_ans"
        )->first();

        return [
            'calls'        => (int) $t->calls,
            'inbound'      => self::hms($t->in_call),
            'outbound'     => self::hms($t->out_call),
            'inbound_ans'  => self::hms($t->in_ans),
            'outbound_ans' => self::hms($t->out_ans),
        ];
    }

    public function show(Request $request)
    {
        $this->authorizeAccess();
        $f = $this->filters($request);

        return view('user.callduration-show', [
            'f'          => $f,
            'totals'     => $this->totals($f),
            'rows'       => $this->query($f)->orderByDesc('call_date')->orderByDesc('id')->paginate(50)->withQueryString(),
            'extensions' => DB::table(self::TABLE)->whereRaw('CHAR_LENGTH(source) < 7')->distinct()
                ->orderByRaw('CAST(source AS UNSIGNED)')->limit(500)->pluck('source'),
        ]);
    }

    public function excel(Request $request)
    {
        $this->authorizeAccess();
        @set_time_limit(300);
        $f = $this->filters($request);
        $t = $this->totals($f);

        $rows = (function () use ($f) {
            $i = 0;
            foreach ($this->query($f)->orderByDesc('call_date')->orderByDesc('id')->cursor() as $r) {
                yield [++$i, $r->call_date, $r->source, $r->destination, $r->call_duration, $r->answered_duration,
                       $r->caller_id, $r->did, $r->disposition, $r->time_zone];
            }
        })();

        return SimpleXlsx::download(
            "call-duration-report-{$f['start_date']}-to-{$f['end_date']}.xlsx",
            "Call Duration - Group Report ({$f['start_date']} {$f['start_time']}:00 to {$f['end_date']} {$f['end_time']}:59"
                . ($f['extension'] !== '' ? ", ext {$f['extension']}" : '') . ") | Inbound {$t['inbound']} (answered {$t['inbound_ans']}) | Outbound {$t['outbound']} (answered {$t['outbound_ans']})",
            ['Sl.No', 'Call Date', 'Source', 'Destination', 'Call Duration', 'Answered Duration', 'CallerID', 'DID', 'Disposition', 'TimeZone'],
            $rows,
            [8, 20, 14, 16, 14, 18, 30, 14, 14, 20]
        );
    }

    public function pdf(Request $request)
    {
        $this->authorizeAccess();
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');
        $f = $this->filters($request);
        $t = $this->totals($f);

        if ($t['calls'] > self::PDF_MAX_ROWS) {
            return back()->with('error', "PDF is limited to " . self::PDF_MAX_ROWS . " calls ({$t['calls']} match). Narrow the date/time or extension filter, or use Download Excel.");
        }

        $html = view('user.callduration-pdf', [
            'f'      => $f,
            'totals' => $t,
            'rows'   => $this->query($f)->orderByDesc('call_date')->orderByDesc('id')->get(),
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        // Built-in PDF font + font cache outside the project: dompdf must not write font .json files into vendor/
        $options->set('defaultFont', 'Helvetica');
        $options->set('fontCache', sys_get_temp_dir());
        $options->set('tempDir', sys_get_temp_dir());
        $pdf = new Dompdf($options);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"call-duration-report-{$f['start_date']}-to-{$f['end_date']}.pdf\"",
        ]);
    }
}
