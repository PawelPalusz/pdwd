<?php
// CPT Obszar
add_action('init', function () {
    register_post_type('obszar', [
        'labels' => [
            'name' => 'Obszary',
            'singular_name' => 'Obszar',
            'add_new_item' => 'Dodaj nowy obszar',
            'edit_item' => 'Edytuj obszar',
            'new_item' => 'Nowy obszar',
            'view_item' => 'Zobacz obszar',
            'search_items' => 'Szukaj obszarów',
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => ['title'],
    ]);
});
