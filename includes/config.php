<?php
$driver = getenv('DB_DRIVER') ?: 'pgsql'; // use 'pgsql' for PostgreSQL or 'mysql' for MySQL
$host   = getenv('DB_HOST') ?: '127.0.0.1';
$user   = getenv('DB_USER') ?: 'root';
$pass   = getenv('DB_PASS') ?: '';
$db     = getenv('DB_NAME') ?: 'tinapa_cms';
$port   = getenv('DB_PORT') ?: ($driver === 'pgsql' ? 5432 : 3306);

try {
    if ($driver === 'pgsql') {
        $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    } else {
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    }

    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function db_query(string $sql, array $bind = []): PDOStatement {
    global $conn;
    $stmt = $conn->prepare($sql);
    $stmt->execute($bind);
    return $stmt;
}

function db_fetch_assoc(PDOStatement $stmt): array {
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function db_num_rows(PDOStatement $stmt): int {
    return $stmt->rowCount();
}

function db_insert_id(): string {
    global $conn;
    return $conn->lastInsertId();
}
?>