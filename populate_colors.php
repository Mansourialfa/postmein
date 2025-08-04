<?php

// =============================================================================
// == CONFIGURATION ET INITIALISATION
// =============================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0); 
ini_set('memory_limit', '1G');

define('SUPABASE_URL', 'https://vlnrqdljzlnuakuvwppy.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZsbnJxZGxqemxudWFrdXZ3cHB5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1NDE3Njk1MCwiZXhwIjoyMDY5NzUyOTUwfQ.aNLaoVaFXrf5km0j4P89ppsBNnN3S0vcklAYFeZ7ogk');


// =============================================================================
// == LE GRAND DICTIONNAIRE POÉTIQUE
// =============================================================================
const ADJ_LUM_TRES_CLAIR = ['Éthéré', 'Céleste', 'Polaire', 'Cristallin', 'Solaire', 'Lunaire', 'Lacté', 'Angélique', 'Divin', 'Glacial', 'Aérien', 'Spirituel', 'Opalescent', 'Adamantin', 'Fantôme', 'Immaculé', 'Nival', 'Albinos', 'ASTRAL', 'Galactique', 'Infusé', 'Nébulé', 'Pâle', 'Évanescent', 'Vaporeux'];
const ADJ_LUM_CLAIR = ['Pastel', 'Poudré', 'Satiné', 'Délicat', 'Doux', 'Printanier', 'Matinal', 'Léger', 'Velouté', 'Givré', 'Nuageux', 'Soie', 'Brumeux', 'Limpide', 'Tendre', 'Discret', 'Subtil', 'Lavé', 'Effacé', 'Matin', 'Aurore', 'Perlé', 'Calme', 'Serein'];
const ADJ_LUM_MOYEN = ['Vif', 'Riche', 'Profond', 'Éclatant', 'Classique', 'Royal', 'Intense', 'Majestueux', 'Noble', 'Harmonieux', 'Terrien', 'Vibrant', 'Français', 'Italien', 'Pigment', 'Essence', 'Héraldique', 'Vénitien', 'Toscan', 'Équilibré', 'Soutenu', 'Généreux'];
const ADJ_LUM_SOMBRE = ['Nocturne', 'Sombre', 'Obscur', 'Profond', 'Sépulcral', 'Abyssal', 'Mystérieux', 'Ténébreux', 'Volcanique', 'Cosmique', 'Crépusculaire', 'Hypnotique', 'Interstellaire', 'Ensorcelé', 'Interdit', 'Secret', 'Insondable', 'Tellurique', 'Onyx', 'Corbeau', 'Réglisse', 'Gothique', 'Nuit'];

const ADJ_SAT_TRES_SATURE = ['Électrique', 'Vibrant', 'Néon', 'Psychédélique', 'Atomique', 'Ardent', 'Explosif', 'Exotique', 'Tropical', 'Magnétique', 'Pur', 'Fauve', 'Flamboyant', 'Digital', 'Surchargé', 'Radioactif', 'Acide', 'Brutaliste', 'Techno', 'Laser', 'Écarlate'];
const ADJ_SAT_SATURE = ['Riche', 'Profond', 'Naturel', 'Luxuriant', 'Audacieux', 'Affirmé', 'Élégant', 'Intense', 'Authentique', 'Vrai', 'Gourmand', 'Ensoleillé', 'Précieux', 'Exquis', 'Catalan', 'Andalou', 'Vermeil', 'Dense', 'Complet', 'Généreux', 'Franc'];
const ADJ_SAT_DESATURE = ['Désaturé', 'Fumé', 'Voilé', 'Muted', 'Subtil', 'Passé', 'Antique', 'Poussiéreux', 'Cendré', 'Mélancolique', 'Rêveur', 'Lointain', 'Oublié', 'Fantasmagorique', 'Ancien', 'Patine', 'Éteint', 'Tempéré', 'Nostalgique', 'Grisé', 'Terreux', 'Minéral'];

