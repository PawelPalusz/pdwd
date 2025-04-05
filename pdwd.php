<?php
/**
 * Plugin Name: PDWD – Karta + Interesanci + Obszary + TERYT
 * Description: Wtyczka z obsługą TERYT z lokalnego JSON, dynamiczne zależności woj-pow-gm, CPT interesant + obszar.
 * Version: 1.4
 * Author: Twój Zespół
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/cpt-interesant.php';
require_once plugin_dir_path(__FILE__) . 'includes/cpt-obszar.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/karta-shortcode.php';

// JSON ACF
add_filter('acf/settings/load_json', fn($paths) => array_merge($paths, [plugin_dir_path(__FILE__) . 'acf-json']));
add_filter('acf/settings/save_json', fn($path) => plugin_dir_path(__FILE__) . 'acf-json');

// Dynamiczne pola z teryt.json
function pdwd_load_teryt_json() {
    $path = WP_CONTENT_DIR . '/uploads/teryt.json';
    return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
}

add_filter('acf/load_field/name=wojewodztwo', function ($field) {
    $data = pdwd_load_teryt_json();
    $woj = array_unique(array_column($data, 'wojewodztwo'));
    sort($woj);
    $field['choices'] = array_combine($woj, $woj);
    return $field;
});

add_filter('acf/load_field/name=powiat', function ($field) {
    $data = pdwd_load_teryt_json();
    $woj = $_GET['woj'] ?? null;
    $pow = [];
    foreach ($data as $entry) {
        if (!$woj || $entry['wojewodztwo'] === $woj) $pow[] = $entry['powiat'];
    }
    $pow = array_unique($pow);
    sort($pow);
    $field['choices'] = array_combine($pow, $pow);
    return $field;
});

add_filter('acf/load_field/name=gmina', function ($field) {
    $data = pdwd_load_teryt_json();
    $pow = $_GET['pow'] ?? null;
    $gminy = [];
    foreach ($data as $entry) {
        if (!$pow || $entry['powiat'] === $pow) $gminy[] = $entry['gmina'];
    }
    $gminy = array_unique($gminy);
    sort($gminy);
    $field['choices'] = array_combine($gminy, $gminy);
    return $field;
});

// Enqueue JS do admina
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('pdwd-teryt-js', plugin_dir_url(__FILE__) . 'teryt.js', ['jquery'], null, true);
});


// Wyłącz Gutenberg + edytor klasyczny
add_filter('use_block_editor_for_post', '__return_false');
add_action('admin_init', function () {
    remove_post_type_support('post', 'editor');
});


// Auto-clean opis_skrocony

add_action('admin_init', function () {
  if (!current_user_can('manage_options')) return;
  if (get_option('pdwd_opis_skrocony_removed')) return;

  $args = [
    'post_type' => 'obszar',
    'post_status' => 'any',
    'posts_per_page' => -1,
    'fields' => 'ids'
  ];
  $posts = get_posts($args);
  foreach ($posts as $post_id) {
    delete_post_meta($post_id, 'opis_skrocony');
    delete_post_meta($post_id, '_opis_skrocony');
  }

  update_option('pdwd_opis_skrocony_removed', 1);
});
