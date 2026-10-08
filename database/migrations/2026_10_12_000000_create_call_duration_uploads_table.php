<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per PBX sheet upload with the time range (IST) its calls cover. The report mails use these ranges to
     * know whether the call data of a slot has been uploaded yet.
     */
    public function up(): void
    {
        if (Schema::hasTable('call_duration_uploads')) {
            return;
        }

        Schema::create('call_duration_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->nullable();
            $table->unsignedInteger('rows_read')->default(0);
            $table->unsignedInteger('rows_added')->default(0);
            $table->dateTime('covers_from');                 // first call of the file, IST
            $table->dateTime('covers_to');                   // last call of the file, IST
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('covers_from');
            $table->index('covers_to');
        });

        // Calls that were uploaded before this table existed: one range per upload (all rows of an upload share created_at),
        // not per file name, so the same file name used on different days cannot cover the days in between (PBX time -> IST)
        if (Schema::hasTable('call_duration_records')) {
            $groups = DB::table('call_duration_records')
                ->selectRaw('source_file, created_at as uploaded_at, COUNT(*) as total, MIN(call_date) as first_call, MAX(call_date) as last_call, MAX(uploaded_by) as uploaded_by')
                ->groupBy('source_file', 'created_at')->get();

            foreach ($groups as $g) {
                DB::table('call_duration_uploads')->insert([
                    'file_name'   => $g->source_file ? $g->source_file . ' (before range tracking)' : 'earlier uploads',
                    'rows_read'   => (int) $g->total,
                    'rows_added'  => (int) $g->total,
                    'covers_from' => Carbon::parse($g->first_call, 'America/New_York')->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'covers_to'   => Carbon::parse($g->last_call, 'America/New_York')->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                    'uploaded_by' => $g->uploaded_by,
                    'created_at'  => $g->uploaded_at,
                    'updated_at'  => $g->uploaded_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('call_duration_uploads');
    }
};
