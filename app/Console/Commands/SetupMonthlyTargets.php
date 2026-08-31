<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\MonthlyTarget;
use Carbon\Carbon;

class SetupMonthlyTargets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly-targets:setup';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Setup monthly targets table and populate with default 1000 targets for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up Monthly Targets...');

        // Run migration
        $this->info('Running migration...');
        Artisan::call('migrate', ['--path' => 'database/migrations/2026_08_31_create_monthly_targets_table.php']);
        $this->info('✓ Migration completed');

        // Populate with defaults
        $this->info('Populating monthly targets with defaults (1000 per month)...');

        $users = User::whereIn('role', ['junior', 'senior'])
                     ->where('is_deleted', 0)
                     ->get();

        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        $totalInserted = 0;

        foreach ($users as $user) {
            foreach ([$currentYear, $nextYear] as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    MonthlyTarget::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'year' => $year,
                            'month' => $month
                        ],
                        [
                            'target' => 1000
                        ]
                    );
                    $totalInserted++;
                }
            }
            $this->line("✓ Created targets for user: {$user->name}");
        }

        $this->info("✓ Successfully created {$totalInserted} monthly target entries");
        $this->info('Monthly Targets setup completed successfully!');
        $this->info('Access monthly targets at: /dashboard/monthly-targets');
    }
}
