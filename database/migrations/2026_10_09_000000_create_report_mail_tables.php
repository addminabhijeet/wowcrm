<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Login/logout mail send status (replaces the daily .jsonl files)
        Schema::create('login_mail_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('event', 10)->default('login');   // login | logout
            $table->string('status', 10);                    // sent | failed | skipped
            $table->text('message')->nullable();
            $table->dateTime('logged_at');
            $table->timestamps();

            $table->index(['logged_at', 'user_id']);
        });

        // Call (C&M) report mail: who receives it
        Schema::create('call_report_mail_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('type', 5);                       // to | cc
            $table->string('email');
            $table->timestamps();

            $table->unique(['type', 'email']);
        });

        // Call (C&M) report mail: which Kolkata clock hours (0-23) it is sent
        Schema::create('call_report_mail_hours', function (Blueprint $table) {
            $table->unsignedTinyInteger('hour')->primary();
            $table->timestamps();
        });

        // Call (C&M) report mail: send history
        Schema::create('call_report_mail_logs', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');                     // Kolkata date of the slot
            $table->unsignedTinyInteger('report_hour');      // Kolkata hour of the slot
            $table->string('trigger', 10)->default('schedule'); // schedule | manual
            $table->string('status', 10);                    // sent | failed | skipped
            $table->text('recipients')->nullable();
            $table->text('message')->nullable();
            $table->dateTime('sent_at');
            $table->timestamps();

            $table->index(['report_date', 'report_hour']);
            $table->index('sent_at');
        });

        // One-time import of status lines previously kept in storage/app/login_mail_log/*.jsonl
        $dir = storage_path('app/login_mail_log');
        if (is_dir($dir)) {
            foreach (glob($dir . '/*.jsonl') ?: [] as $file) {
                foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                    $r = json_decode($line, true);
                    if (!is_array($r) || empty($r['user_id']) || empty($r['time'])) {
                        continue;
                    }
                    DB::table('login_mail_logs')->insert([
                        'user_id'    => $r['user_id'],
                        'event'      => $r['event'] ?? 'login',
                        'status'     => $r['status'] ?? 'sent',
                        'message'    => $r['message'] ?? null,
                        'logged_at'  => $r['time'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('call_report_mail_logs');
        Schema::dropIfExists('call_report_mail_hours');
        Schema::dropIfExists('call_report_mail_recipients');
        Schema::dropIfExists('login_mail_logs');
    }
};