const NOMS_THEMATIQUES_COMMUNS = ['Rêve', 'Souvenir', 'Silence', 'Écho', 'Mystère', 'Secret', 'Utopie', 'Horizon', 'Infini', 'Oasis', 'Mirage', 'Poème', 'Mélodie', 'Parfum', 'Destin', 'Instant', 'Éternité', 'Oracle', 'Talisman', 'Sortilège', 'Chimère', 'Équinoxe', 'Solstice', 'Murmure', 'Serment'];
const NOMS_THEMATIQUES = [
    'Rouge' => ['Volcan', 'Passion', 'Rubis', 'Cœur', 'Danger', 'Cerise', 'Braise', 'Coquelicot', 'Mars', 'Grenade', 'Cinabre', 'Tomate'],
    'Orange' => ['Agrume', 'Coucher de Soleil', 'Ambre', 'Épice', 'Automne', 'Corail', 'Safran', 'Canyon', 'Abricot', 'Paprika', 'Tigre'],
    'Jaune' => ['Citron', 'Or', 'Désert', 'Miel', 'Topaze', 'Étoile', 'Sable', 'Pollen', 'Sahara', 'Soufre', 'Curcuma', 'Bouton d\'Or'],
    'Chartreuse' => ['Absinthe', 'Citron Vert', 'Verdoyant', 'Jungle', 'Toxique', 'Printemps', 'Pistache', 'Limon', 'Souffle de Dragon'],
    'Vert' => ['Forêt', 'Émeraude', 'Jade', 'Espoir', 'Trèfle', 'Menthe', 'Amazonie', 'Lichen', 'Absinthe', 'Péridot', 'Malachite', 'Olive'],
    'Menthe' => ['Glacier', 'Lagon', 'Opaline', 'Rivière', 'Cristal', 'Source', 'Antarctique', 'Verre de Mer', 'Bambou'],
    'Cyan' => ['Océan', 'Lagon', 'Turquoise', 'Givre', 'Néon', 'Cyber', 'Neptune', 'Piscine', 'Curaçao', 'Glacier'],
    'Azur' => ['Ciel', 'Azur', 'Saphir', 'Arctique', 'Liberté', 'Mer Égée', 'Zénith', 'Calanque'],
    'Bleu' => ['Minuit', 'Saphir', 'Océan', 'Royauté', 'Orage', 'Indigo', 'Majorelle', 'Infini', 'Lapis-lazuli', 'Encre', 'Denim'],
    'Violet' => ['Améthyste', 'Crépuscule', 'Magie', 'Impérial', 'Orchidée', 'Nébuleuse', 'Cosmos', 'Lavande', 'Glycine', 'Iris'],
    'Magenta' => ['Fuchsia', 'Passion', 'Bohème', 'Sortilège', 'Électrique', 'Fantaisie', 'Orchidée', 'Betterave', 'Anémone'],
    'Rose' => ['Quartz', 'Aurore', 'Pétale', 'Romance', 'Dragée', 'Fleur de Cerisier', 'Pivoine', 'Flamant', 'Guimauve'],
];
const ADJ_HUMEUR = ['Mystique', 'Légendaire', 'Secret', 'Oublié', 'Ancien', 'Éphémère', 'Interdit', 'Sacré', 'Primal', 'Originel', 'Hypnotique', 'Prophétique', 'Onirique', 'Nomade', 'Solitaire'];


function generateCreativeName($base_hue, $luminance, $saturation) {
    if ($saturation < 0.08) {
        if ($luminance > 0.95) return 'Blanc ' . ADJ_LUM_TRES_CLAIR[array_rand(ADJ_LUM_TRES_CLAIR)];
        if ($luminance < 0.05) return 'Noir ' . ADJ_LUM_SOMBRE[array_rand(ADJ_LUM_SOMBRE)];
        return 'Gris ' . ADJ_LUM_MOYEN[array_rand(ADJ_LUM_MOYEN)] . ' ' . ADJ_SAT_DESATURE[array_rand(ADJ_SAT_DESATURE)];
    }
    
    if ($luminance > 0.9) $lum_pool = ADJ_LUM_TRES_CLAIR; elseif ($luminance > 0.7) $lum_pool = ADJ_LUM_CLAIR; elseif ($luminance > 0.3) $lum_pool = ADJ_LUM_MOYEN; else $lum_pool = ADJ_LUM_SOMBRE;
    if ($saturation > 0.8) $sat_pool = ADJ_SAT_TRES_SATURE; elseif ($saturation > 0.5) $sat_pool = ADJ_SAT_SATURE; else $sat_pool = ADJ_SAT_DESATURE;
    
    $theme_pool = array_merge(NOMS_THEMATIQUES_COMMUNS, NOMS_THEMATIQUES[$base_hue]);
    
    $pattern = rand(1, 6);
    switch ($pattern) {
        case 1: return $base_hue . ' ' . $lum_pool[array_rand($lum_pool)];
        case 2: return $sat_pool[array_rand($sat_pool)] . ' ' . $base_hue;
        case 3: return $lum_pool[array_rand($lum_pool)] . ' de ' . $theme_pool[array_rand($theme_pool)];
        case 4: return $theme_pool[array_rand($theme_pool)] . ' ' . $sat_pool[array_rand($sat_pool)];
        case 5: return ADJ_HUMEUR[array_rand(ADJ_HUMEUR)] . ' ' . $theme_pool[array_rand($theme_pool)];
        case 6: return $base_hue . ' ' . ADJ_HUMEUR[array_rand(ADJ_HUMEUR)];
    }
}

