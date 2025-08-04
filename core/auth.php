<?php
require __DIR__ . '/db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (empty($email) || empty($password)) {
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis.']);
exit;
}

try {
$result = $supabase->from('users')->select('id, password_hash')->eq('email', $email)->execute();
$user = $result->getData()[0] ?? null;

if ($user && password_verify($password, $user['password_hash'])) {
$_SESSION['user_id'] = $user['id'];
echo json_encode(['success' => true, 'message' => 'Connexion réussie.']);
} else {
http_response_code(401);
echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect.']);
}
} catch (Exception $e) {
http_response_code(500);
echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
}