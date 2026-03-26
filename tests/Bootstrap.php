<?php
// tests/Bootstrap.php
// PHPUnit bootstrap file

// Load Composer autoloader if present
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Mock DB config for tests (no real DB)
if (!defined('TEST_MODE')) {
    define('TEST_MODE', true);
}
?>

