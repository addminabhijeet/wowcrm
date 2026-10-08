<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** PBX extension -> recruiter, used by Call Duration > Group Report. */
    public function up(): void
    {
        if (Schema::hasTable('call_duration_extensions')) {
            return;
        }

        Schema::create('call_duration_extensions', function (Blueprint $table) {
            $table->id();
            $table->string('extension', 10)->unique();
            $table->unsignedBigInteger('user_id')->nullable();   // users.id (junior or senior); null = not assigned
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_duration_extensions');
    }
};
