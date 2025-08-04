<?php
require __DIR__ . '/db_connect.php';
require __DIR__ . '/functions.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$result = loginUser(
    $supabase,
    $input['email'] ?? '',
    $input['password'] ?? ''
);

if (!$result['success']) {
    http_response_code($result['code'] ?? 500);
}

echo json_encode($result);