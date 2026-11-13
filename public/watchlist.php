<?php
/**
 * API: Kelola watchlist pengguna
 *
 * GET  watchlist.php              → Ambil semua item watchlist user 1
 * POST watchlist.php action=add  → Tambah film ke watchlist
 * POST watchlist.php action=remove movie_id=X → Hapus film dari watchlist
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db_connect.php';

$userId = 1; // Single-user app
$method = $_SERVER['REQUEST_METHOD'];

if (!$pdo) {
    http_response_code(503);
    echo json_encode(['error' => 'Database tidak tersedia.']);
    exit;
}

// ── GET: Ambil daftar watchlist ──────────────────────────────
if ($method === 'GET') {
    $stmt = $pdo->prepare(
        'SELECT * FROM watchlist WHERE user_id = ? ORDER BY added_at DESC'
    );
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();
    echo json_encode(['items' => $rows]);
    exit;
}

// ── POST: Tambah atau hapus ──────────────────────────────────
if ($method === 'POST') {
    // Baca body JSON atau form-data
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
    } else {
        $body = $_POST;
    }

    $action  = $body['action']   ?? '';
    $movieId = (int)($body['movie_id'] ?? 0);

    // ── Hapus film dari watchlist ────────────────────────────
    if ($action === 'remove') {
        if ($movieId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'movie_id tidak valid.']);
            exit;
        }
        $stmt = $pdo->prepare(
            'DELETE FROM watchlist WHERE user_id = ? AND movie_id = ?'
        );
        $stmt->execute([$userId, $movieId]);
        echo json_encode(['success' => true, 'removed_movie_id' => $movieId]);
        exit;
    }

    // ── Tambah film ke watchlist ─────────────────────────────
    if ($action === 'add') {
        if ($movieId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'movie_id tidak valid.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare(
                'INSERT IGNORE INTO watchlist
                    (user_id, movie_id, title, year, duration, genre, rating, director, actors, synopsis, poster_url)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $userId,
                $movieId,
                $body['title']     ?? '',
                $body['year']      ?? null,
                $body['duration']  ?? null,
                $body['genre']     ?? null,
                $body['rating']    ?? null,
                $body['director']  ?? null,
                $body['actors']    ?? null,
                $body['synopsis']  ?? null,
                $body['poster']    ?? null,
            ]);
            echo json_encode(['success' => true, 'added_movie_id' => $movieId]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Parameter action tidak dikenali.']);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Metode tidak diizinkan.']);
