<?php
// On inclut nos fichiers de base
require __DIR__ . '/core/db_connect.php';
require __DIR__ . '/core/functions.php';

// Augmente le temps d'exécution pour les tests
set_time_limit(120);

// --- Début du rapport de test ---
header('Content-Type: text/plain; charset=utf-8');
echo "=============================================\n";
echo "== RAPPORT DE TEST DES FONCTIONS CORE ==\n";
echo "=============================================\n\n";

// --- TEST 1: APPEL À LA FONCTION SQL 'get_random_unowned_color' ---
echo "--- TEST 1: TROUVER UNE COULEUR LIBRE ---\n";
$colorResponse = supabase_request('POST', 'rpc/get_random_unowned_color');
if ($colorResponse['code'] == 200 && !empty($colorResponse['body'])) {
    echo "[SUCCÈS] La fonction SQL 'get_random_unowned_color' a retourné une couleur.\n";
    $test_color = $colorResponse['body'][0];
    echo "   - Couleur trouvée : " . $test_color['hex_code'] . " (ID: " . $test_color['id'] . ")\n\n";
} else {
    echo "[ÉCHEC] Impossible d'appeler la fonction SQL 'get_random_unowned_color'.\n";
    echo "   - Code HTTP: " . ($colorResponse['code'] ?? 'N/A') . "\n";
    echo "   - Réponse: " . json_encode($colorResponse['body'] ?? 'N/A') . "\n";
    echo "   >>> VÉRIFIEZ QUE LA FONCTION SQL A BIEN ÉTÉ CRÉÉE ET A LES BONNES PERMISSIONS (SECURITY DEFINER).\n\n";
    exit; // On arrête tout, les autres tests ne peuvent pas continuer.
}


// --- TEST 2: INSCRIPTION D'UN NOUVEL UTILISATEUR ---
echo "--- TEST 2: INSCRIPTION (Fonction registerUserAndAssignColor) ---\n";
// On utilise time() pour garantir que l'email et le username sont uniques à chaque test
$test_email = 'testeur_' . time() . '@test.com';
$test_username = 'Testeur_' . time();
$test_password = 'password123';
echo "Tentative d'inscription avec l'email: $test_email\n";

$registrationResult = registerUserAndAssignColor($test_email, $test_username, $test_password);

if ($registrationResult['success']) {
    echo "[SUCCÈS] La fonction 'registerUserAndAssignColor' a fonctionné.\n";
    echo "   - Message: " . $registrationResult['message'] . "\n\n";
    // On doit récupérer l'ID de ce nouvel utilisateur pour le test suivant
    $userCheckParams = '?select=id&email=eq.' . urlencode($test_email);
    $userCheckResponse = supabase_request('GET', 'users', [], $userCheckParams);
    if($userCheckResponse['code'] == 200 && !empty($userCheckResponse['body'])) {
        $test_user_id = $userCheckResponse['body'][0]['id'];
        echo "   - Nouvel utilisateur ID récupéré pour les tests futurs: $test_user_id\n\n";
    } else {
        echo "[AVERTISSEMENT] L'inscription a réussi mais impossible de récupérer l'ID du nouvel utilisateur.\n\n";
        $test_user_id = null;
    }
} else {
    echo "[ÉCHEC] La fonction 'registerUserAndAssignColor' a échoué.\n";
    echo "   - Message: " . $registrationResult['message'] . "\n";
    echo "   - Code HTTP: " . ($registrationResult['code'] ?? 'N/A') . "\n\n";
    exit; // On arrête
}

// --- TEST 3: CONNEXION DE L'UTILISATEUR ---
if($test_user_id) {
    echo "--- TEST 3: CONNEXION (Fonction loginUser) ---\n";
    echo "Tentative de connexion avec l'email: $test_email\n";
    // Vide la session actuelle pour un test propre
    session_destroy();
    session_start();

    $loginResult = loginUser($test_email, $test_password);
    
    if($loginResult['success'] && isset($_SESSION['user_id']) && $_SESSION['user_id'] === $test_user_id) {
        echo "[SUCCÈS] La fonction 'loginUser' a fonctionné et a correctement défini la session.\n";
        echo "   - ID utilisateur en session: " . $_SESSION['user_id'] . "\n\n";
    } else {
        echo "[ÉCHEC] La fonction 'loginUser' a échoué.\n";
        echo "   - Message: " . ($loginResult['message'] ?? 'Erreur inconnue') . "\n";
        echo "   - ID en session: " . ($_SESSION['user_id'] ?? 'non défini') . "\n\n";
    }
}


echo "=============================================\n";
echo "== FIN DU RAPPORT DE TEST ==\n";
echo "=============================================\n";

?>