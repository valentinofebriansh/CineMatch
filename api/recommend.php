<?php
header('Content-Type: application/json; charset=utf-8');

// Mendukung request POST JSON dari chat.php maupun GET q untuk pengujian langsung
$rawInput = file_get_contents('php://input');
$jsonInput = json_decode($rawInput, true);

$prompt = '';
if (is_array($jsonInput) && isset($jsonInput['prompt'])) {
    $prompt = trim($jsonInput['prompt']);
} elseif (isset($_GET['q'])) {
    $prompt = trim($_GET['q']);
} elseif (isset($_POST['prompt'])) {
    $prompt = trim($_POST['prompt']);
}

if ($prompt === '') {
    echo json_encode([
        'status' => 'error',
        'reply' => 'Silakan ketik film atau suasana yang kamu cari.',
        'movie' => null
    ]);
    exit;
}

// Fungsi pemetaan poster tematik berdasarkan genre
function getGenrePoster($genre) {
    $posters = [
        'Action' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=600&q=85',
        'Adventure' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=85',
        'Animation' => 'https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?auto=format&fit=crop&w=600&q=85',
        'Biography' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=600&q=85',
        'Comedy' => 'https://images.unsplash.com/photo-1514306191717-452ec28c7814?auto=format&fit=crop&w=600&q=85',
        'Crime' => 'https://images.unsplash.com/photo-1453873531674-2151bcd01707?auto=format&fit=crop&w=600&q=85',
        'Documentary' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=600&q=85',
        'Drama' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=600&q=85',
        'Family' => 'https://images.unsplash.com/photo-1476900543704-4312b78632f8?auto=format&fit=crop&w=600&q=85',
        'Fantasy' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=600&q=85',
        'History' => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?auto=format&fit=crop&w=600&q=85',
        'Horror' => 'https://images.unsplash.com/photo-1509281373149-e957c6296406?auto=format&fit=crop&w=600&q=85',
        'Music' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=600&q=85',
        'Mystery' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=600&q=85',
        'Romance' => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?auto=format&fit=crop&w=600&q=85',
        'Sci-Fi' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=600&q=85',
        'Sport' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=600&q=85',
        'Thriller' => 'https://images.unsplash.com/photo-1484589065579-248aad0d8b13?auto=format&fit=crop&w=600&q=85',
        'War' => 'https://images.unsplash.com/photo-1543083477-4f785aeafaa9?auto=format&fit=crop&w=600&q=85',
        'Western' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=85',
    ];
    return $posters[$genre] ?? 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=600&q=85';
}

// Menghubungi backend FastAPI smart-recommend
$backendUrl = 'http://backend:8000/smart-recommend?q=' . urlencode($prompt);

$ch = curl_init($backendUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 35);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    echo json_encode([
        'status' => 'error',
        'reply' => 'Maaf, sistem rekomendasi AI sedang memproses permintaan lain atau tidak merespons. Silakan coba sesaat lagi.',
        'movie' => null
    ]);
    exit;
}

$data = json_decode($response, true);

if (!$data || !is_array($data)) {
    echo json_encode([
        'status' => 'error',
        'reply' => 'Format balasan dari server AI tidak valid.',
        'movie' => null
    ]);
    exit;
}

// Bila AI tidak memahami input
if (empty($data['understood']) || empty($data['items'])) {
    $reply = !empty($data['message']) 
        ? $data['message'] 
        : 'Halo! Aku belum menemukan film yang pas untuk permintaan itu. Coba sebutkan genre kesukaanmu (seperti komedi, horor, petualangan), era tahun, atau suasana cerita yang kamu inginkan!';
    
    echo json_encode([
        'status' => 'success',
        'reply' => $reply,
        'movie' => null,
        'parsed' => $data['parsed'] ?? null
    ]);
    exit;
}

// Ambil film teratas
$topMovie = $data['items'][0];
$movieId = $topMovie['id'];

