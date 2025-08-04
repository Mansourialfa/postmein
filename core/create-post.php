<?php
require __DIR__ . '/db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
http_response_code(401);
echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$content = $input['content'] ?? '';

if (empty($content)) {
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Le contenu ne peut pas être vide.']);
exit;
}

try {
// Note: 'post_type' est mis à 'text'. La gestion des fichiers est une étape suivante.
$supabase->from('posts')->insert([
'user_id' => $_SESSION['user_id'],
'description' => $content,
'post_type' => 'text', // Simplifié pour les posts textuels
'status' => 'published'
]);
echo json_encode(['success' => true, 'message' => 'Post créé avec succès!']);
} catch (Exception $e) {
http_response_code(500);
echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du post.']);
}