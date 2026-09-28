<?php

// Build static gzip copies without changing the CSS or JavaScript source.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

if (!function_exists('gzencode')) {
    fwrite(STDERR, "The PHP zlib extension is required.\n");
    exit(1);
}

$checkOnly = in_array('--check', $argv, true);
$assetRoot = dirname(__DIR__) . '/assets';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($assetRoot, FilesystemIterator::SKIP_DOTS));
$count = 0;
$originalBytes = 0;
$compressedBytes = 0;
$failures = 0;

foreach ($files as $file) {
    if (!$file->isFile() || $file->isLink() || !in_array($file->getExtension(), ['css', 'js'], true)) {
        continue;
    }

    $path = $file->getPathname();
    $source = file_get_contents($path);
    if ($source === false) {
        fwrite(STDERR, "Cannot read {$path}\n");
        $failures++;
        continue;
    }

    if ($checkOnly) {
        $compressed = is_file($path . '.gz') ? file_get_contents($path . '.gz') : false;
        if ($compressed === false || @gzdecode($compressed) !== $source) {
            fwrite(STDERR, "Missing or stale compressed asset: {$path}\n");
            $failures++;
            continue;
        }
    } else {
        $compressed = gzencode($source, 9);
        if ($compressed === false || gzdecode($compressed) !== $source) {
            fwrite(STDERR, "Compression verification failed: {$path}\n");
            $failures++;
            continue;
        }

        // Publish each complete file atomically so requests never see partial gzip data.
        $temporaryPath = $path . '.gz.tmp';
        if (file_put_contents($temporaryPath, $compressed, LOCK_EX) !== strlen($compressed)
            || !rename($temporaryPath, $path . '.gz')) {
            if (is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
            fwrite(STDERR, "Cannot write compressed asset: {$path}\n");
            $failures++;
            continue;
        }
    }

    $count++;
    $originalBytes += strlen($source);
    $compressedBytes += strlen($compressed);
}

printf("%s %d assets: %d -> %d bytes (%.1f%% smaller).\n",
    $checkOnly ? 'Verified' : 'Compressed', $count, $originalBytes, $compressedBytes,
    $originalBytes > 0 ? (1 - $compressedBytes / $originalBytes) * 100 : 0);
exit($failures > 0 ? 1 : 0);
