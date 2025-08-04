<?php
require __DIR__ . '/db_connect.php';
require __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
http_response_code(401);
echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
exit;
}

$result = getPosts($supabase, $_SESSION['user_id']);

if (!$result['success']) {
http_response_code($result['code'] ?? 500);
}

echo json_encode($result);