<?php

// --- Affiche toutes les erreurs pour un débogage parfait ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


echo "--- DÉBUT DU TEST D'INSERTION DIRECTE ---\n\n";

// --- Configuration Directe (pas besoin d'autres fichiers) ---
$supabaseUrl = 'https://vlnrqdljzlnuakuvwppy.supabase.co';
$apiKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZsbnJxZGxqemxudWFrdXZ3cHB5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1NDE3Njk1MCwiZXhwIjoyMDY5NzUyOTUwfQ.aNLaoVaFXrf5km0j4P89ppsBNnN3S0vcklAYFeZ7ogk';


// --- Préparation des données de l'utilisateur fictif ---
// On utilise time() pour s'assurer que l'email est unique à chaque exécution
$dummy_email = 'testuser_' . time() . '@fictif.com';
$dummy_username = 'Testeur_' . time();
$dummy_password = 'password123';
$hashed_password = password_hash($dummy_password, PASSWORD_DEFAULT);

$data_to_insert = [
    'email' => $dummy_email,
    'username' => $dummy_username,
    'password_hash' => $hashed_password
];

echo "1. Données préparées pour l'utilisateur : " . $dummy_username . "\n";


// --- Construction de la requête cURL ---
$url = $supabaseUrl . '/rest/v1/users';

$headers = [
    'apikey: ' . $apiKey,
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json',
    'Prefer: return=minimal' // On ne demande pas à Supabase de renvoyer les données, juste de confirmer
];

echo "2. Envoi de la requête POST à l'URL : " . $url . "\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_to_insert));

// --- Exécution et Vérification ---
$response_body = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

echo "3. Réponse reçue du serveur.\n";

if ($curl_error) {
    echo "\n--> ERREUR cURL : " . $curl_error . "\n";
} else {
    echo "   Code de statut HTTP : " . $http_code . "\n";
    
    // Le code 201 "Created" est le code de succès pour une insertion POST
    if ($http_code == 201) {
        echo "\n--> SUCCÈS ! L'utilisateur a été inséré dans la base de données !\n";
        echo "   Vous pouvez vérifier dans le 'Table Editor' de Supabase.\n";
    } else {
        echo "\n--> ERREUR ! L'insertion a échoué.\n";
        echo "   Réponse brute du serveur : " . $response_body . "\n";
    }
}

echo "\n--- FIN DU TEST ---\n";

?>