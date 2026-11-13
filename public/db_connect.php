<?php
/**
 * db_connect.php (public/)
 * Koneksi database dengan retry otomatis (maksimal 3 percobaan).
 */

$host     = getenv('DB_HOST')     ?: 'db';
$dbname   = getenv('DB_NAME')     ?: 'cinematch';
$username = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: 'root';
$dsn      = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

$pdo         = null;
$dbLastError = '';
$maxRetries  = 3;

for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 5,
        ]);
        $dbLastError = '';
        break; // koneksi berhasil
    } catch (PDOException $e) {
        $dbLastError = $e->getMessage();
        $pdo = null;
        if ($attempt < $maxRetries) {
            sleep(1); // tunggu 1 detik sebelum retry
        }
    }
}
