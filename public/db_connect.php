<?php
/**
 * db_connect.php (public/)
 * Koneksi database untuk file PHP yang berada di folder public/.
 */

$host     = getenv('DB_HOST')     ?: 'db';
$dbname   = getenv('DB_NAME')     ?: 'cinematch';
$username = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'root';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $exception) {
    // Jangan tampilkan error DB ke UI — fallback ke mode tanpa DB
    $pdo = null;
}
