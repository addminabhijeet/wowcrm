<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MonthlyTarget;
use Carbon\Carbon;

class MonthlyTargetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active users (junior and senior roles)
        $users = User::whereIn('role', ['junior', 'senior'])
                     ->where('is_deleted', 0)
                     ->get();

        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;

        foreach ($users as $user) {
            // Create default targets for current year and next year
            foreach ([$currentYear, $nextYear] as $year) {
                for ($month = 1; $month <= 12; $month++) {
                    MonthlyTarget::firstOrCreate(
                        [
                            'user_id' => $user->id,
                            'year' => $year,
                            'month' => $month
                        ],
                        [
                            'target' => 1000 // Default monthly target
                        ]
                    );
                }
            }
        }

        $this->command->info('Monthly targets seeded successfully for all users!');
    }
}
