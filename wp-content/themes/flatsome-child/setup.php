<?php
/**
 * Kreski — jednorazowy setup Flatsome
 * Uruchom raz: dev.kreski.pl/wp-content/themes/flatsome-child/setup.php
 * Po uruchomieniu usuń plik lub zostanie usunięty automatycznie.
 */

// Załaduj WordPress
$wp_load = dirname(__FILE__) . '/../../../../wp-load.php';
if ( ! file_exists( $wp_load ) ) {
    die('wp-load.php not found');
}
require_once $wp_load;

// Zabezpieczenie — tylko admin
if ( ! current_user_can('manage_options') ) {
    wp_die('Brak uprawnień.');
}

$results = [];

// ── 1. FLATSOME THEME MODS ──────────────────────────────────────

$mods = [
    // Topbar
    'top_bar'               => '1',
    'top_bar_sticky'        => '0',
    'top_bar_text'          => '<a href="tel:+48226662240">&#9990; 22 666 22 40</a> &nbsp;&bull;&nbsp; <a href="mailto:kreski@kreski.pl">&#9993; kreski@kreski.pl</a>',
    'top_bar_right'         => 'social',

    // Header
    'header_layout'         => 'logo-left-nav-right',
    'header_height'         => '74',
    'header_border_bottom'  => '1',
    'header_search'         => '1',
    'header_sticky'         => '1',
    'header_sticky_shrink'  => '1',

    // Header — po prawej telefon
    'header_right_html'     => '<a href="tel:+48226662240" class="header-tel">&#9990; 22 666 22 40</a>',

    // Logo
    'logo_height'           => '44',

    // Przyciski
    'button_radius'         => '0',

    // WooCommerce — ukryj cart icon (katalog, nie sklep)
    'header_cart'           => '0',
    'header_account'        => '0',

    // Footer
    'footer_bar_text'       => '&copy; ' . date('Y') . ' Kreski Sp. Jawna. Wszelkie prawa zastrzeżone.',

    // Social
    'social_facebook'       => 'https://facebook.com/kreskipl',
    'social_instagram'      => 'https://instagram.com/kreskipl',
    'social_linkedin'       => 'https://linkedin.com/company/kreski',
    'social_youtube'        => 'https://youtube.com/@kreski',
];

foreach ( $mods as $key => $value ) {
    set_theme_mod( $key, $value );
    $results[] = "✅ set_theme_mod: $key";
}

// ── 2. MENU GŁÓWNE ──────────────────────────────────────────────

// Usuń stare menu jeśli istnieje
$existing = wp_get_nav_menu_object('Menu główne');
if ( $existing ) {
    wp_delete_nav_menu( $existing->term_id );
}

$menu_id = wp_create_nav_menu('Menu główne');

if ( ! is_wp_error($menu_id) ) {
    $results[] = "✅ Utworzono menu: Menu główne (ID: $menu_id)";

    // Produkty
    $prod = wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'   => 'Produkty',
        'menu-item-url'     => home_url('/produkty/'),
        'menu-item-status'  => 'publish',
        'menu-item-classes' => 'menu-item-has-children',
    ]);
    $results[] = "✅ Dodano: Produkty (ID: $prod)";

    // Usługi
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'  => 'Usługi',
        'menu-item-url'    => home_url('/uslugi/'),
        'menu-item-status' => 'publish',
    ]);

    // Serwis
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'  => 'Serwis',
        'menu-item-url'    => home_url('/serwis/'),
        'menu-item-status' => 'publish',
    ]);

    // Centrum wiedzy
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'  => 'Centrum wiedzy',
        'menu-item-url'    => home_url('/centrum-wiedzy/'),
        'menu-item-status' => 'publish',
    ]);

    // O firmie
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'  => 'O firmie',
        'menu-item-url'    => home_url('/o-firmie/'),
        'menu-item-status' => 'publish',
    ]);

    // Kontakt
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'  => 'Kontakt',
        'menu-item-url'    => home_url('/kontakt/'),
        'menu-item-status' => 'publish',
        'menu-item-classes' => 'menu-kontakt',
    ]);

    // Przypisz do lokalizacji primary
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $menu_id;
    $locations['handheld'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
    $results[] = "✅ Menu przypisane do primary i handheld";

} else {
    $results[] = "❌ Błąd tworzenia menu: " . $menu_id->get_error_message();
}

// ── 3. USTAWIENIA WORDPRESS ─────────────────────────────────────

update_option('blogname', 'Kreski');
update_option('blogdescription', 'Kompleksowe rozwiązania Auto ID i IT');
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();
$results[] = "✅ Ustawiono: blogname, permalink";

// ── 4. USUŃ SAM SIEBIE ──────────────────────────────────────────

$self = __FILE__;

// Wyświetl wyniki
echo '<style>body{font-family:monospace;padding:20px;background:#f5f5f5;} .ok{color:green;} .err{color:red;} h2{margin-top:20px;}</style>';
echo '<h1>✅ Kreski Setup — gotowe!</h1>';
echo '<h2>Wykonane operacje:</h2><ul>';
foreach ($results as $r) {
    $cls = strpos($r, '❌') !== false ? 'err' : 'ok';
    echo "<li class='$cls'>$r</li>";
}
echo '</ul>';
echo '<h2>Następny krok:</h2>';
echo '<p>Odśwież <a href="' . admin_url('customize.php') . '">Customizer</a> i sprawdź header.</p>';

// Auto-usuń plik
if ( unlink($self) ) {
    echo '<p style="color:orange;">⚠️ Plik setup.php został automatycznie usunięty.</p>';
} else {
    echo '<p style="color:red;">⚠️ Usuń ręcznie plik: wp-content/themes/flatsome-child/setup.php</p>';
}
