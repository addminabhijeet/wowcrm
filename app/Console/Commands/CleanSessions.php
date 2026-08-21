<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clean {--force : Force delete all sessions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired session files to fix 419 Page Expired errors';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sessionPath = storage_path('framework/sessions');

        if (!is_dir($sessionPath)) {
            $this->error("Session path does not exist: {$sessionPath}");
            return 1;
        }

        $lifetime = config('session.lifetime', 120) * 60; // Convert to seconds
        $force = $this->option('force');

        try {
            $deleted = 0;
            $total = 0;
            $now = time();

            $files = @scandir($sessionPath);
            if ($files === false) {
                $this->error("Unable to read session directory");
                return 1;
            }

            foreach ($files as $file) {
                if ($file === '.' || $file === '..' || $file === '.gitignore') {
                    continue;
                }

                $filePath = $sessionPath . DIRECTORY_SEPARATOR . $file;

                if (!is_file($filePath)) {
                    continue;
                }

                $total++;

                // Delete if forced OR if file is older than session lifetime
                if ($force || ($now - filemtime($filePath) >= $lifetime)) {
                    if (@unlink($filePath)) {
                        $deleted++;
                    }
                }
            }

            $this->info("✅ Session cleanup completed!");
            $this->line("   Total session files: {$total}");
            $this->line("   Deleted: {$deleted}");
            $this->line("   Remaining: " . ($total - $deleted));

            if ($force) {
                $this->warn("   ⚠️  All sessions were force deleted!");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Error cleaning sessions: " . $e->getMessage());
            return 1;
        }
    }
}
