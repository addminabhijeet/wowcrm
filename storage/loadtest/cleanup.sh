#!/bin/bash
PROJ_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

echo "Cleaning up test data..."
cd "$PROJ_DIR"

# Delete 100 test users and their data
php8.3 artisan tinker --execute="
\$emails = collect(range(1,100))->map(fn(\$i) => 'loadtest.junior'.\$i.'@test.local');
\$ids = \App\Models\User::whereIn('email', \$emails)->pluck('id');

if (\$ids->count() > 0) {
    \App\Models\UserTimerPause::whereIn('user_id', \$ids)->delete();
    \App\Models\Logins::whereIn('user_id', \$ids)->delete();
    \App\Models\User::whereIn('id', \$ids)->delete();
    echo 'Cleaned up '.count(\$ids).' test users';
} else {
    echo 'No test users found to cleanup';
}
"

echo ""
echo "✓ Cleanup completed"
