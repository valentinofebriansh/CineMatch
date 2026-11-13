<?php
header('Content-Type: application/json; charset=utf-8');

$movieId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($movieId > 0) {
    $url = "http://backend:8000/movies/" . $movieId;
} elseif ($query !== '') {
    $url = "http://backend:8000/search?q=" . urlencode($query) . "&limit=" . $limit;
} else {
    $url = "http://backend:8000/movies?page=" . $page . "&limit=" . $limit;
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($code ?: 200);
echo $res ?: json_encode(['items' => []]);
