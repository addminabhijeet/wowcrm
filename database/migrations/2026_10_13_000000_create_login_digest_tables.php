<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Grouped login / logout mails: junior logins and logouts are stored here and mailed as ONE mail per hour slot (IST)
     * that had at least one event, instead of one mail per event.
     */
    public function up(): void
    {
        if (!Schema::hasTable('login_digest_events')) {
            Schema::create('login_digest_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('event', 10);                   // login | logout
                $table->string('ip', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->dateTime('happened_at');               // app timezone, like login_mail_logs.logged_at
                $table->dateTime('slot_start');                // start of the IST hour the event belongs to
                $table->dateTime('digested_at')->nullable();   // set when the grouped mail containing it was sent
                $table->timestamps();

                $table->index(['slot_start', 'digested_at']);
                $table->index('user_id');
            });
        }

        if (!Schema::hasTable('login_digest_runs')) {
            Schema::create('login_digest_runs', function (Blueprint $table) {
                $table->id();
                $table->dateTime('slot_start')->unique();      // IST hour of the grouped mail
                $table->unsignedInteger('events_count')->default(0);
                $table->string('status', 10);                  // sent | failed
                $table->unsignedInteger('attempts')->default(0);
                $table->dateTime('last_attempt_at')->nullable();
                $table->dateTime('sent_at')->nullable();
                $table->text('message')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('login_digest_settings')) {
            Schema::create('login_digest_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('grouped')->default(true);     // true = one mail per slot (juniors only), false = one mail per event as before
                $table->timestamps();
            });
            DB::table('login_digest_settings')->insert(['grouped' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('login_digest_settings');
        Schema::dropIfExists('login_digest_runs');
        Schema::dropIfExists('login_digest_events');
    }
};
