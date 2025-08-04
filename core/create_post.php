<?php
require __DIR__ . '/db_connect.php';
require __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
http_response_code(401);
echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$result = createPost($supabase, $_SESSION['user_id'], $input['content'] ?? '');

if (!$result['success']) {
http_response_code($result['code'] ?? 500);
}

echo json_encode($result);