// =============================================================================
// == FONCTIONS UTILITAIRES (COMMUNICATION AVEC SUPABASE)
// =============================================================================
function supabase_request($method, $table, $data = [], $params = '') {
    $url = SUPABASE_URL . '/rest/v1/' . $table . $params;
    $headers = [ 'apikey: ' . SUPABASE_KEY, 'Authorization: Bearer ' . SUPABASE_KEY, 'Content-Type: application/json' ];
    if ($method === 'POST') { $headers[] = 'Prefer: return=minimal,resolution=ignore-duplicates'; }
    
    $ch = curl_init($url);
    curl_setopt_array($ch, [ CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => $headers ]);
    
    switch (strtoupper($method)) {
        case 'POST': curl_setopt_array($ch, [CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($data)]); break;
        case 'DELETE': curl_setopt($ch, [CURLOPT_CUSTOMREQUEST => 'DELETE']); break;
    }
    
    curl_exec($ch); $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    return $http_code;
}

function deleteAllFromTable($table) {
    echo "TENTATIVE DE PURGE DE LA TABLE '$table'...\n";
    $http_code = supabase_request('DELETE', $table, [], '?id=not.is.null');
    if ($http_code >= 200 && $http_code < 300) {
        echo "--> SUCCÈS : La table '$table' a été vidée (Code: $http_code).\n\n";
        return true;
    }
    echo "--> ERREUR : Impossible de vider la table '$table' (Code: $http_code). Vérifiez les permissions RLS ?\n\n";
    return false;
}

// =============================================================================
// == SCRIPT PRINCIPAL "ÉDITION FINALE"
// =============================================================================
echo "---------------------------------------------------------\n";
echo "--- SCRIPT DE PEUPLEMENT 'ÉDITION FINALE' ---\n";
echo "---------------------------------------------------------\n";
echo "ATTENTION : Ce script va DÉTRUIRE DÉFINITIVEMENT toutes les données de la table 'colors'.\n";
$confirmation = readline("Pour continuer, tapez 'OUI' en majuscules : ");

if ($confirmation !== 'OUI') {
    echo "Confirmation non reçue. Annulation du script.\n";
    exit;
}

if (!deleteAllFromTable('colors')) {
    echo "Le script ne peut pas continuer car la purge a échoué.\n";
    exit;
}

$batch_size = 2000;
$colors_batch = [];
$total_inserted = 0;

for ($r = 0; $r <= 255; $r++) {
    for ($g = 0; $g <= 255; $g++) {
        for ($b = 0; $b <= 255; $b++) {
            
            $hex = sprintf("#%02x%02x%02x", $r, $g, $b);
            list($h, $s, $l) = rgbToHsl($r, $g, $b);
            $hue_name = getBaseHue($h);
            $name = generateCreativeName($hue_name, $l, $s);
            $desc = "La couleur unique '$name' ($hex), une teinte de la famille '$hue_name'.";

            $colors_batch[] = [ 'hex_code' => $hex, 'name' => $name, 'description' => $desc, 'base_hue' => $hue_name, 
                                'luminance' => $l, 'saturation' => $s, 'is_rare' => false, 'influence_score' => 0, 'is_for_sale' => false ];

            if (count($colors_batch) >= $batch_size) {
                echo "Progression (R=$r) | Envoi lot de " . count($colors_batch) . "... ";
                $start_time = microtime(true);
                $http_code = supabase_insert_batch('colors', $colors_batch);
                $duration = round(microtime(true) - $start_time, 2);

                if ($http_code == 201) {
                    $total_inserted += count($colors_batch);
                    echo "OK (Code: $http_code, {$duration}s). Total: $total_inserted\n";
                } else {
                    echo "AVERTISSEMENT (Code: $http_code). Lot ignoré.\n";
                }
                $colors_batch = [];
            }
        }
    }
}

if (!empty($colors_batch)) {
    echo "Envoi du dernier lot... ";
    supabase_insert_batch('colors', $colors_batch);
    echo "OK.\n";
}

echo "\n--- SCRIPT TERMINÉ ! L'univers des couleurs a été forgé. ---\n";