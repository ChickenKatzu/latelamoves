<?php
// public/health.php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    // Test database connection
    $config = require __DIR__.'/../config/database.php';

    echo "Environment Variables:\n";
    echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set') . "\n";
    echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? 'not set') . "\n";
    echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'not set') . "\n";

    echo "\nPHP Version: " . phpversion() . "\n";
    echo "Laravel Version: " . app()->version() . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
