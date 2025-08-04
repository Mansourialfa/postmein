<?php

// =============================================================================
// == CONFIGURATION & INITIALIZATION
// =============================================================================
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
set_time_limit(0); 
ini_set('memory_limit', '1G'); // Keep 1GB for safety

define('SUPABASE_URL', 'https://vlnrqdljzlnuakuvwppy.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZsbnJxZGxqemxudWFrdXZ3cHB5Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1NDE3Njk1MCwiZXhwIjoyMDY5NzUyOTUwfQ.aNLaoVaFXrf5km0j4P89ppsBNnN3S0vcklAYFeZ7ogk');


// =============================================================================
// == DICTIONARIES AND UTILITY FUNCTIONS (Unchanged)
// =============================================================================
const ADJ_LUM_VERY_BRIGHT = ['Ethereal', 'Celestial', 'Polar', 'Crystalline', 'Solar', 'Lunar', 'Angelic', 'Divine', 'Glacial', 'Spiritual', 'Opalescent', 'Ghostly', 'Immaculate', 'Astral', 'Galactic', 'Nebulous', 'Evanescent', 'Vaporous'];
const ADJ_LUM_BRIGHT = ['Pastel', 'Pale', 'Powder', 'Satin', 'Delicate', 'Soft', 'Spring', 'Morning', 'Feather', 'Velvet', 'Frosted', 'Cloudy', 'Silk', 'Misty', 'Limpid', 'Gentle', 'Subtle', 'Washed', 'Faded', 'Dawn'];
const ADJ_LUM_MEDIUM = ['Vivid', 'Rich', 'Deep', 'Bright', 'Classic', 'Royal', 'Intense', 'Majestic', 'Noble', 'Harmonious', 'Earthen', 'Vibrant', 'Heraldic', 'Venetian', 'Tuscan', 'Balanced', 'Saturated', 'Generous'];
const ADJ_LUM_DARK = ['Nocturnal', 'Dark', 'Obscure', 'Deep', 'Sepulchral', 'Abyssal', 'Mysterious', 'Shadowy', 'Volcanic', 'Cosmic', 'Twilight', 'Hypnotic', 'Interstellar', 'Forbidden', 'Secret', 'Onyx', 'Raven', 'Gothic', 'Midnight'];
const ADJ_SAT_VERY_SATURATED = ['Electric', 'Vibrant', 'Neon', 'Psychedelic', 'Atomic', 'Fiery', 'Explosive', 'Exotic', 'Tropical', 'Magnetic', 'Pure', 'Wild', 'Flamboyant', 'Digital', 'Overload', 'Radioactive', 'Acid', 'Laser', 'Scarlet'];
const ADJ_SAT_SATURATED = ['Rich', 'Deep', 'Natural', 'Lush', 'Bold', 'Assertive', 'Elegant', 'Intense', 'Authentic', 'True', 'Gourmet', 'Sun-kissed', 'Precious', 'Exquisite', 'Andalusian', 'Vermilion', 'Dense', 'Full', 'Generous', 'Frank'];
const ADJ_SAT_DESATURATED = ['Desaturated', 'Smoky', 'Veiled', 'Muted', 'Subtle', 'Faded', 'Antique', 'Dusty', 'Ashen', 'Melancholic', 'Dreamy', 'Distant', 'Forgotten', 'Phantasmagoric', 'Ancient', 'Patina', 'Tempered', 'Nostalgic', 'Mineral'];
const NOUNS_THEMATIC_COMMON = ['Dream', 'Memory', 'Silence', 'Echo', 'Mystery', 'Secret', 'Utopia', 'Horizon', 'Infinity', 'Oasis', 'Mirage', 'Poem', 'Melody', 'Perfume', 'Destiny', 'Moment', 'Eternity', 'Oracle', 'Talisman', 'Spell', 'Chimera', 'Equinox', 'Solstice', 'Whisper', 'Oath'];
const NOUNS_THEMATIQUES = [
    'Red' => ['Volcano', 'Passion', 'Ruby', 'Heart', 'Danger', 'Cherry', 'Ember', 'Poppy', 'Mars', 'Garnet', 'Cinabar'], 'Orange' => ['Citrus', 'Sunset', 'Amber', 'Spice', 'Autumn', 'Coral', 'Saffron', 'Canyon', 'Apricot', 'Paprika', 'Tiger'], 'Yellow' => ['Lemon', 'Gold', 'Desert', 'Honey', 'Topaz', 'Star', 'Sand', 'Pollen', 'Sahara', 'Sulfur', 'Turmeric'], 'Chartreuse' => ['Absinthe', 'Lime', 'Verdant', 'Jungle', 'Toxic', 'Spring', 'Pistachio', 'Dragon\'s Breath'], 'Green' => ['Forest', 'Emerald', 'Jade', 'Hope', 'Clover', 'Mint', 'Amazon', 'Lichen', 'Absinthe', 'Peridot', 'Malachite', 'Olive'], 'Mint' => ['Glacier', 'Lagoon', 'Opaline', 'River', 'Crystal', 'Source', 'Antarctic', 'Sea Glass', 'Bamboo'], 'Cyan' => ['Ocean', 'Lagoon', 'Turquoise', 'Frost', 'Neon', 'Cyber', 'Neptune', 'Pool', 'Curaçao', 'Glacier'], 'Azure' => ['Sky', 'Azure', 'Sapphire', 'Arctic', 'Freedom', 'Aegean', 'Zenith'], 'Blue' => ['Midnight', 'Sapphire', 'Ocean', 'Royalty', 'Storm', 'Indigo', 'Majorelle', 'Infinity', 'Lapis Lazuli', 'Ink', 'Denim'], 'Violet' => ['Amethyst', 'Twilight', 'Magic', 'Imperial', 'Orchid', 'Nebula', 'Cosmos', 'Lavender', 'Wisteria', 'Iris'], 'Magenta' => ['Fuchsia', 'Passion', 'Bohemian', 'Spellbound', 'Electric', 'Fantasy', 'Orchid', 'Anemone'], 'Rose' => ['Quartz', 'Dawn', 'Petal', 'Romance', 'Candy', 'Cherry Blossom', 'Peony', 'Flamingo', 'Marshmallow'],
];
const ADJ_MOOD = ['Mystic', 'Legendary', 'Secret', 'Forgotten', 'Ancient', 'Ephemeral', 'Forbidden', 'Sacred', 'Primal', 'Primeval', 'Hypnotic', 'Prophetic', 'Oneiric', 'Nomad', 'Solitary'];
function generateCreativeName($base_hue, $luminance, $saturation) { if ($saturation < 0.08) { if ($luminance > 0.95) return 'White ' . ADJ_LUM_VERY_BRIGHT[array_rand(ADJ_LUM_VERY_BRIGHT)]; if ($luminance < 0.05) return 'Black ' . ADJ_LUM_DARK[array_rand(ADJ_LUM_DARK)]; return 'Grey ' . ADJ_LUM_MEDIUM[array_rand(ADJ_LUM_MEDIUM)] . ' ' . ADJ_SAT_DESATURATED[array_rand(ADJ_SAT_DESATURATED)]; } if ($luminance > 0.9) $lum_pool = ADJ_LUM_VERY_BRIGHT; elseif ($luminance > 0.7) $lum_pool = ADJ_LUM_BRIGHT; elseif ($luminance > 0.3) $lum_pool = ADJ_LUM_MEDIUM; else $lum_pool = ADJ_LUM_DARK; if ($saturation > 0.8) $sat_pool = ADJ_SAT_VERY_SATURATED; elseif ($saturation > 0.5) $sat_pool = ADJ_SAT_SATURATED; else $sat_pool = ADJ_SAT_DESATURATED; $theme_pool = array_merge(NOUNS_THEMATIC_COMMON, NOUNS_THEMATIQUES[$base_hue]); $pattern = rand(1, 6); switch ($pattern) { case 1: return $base_hue . ' ' . $lum_pool[array_rand($lum_pool)]; case 2: return $sat_pool[array_rand($sat_pool)] . ' ' . $base_hue; case 3: return $lum_pool[array_rand($lum_pool)] . ' ' . $theme_pool[array_rand($theme_pool)]; case 4: return $theme_pool[array_rand($theme_pool)] . ' ' . $sat_pool[array_rand($sat_pool)]; case 5: return ADJ_MOOD[array_rand(ADJ_MOOD)] . ' ' . $theme_pool[array_rand($theme_pool)]; case 6: return $base_hue . ' ' . ADJ_MOOD[array_rand(ADJ_MOOD)]; } }
function rgbToHsl($r, $g, $b) { $r /= 255; $g /= 255; $b /= 255; $max = max($r, $g, $b); $min = min($r, $g, $b); $l = ($max + $min) / 2; if ($max == $min) { $h = $s = 0; } else { $d = $max - $min; $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min); switch ($max) { case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break; case $g: $h = ($b - $r) / $d + 2; break; case $b: $h = ($r - $g) / $d + 4; break; } $h /= 6; } return [round($h * 360), $s, $l]; }
function getBaseHue($h) { if ($h >= 345 || $h < 15) return 'Red'; if ($h < 45) return 'Orange'; if ($h < 75) return 'Yellow'; if ($h < 105) return 'Chartreuse'; if ($h < 135) return 'Green'; if ($h < 165) return 'Mint'; if ($h < 195) return 'Cyan'; if ($h < 225) return 'Azure'; if ($h < 255) return 'Blue'; if ($h < 285) return 'Violet'; if ($h < 315) return 'Magenta'; if ($h < 345) return 'Rose'; return 'Grey'; }
function supabase_insert_batch($table, $data) { $url = SUPABASE_URL . '/rest/v1/' . $table; $headers = [ 'apikey: ' . SUPABASE_KEY, 'Authorization: Bearer ' . SUPABASE_KEY, 'Content-Type: application/json', 'Prefer: return=minimal' ]; $ch = curl_init($url); curl_setopt_array($ch, [ CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => $headers, CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($data) ]); curl_exec($ch); $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch); return $http_code; }
function format_seconds( $seconds ) { $hours = floor( $seconds / 3600 ); $minutes = floor( ( $seconds / 60 ) % 60 ); $seconds = $seconds % 60; return sprintf( "%02d:%02d:%02d", $hours, $minutes, $seconds ); }


