<?php
/**
 * API: Update profil pengguna (nama & foto)
 * Metode : POST
 * Fields : name (string), photo (file, opsional)
 *
 * Nama file foto selalu disimpan sebagai "profil.{ext}" (jpg/png/gif/webp)
 * sehingga selalu mudah ditemukan dan tidak menumpuk file lama.
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metode tidak diizinkan.']);
    exit;
}

// ── Cek koneksi DB ────────────────────────────────────────────
if (!$pdo) {
    http_response_code(503);
    echo json_encode([
        'error' => 'Koneksi database gagal. Pastikan container MySQL sudah berjalan '
                 . 'dan tabel users sudah dibuat (jalankan: docker compose down -v && docker compose up --build).'
                 . (isset($dbLastError) ? ' Detail: ' . $dbLastError : ''),
    ]);
    exit;
}

$userId   = 1;
$namaBaru = trim($_POST['name'] ?? '');

if ($namaBaru === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Nama tidak boleh kosong.']);
    exit;
}

// ── Peta ekstensi yang diizinkan ─────────────────────────────
$mimeToExt = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];

$photoPath = null;

// ── Proses upload foto jika ada ──────────────────────────────
if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $fileMime = mime_content_type($_FILES['photo']['tmp_name']);

    if (!array_key_exists($fileMime, $mimeToExt)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP.']);
        exit;
    }

    // Nama file selalu "profil.{ext}" — file lama otomatis tertimpa
    $ext        = $mimeToExt[$fileMime];
    $fileName   = 'profil.' . $ext;                     // ← selalu "profil.jpg" / "profil.png" / dll
    $uploadDir  = __DIR__ . '/uploads/avatars/';
    $uploadPath = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan foto ke server.']);
        exit;
    }

    $photoPath = 'uploads/avatars/' . $fileName;
}

// ── Update database ──────────────────────────────────────────
try {
    // Pastikan tabel users ada; jika belum, buat otomatis
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
            username   VARCHAR(100) NOT NULL DEFAULT 'Briang',
            photo_path VARCHAR(500) NULL,
            created_at TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Pastikan baris user 1 ada
    $pdo->exec("INSERT IGNORE INTO users (id, username) VALUES (1, 'Briang')");

    if ($photoPath !== null) {
        $stmt = $pdo->prepare('UPDATE users SET username = ?, photo_path = ? WHERE id = ?');
        $stmt->execute([$namaBaru, $photoPath, $userId]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET username = ? WHERE id = ?');
        $stmt->execute([$namaBaru, $userId]);
    }

    // Ambil data terbaru
    $stmtGet = $pdo->prepare('SELECT username, photo_path FROM users WHERE id = ?');
    $stmtGet->execute([$userId]);
    $user = $stmtGet->fetch();

    echo json_encode([
        'success'    => true,
        'username'   => $user['username'],
        'photo_path' => $user['photo_path'],
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal update profil: ' . $e->getMessage()]);
}
