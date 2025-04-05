<?php
// CPT Interesant
add_action('init', function () {
    register_post_type('interesant', [
        'labels' => [
            'name' => 'Interesanci',
            'singular_name' => 'Interesant',
            'add_new_item' => 'Dodaj nowego interesanta',
            'edit_item' => 'Edytuj interesanta',
            'new_item' => 'Nowy interesant',
            'view_item' => 'Zobacz interesanta',
            'search_items' => 'Szukaj interesantów',
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-id',
        'supports' => ['title'],
    ]);
});
