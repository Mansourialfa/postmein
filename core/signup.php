<?php
// --- LIGNES DE DÉBOGAGE AJOUTÉES ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- FIN DES LIGNES DE DÉBOGAGE ---

require __DIR__ . '/db_connect.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

if (empty($email) || empty($username) || empty($password)) {
    http_response_code(400);
    // On ajoute un 'echo' pour s'assurer qu'une réponse JSON est toujours envoyée
    echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $supabase->from('users')->insert([
        'email' => $email,
        'username' => $username,
        'password_hash' => $password_hash,
    ]);
    echo json_encode(['success' => true, 'message' => 'Inscription réussie !']);
} catch (Exception $e) {
    http_response_code(500);
    // On envoie le message d'erreur réel pour le débogage
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}