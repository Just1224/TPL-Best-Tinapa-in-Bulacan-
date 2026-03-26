<?php
// tests/ConfigTest.php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    public function testConfigLoadsWithoutFatalErrors(): void
    {
        // Test includes/config.php loads (env vars mocked in Bootstrap)
        ob_start();
        require_once __DIR__ . '/../includes/config.php';
        $output = ob_get_clean();

        $this->assertEmpty($output, 'Config should load without output/errors');
        $this->assertTrue(isset($pdo), 'PDO connection object should be defined');
    }

    public function testEnvFallbacks(): void
    {
        // Test default env fallbacks
        putenv('DB_HOST=test');
        require_once __DIR__ . '/../includes/config.php';
        $this->assertEquals('test', $host, 'Env var should override default');
    }
}
?>

