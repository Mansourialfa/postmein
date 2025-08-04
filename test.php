<?php

echo "--- DÉBUT DU TEST DE CONNEXION ---\n\n";

// ÉTAPE 1: Vérifier si le fichier autoload.php existe
$autoloader_path = __DIR__ . '/vendor/autoload.php';
echo "1. Recherche du fichier autoloader à l'adresse : " . $autoloader_path . "\n";

if (!file_exists($autoloader_path)) {
    echo "   ERREUR FATALE : Le fichier autoload.php est introuvable. Avez-vous bien lancé la commande 'composer install' ?\n";
    exit; // On arrête tout si le fichier n'est pas là
}
echo "   --> SUCCÈS : Le fichier a été trouvé.\n\n";


// ÉTAPE 2: Essayer de charger ce fichier
echo "2. Tentative de chargement de la bibliothèque (require autoload.php)\n";
require $autoloader_path;
echo "   --> SUCCÈS : La bibliothèque a été chargée sans erreur.\n\n";


// ÉTAPE 3: Vérifier si PHP connaît maintenant la fonction de Supabase
echo "3. Vérification : Est-ce que la fonction 'Supabase\createClient' existe maintenant ?\n";

if (function_exists('Supabase\createClient')) {
    echo "   --> SUCCÈS : OUI ! PHP connaît la fonction.\n\n";
} else {
    echo "   ERREUR FATALE : NON. Même après avoir chargé la bibliothèque, PHP ne connaît pas la fonction. Votre installation (dossier vendor) est peut-être corrompue.\n";
    exit;
}


// ÉTAPE 4: Exécuter le code de votre fichier db_connect.php
echo "4. Tentative de création du client Supabase (le code de votre fichier db_connect.php)\n";
try {
    $supabaseUrl = 'https://vlnrqdljzlnuakuvwppy.supabase.co';
    $supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZsbnJxZGxqemxudWFrdXZ3cHB5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1NDE3Njk1MCwiZXhwIjoyMDY5NzUyOTUwfQ.aNLaoVaFXrf5km0j4P89ppsBNnN3S0vcklAYFeZ7ogk';
    $supabase = Supabase\createClient($supabaseUrl, $supabaseKey);
    
    echo "   --> SUCCÈS : Le client Supabase a été créé !\n\n";
} catch (Throwable $t) {
    echo "   ERREUR FATALE lors de la création du client : " . $t->getMessage() . "\n";
    exit;
}

echo "--- FIN DU TEST : Si vous voyez ce message, tout devrait fonctionner. ---\n";

?>