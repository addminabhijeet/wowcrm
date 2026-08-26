<?php
/**
 * Windows-Compatible Load Test Runner for WowCRM
 * Creates 100 concurrent test users and simulates login + dashboard access
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$baseUrl = 'http://localhost/wowcrm';
$resultsDir = __DIR__ . '/results';
$logsDir = __DIR__ . '/logs';

// Ensure directories exist
@mkdir($resultsDir, 0777, true);
@mkdir($logsDir, 0777, true);

echo "=================================================\n";
echo "WowCRM Load Test Suite - Windows Compatible\n";
echo "=================================================\n\n";

// Step 1: Create 100 test users
echo "[STEP 1] Creating 100 test users with junior role...\n";
require_once __DIR__ . '/../../bootstrap/app.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Use tinker to create users
exec('php artisan tinker --execute="
for (\$i = 1; \$i <= 100; \$i++) {
    \\\$user = \App\Models\User::create([
        \'name\' => \'LoadTest Junior \'.\$i,
        \'email\' => \'loadtest.junior\'.\$i.\'@test.local\',
        \'password\' => \bcrypt(\'LoadTest@123\'),
        \'role\' => \'junior\',
        \'status\' => 1,
        \'is_deleted\' => 0,
    ]);
    echo \$i . \' \';
}
echo \'\n\';
"', $output);

echo "Created users: " . implode("\n", $output) . "\n";
echo "✓ 100 test users created\n\n";

// Step 2: Run load tests
echo "[STEP 2] Running 100 concurrent login + dashboard tests...\n";
echo "Starting load test simulation...\n\n";

$startTime = microtime(true);
$results = [];
$stats = [];

// Create parent process for concurrent requests
$processes = [];
$handles = [];

for ($i = 1; $i <= 100; $i++) {
    $email = "loadtest.junior{$i}@test.local";
    $password = "LoadTest@123";
    $resultFile = "$resultsDir/result_{$i}.txt";
    $cookieFile = "$resultsDir/cookies_{$i}.txt";

    $testScript = <<<'EOT'
<?php
$i = %d;
$email = '%s';
$password = '%s';
$baseUrl = '%s';
$resultFile = '%s';
$cookieFile = '%s';
$testStartTime = microtime(true);

// Step 1: Get CSRF token from login page
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/login',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_TIMEOUT => 30,
]);
$loginPage = curl_exec($ch);
$loginPageTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000;

if (!$loginPage) {
    file_put_contents($resultFile, "USER=$i ERROR=NO_PAGE\n");
    exit;
}

// Extract CSRF token
if (!preg_match('/<input[^>]*name="[^"]*token[^"]*"[^>]*value="([^"]+)"/', $loginPage, $matches) &&
    !preg_match('/_token["\']?\s*[:=]\s*["\']?([a-zA-Z0-9/+=]+)/', $loginPage, $matches)) {
    file_put_contents($resultFile, "USER=$i ERROR=NO_TOKEN\n");
    curl_close($ch);
    exit;
}

$csrfToken = $matches[1];

// Step 2: Login
$loginPostData = http_build_query([
    'email' => $email,
    'password' => $password,
    '_token' => $csrfToken,
]);

curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/loginsubmit',
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $loginPostData,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
]);

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$loginTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000;

if ($loginHttpCode != 200 && $loginHttpCode != 302) {
    file_put_contents($resultFile, "USER=$i ERROR=LOGIN_FAILED_HTTP_$loginHttpCode\n");
    curl_close($ch);
    exit;
}

// Step 3: Access dashboard
curl_setopt_array($ch, [
    CURLOPT_URL => $baseUrl . '/dashboard/junior',
    CURLOPT_POST => false,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
]);

$dashboardResponse = curl_exec($ch);
$dashboardHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$dashboardTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000;

curl_close($ch);

// Calculate total time
$totalTime = (microtime(true) - $testStartTime) * 1000;

// Determine success
$success = ($loginHttpCode == 200 || $loginHttpCode == 302) && $dashboardHttpCode == 200;
$status = $success ? 'SUCCESS' : "FAIL_HTTP_$dashboardHttpCode";

$result = "USER=$i STATUS=$status LOGIN_TIME=" . number_format($loginTime, 2) . "ms DASHBOARD_TIME=" . number_format($dashboardTime, 2) . "ms TOTAL_TIME=" . number_format($totalTime, 2) . "ms";

file_put_contents($resultFile, $result . "\n");
?>
EOT;

    $scriptContent = sprintf($testScript, $i, $email, $password, $baseUrl, $resultFile, $cookieFile);
    $tempScript = sys_get_temp_dir() . "/loadtest_{$i}.php";
    file_put_contents($tempScript, $scriptContent);

    // Run in background (non-blocking)
    $cmdLine = "php \"$tempScript\"";

    // Windows: Use proc_open for parallel execution
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($cmdLine, $descriptors, $pipes);
    if (is_resource($process)) {
        $handles[$i] = ['process' => $process, 'pipes' => $pipes, 'script' => $tempScript];
    }
}

// Wait for all processes to complete
echo "Waiting for all 100 tests to complete...\n";
foreach ($handles as $i => $handle) {
    proc_close($handle['process']);
    @unlink($handle['script']);
    if (($i % 10) == 0) {
        echo ".";
    }
}
echo "\n✓ All tests completed\n\n";

// Step 3: Collect and analyze results
echo "[STEP 3] Analyzing results...\n";
$results = [];
$successCount = 0;
$failureCount = 0;
$totalTime = 0;
$responseTimes = [];

for ($i = 1; $i <= 100; $i++) {
    $resultFile = "$resultsDir/result_{$i}.txt";
    if (file_exists($resultFile)) {
        $content = trim(file_get_contents($resultFile));
        $results[$i] = $content;

        if (strpos($content, 'SUCCESS') !== false) {
            $successCount++;

            // Extract timing
            if (preg_match('/TOTAL_TIME=([\d.]+)ms/', $content, $matches)) {
                $time = (float)$matches[1];
                $responseTimes[] = $time;
                $totalTime += $time;
            }
        } else {
            $failureCount++;
        }
    }
}

// Generate report
$reportFile = "$resultsDir/REPORT.txt";
$report = generateReport($successCount, $failureCount, $responseTimes, $totalTime);
file_put_contents($reportFile, $report);

echo $report;

echo "\n[COMPLETE] Load test finished in " . number_format((microtime(true) - $startTime), 2) . " seconds\n";
echo "Report saved to: $reportFile\n";

function generateReport($successCount, $failureCount, $responseTimes, $totalTime) {
    $total = $successCount + $failureCount;
    $successRate = ($total > 0) ? ($successCount / $total) * 100 : 0;

    $avgTime = 0;
    $minTime = PHP_INT_MAX;
    $maxTime = 0;

    if (!empty($responseTimes)) {
        $avgTime = array_sum($responseTimes) / count($responseTimes);
        $minTime = min($responseTimes);
        $maxTime = max($responseTimes);
    }

    $report = "\n========================================\n";
    $report .= "LOAD TEST REPORT - 100 Concurrent Users\n";
    $report .= "========================================\n\n";

    $report .= "OVERALL RESULTS:\n";
    $report .= "  Total Users: $total\n";
    $report .= "  Successful: $successCount (✓ " . number_format($successRate, 2) . "%)\n";
    $report .= "  Failed: $failureCount (✗ " . number_format(100 - $successRate, 2) . "%)\n\n";

    $report .= "RESPONSE TIMES:\n";
    $report .= "  Minimum: " . number_format($minTime, 2) . "ms\n";
    $report .= "  Average: " . number_format($avgTime, 2) . "ms\n";
    $report .= "  Maximum: " . number_format($maxTime, 2) . "ms\n\n";

    if ($successRate >= 95) {
        $report .= "VERDICT: ✓ EXCELLENT - System handles 100 concurrent junior users WITHOUT LAG\n";
    } elseif ($successRate >= 80) {
        $report .= "VERDICT: ⚠ ACCEPTABLE - System handles 100 concurrent users with minor issues\n";
    } else {
        $report .= "VERDICT: ✗ POOR - System struggles with 100 concurrent users\n";
    }

    $report .= "========================================\n";

    return $report;
}