// Ambil detail lengkap film jika memungkinkan untuk sinopsis yang lebih kaya
$detailUrl = 'http://backend:8000/movies/' . intval($movieId);
$chDetail = curl_init($detailUrl);
curl_setopt($chDetail, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chDetail, CURLOPT_TIMEOUT, 5);
$detailResponse = curl_exec($chDetail);
curl_close($chDetail);

$detail = json_decode($detailResponse, true);
if (!$detail || !is_array($detail)) {
    $detail = $topMovie;
}

// Susun sinopsis informatif dari metadata film
$synopsisParts = [];
if (!empty($detail['plot_keywords'])) {
    $keywords = str_replace('|', ', ', $detail['plot_keywords']);
    $synopsisParts[] = "Tema & kata kunci cerita: {$keywords}.";
}
if (!empty($detail['director_name'])) {
    $synopsisParts[] = "Disutradarai oleh {$detail['director_name']}.";
}
$actors = array_filter([$detail['actor_1_name'] ?? null, $detail['actor_2_name'] ?? null, $detail['actor_3_name'] ?? null]);
if (!empty($actors)) {
    $synopsisParts[] = "Pemeran utama: " . implode(', ', $actors) . ".";
}
if (!empty($detail['duration'])) {
    $synopsisParts[] = "Durasi: {$detail['duration']} menit.";
}

$synopsis = !empty($synopsisParts) 
    ? implode(' ', $synopsisParts) 
    : "Film rekomendasi terpilih dengan ulasan penonton tinggi di IMDb.";

$primaryGenre = $detail['primary_genre'] ?? 'Drama';
$genres = !empty($detail['genres']) ? str_replace('|', ', ', $detail['genres']) : $primaryGenre;
$posterUrl = getGenrePoster($primaryGenre);

// Susun teks balasan chatbot
$replyText = "Ini dia rekomendasi film terbaik yang cocok untukmu: **{$detail['title']}**!";
if (!empty($data['parsed']['genre']) || !empty($data['parsed']['start_year']) || !empty($data['parsed']['keyword'])) {
    $filterDesc = [];
    if (!empty($data['parsed']['genre'])) $filterDesc[] = "genre " . $data['parsed']['genre'];
    if (!empty($data['parsed']['start_year']) && !empty($data['parsed']['end_year'])) {
        $filterDesc[] = "tahun {$data['parsed']['start_year']}-{$data['parsed']['end_year']}";
    } elseif (!empty($data['parsed']['start_year'])) {
        $filterDesc[] = "sejak {$data['parsed']['start_year']}";
    }
    if (!empty($data['parsed']['keyword'])) $filterDesc[] = "suasana '{$data['parsed']['keyword']}'";
    $replyText .= " (Sesuai dengan pencarian: " . implode(', ', $filterDesc) . ").";
}

// Tambahkan rekomendasi alternatif jika ada
if (count($data['items']) > 1) {
    $alternatives = [];
    for ($i = 1; $i < min(4, count($data['items'])); $i++) {
        $alt = $data['items'][$i];
        $altYear = !empty($alt['release_year']) ? " ({$alt['release_year']})" : "";
        $alternatives[] = "{$alt['title']}{$altYear}";
    }
    if (!empty($alternatives)) {
        $replyText .= "\n\nPilihan alternatif menarik lainnya: " . implode(', ', $alternatives) . ".";
    }
}

if (!empty($data['note'])) {
    $replyText .= "\n\nCatatan: " . $data['note'];
}

echo json_encode([
    'status' => 'success',
    'reply' => $replyText,
    'movie' => [
        'id' => $detail['id'],
        'title' => $detail['title'],
        'year' => (string)($detail['release_year'] ?? '-'),
        'genre' => $genres,
        'rating' => (string)($detail['imdb_score'] ?? '0.0'),
        'synopsis' => $synopsis,
        'poster' => $posterUrl,
    ],
    'total_found' => $data['total'] ?? 0,
    'mode' => $data['mode'] ?? 'llm',
]);
