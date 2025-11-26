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
if (empty($env)) { echo "failed to parse .env\n"; exit(1); }
$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$db = $env['DB_DATABASE'] ?? '';
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $db);
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query('SHOW TABLES');
    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    foreach ($rows as $r) echo $r[0] . "\n";
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage() . "\n";
}
?>
