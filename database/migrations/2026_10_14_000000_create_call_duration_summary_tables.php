<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Precomputed call duration per extension and shift (8:00pm - 5:00am IST), refreshed when a PBX sheet is uploaded.
     * The navbar of a junior reads one row from here instead of calculating from the call records on every page.
     */
    public function up(): void
    {
        if (!Schema::hasTable('call_duration_summaries')) {
            Schema::create('call_duration_summaries', function (Blueprint $table) {
                $table->id();
                $table->date('shift_date');                       // IST date the shift starts (8:00pm)
                $table->string('extension', 10);
                for ($i = 0; $i < 9; $i++) {
                    $table->unsignedInteger("slot{$i}")->default(0);   // seconds in 8-9pm ... 4-5am (IST hours)
                }
                $table->unsignedInteger('total')->default(0);
                $table->timestamps();

                $table->unique(['shift_date', 'extension']);
            });
        }

        if (!Schema::hasTable('call_duration_shifts')) {
            Schema::create('call_duration_shifts', function (Blueprint $table) {
                $table->id();
                $table->date('shift_date')->unique();
                $table->dateTime('covered_until')->nullable();    // IST: how far the uploaded PBX sheets reach inside this shift
                $table->dateTime('refreshed_at');                 // app timezone
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('call_duration_shifts');
        Schema::dropIfExists('call_duration_summaries');
    }
};