// =============================================================================
// == MAIN SCRIPT - UPGRADED VERSION (Batch Size: 10,000)
// =============================================================================
echo "---------------------------------------------------------\n";
echo "--- 'UNIVERSE FORGE' POPULATION SCRIPT ---\n";
echo "---------------------------------------------------------\n";

$batch_size = 10000; // Batch size adjusted to 10,000
$colors_batch = [];
$total_inserted = 0;
$total_colors = 256 * 256 * 256;
$script_start_time = microtime(true);

for ($r = 0; $r <= 255; $r++) {
    for ($g = 0; $g <= 255; $g++) {
        for ($b = 0; $b <= 255; $b++) {
            $hex = sprintf("#%02x%02x%02x", $r, $g, $b);
            list($h, $s, $l) = rgbToHsl($r, $g, $b);
            $hue_name = getBaseHue($h);
            $name = generateCreativeName($hue_name, $l, $s);
            $desc = "The unique color '$name' ($hex), a shade from the '$hue_name' family.";

            $colors_batch[] = [
                'hex_code' => $hex, 'name' => $name, 'description' => $desc, 'base_hue' => $hue_name, 
                'luminance' => $l, 'saturation' => $s, 'is_rare' => false, 'influence_score' => 0, 'is_for_sale' => false
            ];

            // When the batch is full, send it to Supabase
            if (count($colors_batch) >= $batch_size) {
                $batch_start_time = microtime(true);
                $http_code = supabase_insert_batch('colors', $colors_batch);
                $batch_duration = microtime(true) - $batch_start_time;
                
                if ($http_code == 201) {
                    $total_inserted += count($colors_batch);
                    $percentage = ($total_inserted / $total_colors) * 100;
                    $elapsed_time = microtime(true) - $script_start_time;
                    $eta = ($elapsed_time / $total_inserted) * ($total_colors - $total_inserted);

                    // --- PROGRESS BAR ---
                    $progress = floor($percentage / 2);
                    $bar = str_repeat('=', $progress) . str_repeat(' ', 50 - $progress);
                    
                    // \r moves the cursor to the beginning of the line to create an updating effect
                    echo "\r[$bar] " . number_format($percentage, 2) . "% | Total: " . number_format($total_inserted) . " | ETA: " . format_seconds($eta) . "   ";

                } else {
                    // Changed error message for better clarity on batch errors
                    echo "\nERROR (Code: $http_code). Batch failed. Aborting script. Check Supabase logs and RLS permissions. It might also be a memory issue.\n";
                    exit;
                }
                $colors_batch = []; // Reset batch
            }
        }
    }
}

// Send the final, smaller batch if any colors are left
if (!empty($colors_batch)) {
    echo "\nSending final batch... ";
    $http_code = supabase_insert_batch('colors', $colors_batch);
    echo ($http_code == 201) ? "OK.\n" : "ERROR (Code: $http_code).\n";
}

echo "\n\n--- SCRIPT FINISHED! The color universe has been forged. ---\n";