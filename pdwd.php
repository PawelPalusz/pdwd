<?php
/**
 * Plugin Name: PDWD – Moduł EkoPortal
 * Description: Wtyczka rejestruje CPT "Interesant" i "Obszar", dodaje dynamiczne pola ACF i wyłącza Gutenberg dla wpisów.
 * Version: 1.0
 * Author: Twoja Nazwa
 */

// Zabezpieczenie bezpośredniego wywołania.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Załaduj pliki CPT z folderu /includes
 */
include_once plugin_dir_path(__FILE__) . 'includes/cpt-interesant.php';
include_once plugin_dir_path(__FILE__) . 'includes/cpt-obszar.php';

/**
 * 2. Załaduj rejestrację i enqueue skryptów admina.
 */
include_once plugin_dir_path(__FILE__) . 'includes/admin-scripts.php';

/**
 * 3. Wyłącz Gutenberg dla "post" (jeśli chcesz klasyczny edytor tylko w post).
 *    Jeśli chcesz wyłączyć globalnie, możesz użyć filtera __return_false.
 */
add_filter('use_block_editor_for_post_type', 'pdwd_disable_gutenberg_for_post', 10, 2);
function pdwd_disable_gutenberg_for_post($can_edit, $post_type){
    if ($post_type === 'post') {
        return false;
    }
    return $can_edit;
}

/**
 * 4. (Opcjonalnie) Automatyczny import plików JSON ACF z /acf-json
 *    Jeśli nie chcesz tego robić, usuń poniższą sekcję.
 */
add_filter('acf/settings/save_json', 'pdwd_acf_json_save_point');
function pdwd_acf_json_save_point($path) {
    $path = plugin_dir_path(__FILE__) . '/acf-json';
    return $path;
}
add_filter('acf/settings/load_json', 'pdwd_acf_json_load_point');
function pdwd_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = plugin_dir_path(__FILE__) . '/acf-json';
    return $paths;
}
