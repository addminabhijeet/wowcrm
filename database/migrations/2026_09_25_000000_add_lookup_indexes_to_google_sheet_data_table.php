<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Indexes for the columns the junior list pages look candidates up by
     * (email, phone, created_by prefix + updated_at), so those lookups stop
     * scanning the whole google_sheet_data table. Indexes only — no data or
     * column changes.
     */
    public function up(): void
    {
        $this->addIndex('gsd_email_address_index', 'Email_Address');
        $this->addIndex('gsd_phone_number_index', 'Phone_Number');

        // Full-length created_by lets the Target Achieved counts run from the index alone;
        // the 191-char prefix is the fallback for servers limited to 767-byte index keys.
        try {
            $this->addIndex('gsd_created_by_index', 'created_by, updated_at');
        } catch (QueryException $e) {
            $this->addIndex('gsd_created_by_index', 'created_by(191), updated_at');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['gsd_email_address_index', 'gsd_phone_number_index', 'gsd_created_by_index'] as $name) {
            if (Schema::hasIndex('google_sheet_data', $name)) {
                DB::statement("ALTER TABLE google_sheet_data DROP INDEX {$name}");
            }
        }
    }

    private function addIndex(string $name, string $columns): void
    {
        if (!Schema::hasIndex('google_sheet_data', $name)) {
            DB::statement("ALTER TABLE google_sheet_data ADD INDEX {$name} ({$columns})");
        }
    }
};
