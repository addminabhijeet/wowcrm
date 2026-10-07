<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_alert_emails', function (Blueprint $table) {
            $table->id();
            $table->string('type', 5);          // to | cc
            $table->string('email');
            $table->timestamps();

            $table->unique(['type', 'email']);
        });

        // Import recipients previously saved in the JSON file, if any.
        $file = storage_path('app/login_alert_emails.json');
        if (is_file($file)) {
            $data = json_decode((string) file_get_contents($file), true);
            if (is_array($data)) {
                $lists = array_is_list($data) ? ['to' => $data, 'cc' => []] : $data;
                foreach (['to', 'cc'] as $type) {
                    foreach (array_unique($lists[$type] ?? []) as $email) {
                        DB::table('login_alert_emails')->insertOrIgnore([
                            'type' => $type, 'email' => $email, 'created_at' => now(), 'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('login_alert_emails');
    }
};
