<?php
add_shortcode('karta_informacyjna_view', function () {
    if (!is_singular('post')) return '';
    $id = get_the_ID();
    $html = '<div class="karta-informacyjna"><h3>Karta Informacyjna</h3><ul>';

    $fields = [
        'rodzaj_dokumentu' => 'Rodzaj dokumentu',
        'zakres_przedmiotowy' => 'Zakres przedmiotowy',
        'data_dokumentu' => 'Data dokumentu',
        'znak_sprawy' => 'Znak sprawy',
        'miejsce_przechowywania' => 'Miejsce przechowywania dokumentu',
        'zastrzezenia' => 'Zastrzeżenia',
        'uwagi' => 'Uwagi'
    ];

    foreach ($fields as $key => $label) {
        $val = get_field($key, $id);
        if ($val) {
            $html .= "<li><strong>{$label}:</strong> {$val}</li>";
        }
    }

    if ($interesant = get_field('interesant', $id)) {
        $tel = get_field('telefon', $interesant->ID);
        $email = get_field('email', $interesant->ID);
        $html .= "<li><strong>Interesant:</strong> {$interesant->post_title}";
        if ($tel || $email) {
            $html .= " (";
            if ($tel) $html .= "tel: {$tel}";
            if ($tel && $email) $html .= ", ";
            if ($email) $html .= "email: {$email}";
            $html .= ")";
        }
        $html .= "</li>";
    }

    if (have_rows('zalaczniki', $id)) {
        $html .= "<li><strong>Załączniki:</strong><ul>";
        while (have_rows('zalaczniki', $id)) {
            the_row();
            $plik = get_sub_field('plik');
            $opis = get_sub_field('opis');
            if ($plik) {
                $url = esc_url($plik['url']);
                $name = $opis ?: basename($url);
                $html .= "<li><a href='{$url}' target='_blank'>{$name}</a></li>";
            }
        }
        $html .= "</ul></li>";
    }

    $html .= '</ul></div>';
    return $html;
});
