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
$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$dbname = $env['DB_DATABASE'] ?? '';
$dsn = sprintf('mysql:host=%s;port=%s', $host, $port);
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected to MySQL on $host:$port\n";
    if ($dbname) {
        echo "Dropping database $dbname if exists...\n";
        $pdo->exec('DROP DATABASE IF EXISTS `'. $dbname . '`');
        echo "Creating database $dbname...\n";
        $pdo->exec('CREATE DATABASE `'. $dbname . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        echo "Database $dbname recreated.\n";
    } else {
        echo "DB_DATABASE not set in .env. Aborting.\n";
        exit(1);
    }
} catch (Exception $e) {
    echo 'MySQL action failed: ' . $e->getMessage() . "\n";
    exit(1);
}
?>
