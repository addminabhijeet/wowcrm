#!/bin/bash
PROJ_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
RESULTS_DIR="$PROJ_DIR/storage/loadtest/results"
LOGS_DIR="$PROJ_DIR/storage/loadtest/logs"

# Clear previous results
rm -f "$RESULTS_DIR"/*.txt "$RESULTS_DIR"/*.csv
rm -f "$LOGS_DIR"/*.log

echo "╔════════════════════════════════════════════════════════════════════════════╗"
echo "║         WowCRM Load Test: 100 Concurrent Junior Users                      ║"
echo "║         Project: $PROJ_DIR"
echo "╚════════════════════════════════════════════════════════════════════════════╝"
echo ""

# Step 1: Create 100 test users
echo "[1/5] Creating 100 test users..."
cd "$PROJ_DIR"
php8.3 artisan tinker --execute="
for (\$i = 1; \$i <= 100; \$i++) {
    \App\Models\User::create([
        'name' => 'LoadTest Junior '.\$i,
        'email' => 'loadtest.junior'.\$i.'@test.local',
        'password' => 'LoadTest@123',
        'role' => 'junior',
        'status' => 1,
        'is_deleted' => 0,
    ]);
}
echo 'Created 100 test users';
" 2>&1 | grep -i "created"

if [ $? -ne 0 ]; then
    echo "❌ Failed to create test users"
    exit 1
fi
echo "✓ Test users created"
echo ""

# Step 2: Start system monitoring in background
echo "[2/5] Starting system monitoring..."
nohup "$PROJ_DIR/storage/loadtest/monitor.sh" > "$LOGS_DIR/monitor_output.log" 2>&1 &
MONITOR_PID=$!
echo $MONITOR_PID > "$LOGS_DIR/monitor.pid"
echo "✓ Monitoring started (PID: $MONITOR_PID)"
sleep 2
echo ""

# Step 3: Run 100 concurrent load tests
echo "[3/5] Running 100 concurrent user logins..."
TEST_START=$(date +%s)

seq 1 100 | xargs -n1 -P100 -I{} bash "$PROJ_DIR/storage/loadtest/loadtest_run.sh" {} 2>&1 | while read line; do
    if [[ $line == USER=* ]]; then
        echo "$line" >> "$RESULTS_DIR/combined_results.txt"
    fi
done

TEST_END=$(date +%s)
TEST_DURATION=$(( $TEST_END - $TEST_START ))

echo "✓ Load test completed in $TEST_DURATION seconds"
echo ""

# Step 4: Stop monitoring
echo "[4/5] Stopping system monitoring..."
sleep 3
kill $(cat "$LOGS_DIR/monitor.pid") 2>/dev/null
sleep 1
echo "✓ Monitoring stopped"
echo ""

# Step 5: Generate report
echo "[5/5] Generating report..."
bash "$PROJ_DIR/storage/loadtest/generate_report.sh"

echo ""
echo "✓ All results saved to: $RESULTS_DIR"
echo "✓ All logs saved to: $LOGS_DIR"
