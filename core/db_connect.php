<?php
// CETTE LIGNE EST LA SOLUTION. Elle charge la bibliothèque Supabase pour que PHP la connaisse.
require __DIR__ . '/../vendor/autoload.php';

// Active l'affichage des erreurs pour le débogage.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ATTENTION : CLÉ D'ADMINISTRATEUR.
$supabaseUrl = 'https://vlnrqdljzlnuakuvwppy.supabase.co';
$supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZsbnJxZGxqemxudWFrdXZ3cHB5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1NDE3Njk1MCwiZXhwIjoyMDY5NzUyOTUwfQ.aNLaoVaFXrf5km0j4P89ppsBNnN3S0vcklAYFeZ7ogk';

// Cette ligne ne causera plus d'erreur car la fonction est maintenant connue.
$supabase = Supabase\createClient($supabaseUrl, $supabaseKey);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}