<?php
if (!defined('ABSPATH')) {
    exit;
}

// Rejestracja CPT "obszar"
add_action('init', 'pdwd_register_cpt_obszar');
function pdwd_register_cpt_obszar() {

    $labels = array(
        'name'               => 'Obszary',
        'singular_name'      => 'Obszar',
        'add_new'            => 'Dodaj nowy',
        'add_new_item'       => 'Dodaj nowy Obszar',
        'edit_item'          => 'Edytuj Obszar',
        'new_item'           => 'Nowy Obszar',
        'view_item'          => 'Zobacz Obszar',
        'search_items'       => 'Szukaj Obszarów',
        'not_found'          => 'Nie znaleziono',
        'not_found_in_trash' => 'Nie znaleziono w koszu',
        'all_items'          => 'Wszystkie Obszary',
    );

    $args = array(
        'label'               => 'Obszary',
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,   // pokazywanie w panelu
        'show_in_menu'        => true,   // w głównym menu admina
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-admin-site',
        'supports'            => array('title'), // ACF zajmuje się polami
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
    );

    register_post_type('obszar', $args);
}
