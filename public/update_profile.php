<?php
/**
 * API: Update profil pengguna (nama & foto)
 * Metode: POST
 * Field form-data: name (teks), photo (file opsional)
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../api/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Metode tidak diizinkan.']);
    exit;
}

$userId   = 1; // Single-user app
$namaBaru = trim($_POST['name'] ?? '');

if ($namaBaru === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Nama tidak boleh kosong.']);
    exit;
}

$photoPath = null;

// ── Proses upload foto jika ada ──────────────────────────────
if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileMime    = mime_content_type($_FILES['photo']['tmp_name']);

    if (!in_array($fileMime, $allowedMime, true)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, GIF, atau WEBP.']);
        exit;
    }

    $ext        = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $fileName   = 'avatar_' . $userId . '_' . time() . '.' . strtolower($ext);
    $uploadDir  = __DIR__ . '/uploads/avatars/';
    $uploadPath = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal menyimpan foto.']);
        exit;
    }

    $photoPath = 'uploads/avatars/' . $fileName;
}

// ── Update database ──────────────────────────────────────────
try {
    if ($photoPath !== null) {
        // Hapus foto lama jika ada
        $stmtOld = $pdo->prepare('SELECT photo_path FROM users WHERE id = ?');
        $stmtOld->execute([$userId]);
        $oldRow = $stmtOld->fetch();
        if ($oldRow && $oldRow['photo_path']) {
            $oldFile = __DIR__ . '/' . $oldRow['photo_path'];
            if (file_exists($oldFile)) {
                @unlink($oldFile);
            }
        }

        $stmt = $pdo->prepare('UPDATE users SET username = ?, photo_path = ? WHERE id = ?');
        $stmt->execute([$namaBaru, $photoPath, $userId]);
    } else {
        $stmt = $pdo->prepare('UPDATE users SET username = ? WHERE id = ?');
        $stmt->execute([$namaBaru, $userId]);
    }

    // Ambil data terbaru untuk dikembalikan
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
    echo json_encode(['error' => 'Gagal memperbarui profil: ' . $e->getMessage()]);
}
