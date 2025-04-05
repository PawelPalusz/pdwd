<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ładowanie skryptów w panelu admina dla Wpisów, Obszarów, Interesantów itp.
 */
add_action('admin_enqueue_scripts', 'pdwd_admin_enqueue_scripts');
function pdwd_admin_enqueue_scripts($hook_suffix) {

    // Ładuj skrypt np. na wszystkich ekranach edycji (post.php, post-new.php).
    // Możesz też zawęzić, jeśli chcesz (np. sprawdzać post_type itd.)
    if (in_array($hook_suffix, array('post.php', 'post-new.php'))) {
        
        // Zarejestruj i załaduj skrypt 'teryt.js'
        wp_enqueue_script(
            'pdwd-teryt',
            plugin_dir_url(__FILE__) . '../assets/js/teryt.js',
            array('jquery'),
            '1.0',
            true
        );

        // Przekaż do JS ścieżkę do pliku teryt.json we wtyczce
        wp_localize_script('pdwd-teryt','myPdwd', array(
            'teryt_url' => plugin_dir_url(__FILE__) . '../assets/teryt.json',
        ));
    }
}
