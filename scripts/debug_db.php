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
print_r([ 'DB_HOST'=>$env['DB_HOST']??null, 'DB_PORT'=>$env['DB_PORT']??null, 'DB_DATABASE'=>$env['DB_DATABASE']??null, 'DB_USERNAME'=>$env['DB_USERNAME']??null, 'DB_PASSWORD'=>$env['DB_PASSWORD']??null ]);
$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$db = $env['DB_DATABASE'] ?? '';
$dsnNS = sprintf('mysql:host=%s;port=%s', $host, $port);
$dsnDB = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $db);
try {
    $pdo = new PDO($dsnNS, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    echo "Connected to server OK\n";
    $stmt = $pdo->query('SHOW DATABASES');
    foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $r) echo "DB: {$r[0]}\n";
    if ($db) {
        $pdo2 = new PDO($dsnDB, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        echo "Connected to DB $db OK\n";
        $stmt2 = $pdo2->query('SHOW TABLES');
        foreach ($stmt2->fetchAll(PDO::FETCH_NUM) as $t) echo "TABLE: {$t[0]}\n";
    }
} catch (Exception $e) {
    echo 'error: ' . $e->getMessage() . "\n";
}
?>
