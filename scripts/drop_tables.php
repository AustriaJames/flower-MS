<?php
$env = [];
if (file_exists('.env')) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^([^=]+)=(.*)$/', $line, $m)) {
            $env[$m[1]] = trim($m[2], "\"' ");
        }
    }
}
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $env['DB_HOST'] ?? '127.0.0.1', $env['DB_PORT'] ?? '3306', $env['DB_DATABASE'] ?? '');
try {
    $pdo = new PDO($dsn, $env['DB_USERNAME'] ?? 'root', $env['DB_PASSWORD'] ?? '');
    foreach (['migrations','cache','sessions'] as $t) {
        try {
            $pdo->exec('DROP TABLE IF EXISTS `'. $t . '`');
            echo "dropped $t\n";
        } catch (Exception $e) {
            echo "drop $t failed: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo 'pdo connection failed: ' . $e->getMessage() . "\n";
    exit(1);
}
?>
