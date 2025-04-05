<?php
if (!defined('ABSPATH')) {
    exit;
}

// Rejestracja CPT "interesant"
add_action('init', 'pdwd_register_cpt_interesant');
function pdwd_register_cpt_interesant() {

    $labels = array(
        'name'               => 'Interesanci',
        'singular_name'      => 'Interesant',
        'add_new'            => 'Dodaj nowego',
        'add_new_item'       => 'Dodaj nowego Interesanta',
        'edit_item'          => 'Edytuj Interesanta',
        'new_item'           => 'Nowy Interesant',
        'view_item'          => 'Zobacz Interesanta',
        'search_items'       => 'Szukaj Interesantów',
        'not_found'          => 'Nie znaleziono',
        'not_found_in_trash' => 'Nie znaleziono w koszu',
        'all_items'          => 'Wszyscy Interesanci',
    );

    $args = array(
        'label'               => 'Interesanci',
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,   // pokazywanie w panelu
        'show_in_menu'        => true,   // w głównym menu admina
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-businessman', // dowolna ikona
        'supports'            => array('title'),          // ACF zajmuje się polami
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
    );

    register_post_type('interesant', $args);
}
