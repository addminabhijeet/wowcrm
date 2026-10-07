<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** One row per line of the uploaded "Export_Callrecords" sheet (Call Duration > Group Report). */
    public function up(): void
    {
        if (Schema::hasTable('call_duration_records')) {
            return;
        }

        Schema::create('call_duration_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sl_no')->nullable();                // "Sl.No" of the export
            $table->dateTime('call_date');                                // "Call Date"
            $table->string('source', 40);                                 // "Source"
            $table->string('destination', 40);                            // "Destination"
            $table->unsignedInteger('call_duration')->default(0);         // "Call Duration" (seconds)
            $table->unsignedInteger('answered_duration')->default(0);     // "Amswered Duration" (seconds)
            $table->string('caller_id', 150)->nullable();                 // "CallerID"
            $table->string('did', 40)->nullable();                        // "DID"
            $table->string('disposition', 30)->nullable();                // "Disposition"
            $table->string('time_zone', 40)->nullable();                  // "TimeZone"
            $table->string('row_hash', 40)->unique();                     // same call re-uploaded in an overlapping export is skipped
            $table->string('source_file')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('call_date');
            $table->index('source');
            $table->index('destination');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_duration_records');
    }
};
