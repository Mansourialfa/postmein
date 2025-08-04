<?php
require __DIR__ . '/db_connect.php';
header('Content-Type: application/json');

try {
// On récupère les posts en joignant la table users pour avoir le username
// On trie par date de création, du plus récent au plus ancien
$result = $supabase->from('posts')
->select('id, description, created_at, likes_count, comments_count, users(username)')
->order('created_at', 'desc')
->execute();

echo json_encode(['success' => true, 'data' => $result->getData()]);
} catch (Exception $e) {
http_response_code(500);
echo json_encode(['success' => false, 'message' => 'Impossible de récupérer les posts.']);
}