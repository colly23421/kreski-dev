<?php
/**
 * Flatsome Child — Kreski
 */

add_action( 'wp_enqueue_scripts', 'flatsome_child_enqueue_styles' );
function flatsome_child_enqueue_styles() {
    wp_enqueue_style( 'flatsome-parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'flatsome-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'flatsome-parent-style' ), filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_style( 'kreski-fonts', 'https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Barlow+Condensed:wght@600;700;800&display=swap', array(), null );
}

add_action( 'wp_head', 'kreski_preconnect_fonts', 1 );
function kreski_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

// Jednorazowy setup — uruchamia się tylko raz
add_action( 'init', 'kreski_one_time_setup', 20 );
function kreski_one_time_setup() {
    if ( get_option( 'kreski_setup_v3' ) ) return;

    // Flatsome theme mods
    $mods = array(
        'top_bar'              => '1',
        'top_bar_text'         => '<a href="tel:+48226662240">&#9990; 22 666 22 40</a> &nbsp;&bull;&nbsp; <a href="mailto:kreski@kreski.pl">&#9993; kreski@kreski.pl</a>',
        'top_bar_right'        => 'social',
        'header_height'        => '74',
        'header_sticky'        => '1',
        'header_sticky_shrink' => '1',
        'header_search'        => '1',
        'header_cart'          => '0',
        'header_account'       => '0',
        'button_radius'        => '0',
        'social_facebook'      => 'https://www.facebook.com/kreskipl',
        'social_instagram'     => 'https://www.instagram.com/kreskipl',
        'social_linkedin'      => 'https://www.linkedin.com/company/kreski',
        'social_youtube'       => 'https://www.youtube.com/@kreski',
    );
    foreach ( $mods as $key => $value ) {
        set_theme_mod( $key, $value );
    }

    // Menu główne
    $existing = wp_get_nav_menu_object( 'Menu główne' );
    if ( $existing ) wp_delete_nav_menu( $existing->term_id );

    $menu_id = wp_create_nav_menu( 'Menu główne' );
    if ( ! is_wp_error( $menu_id ) ) {
        $items = array(
            array( 'Produkty',       '/produkty/' ),
            array( 'Usługi',         '/uslugi/' ),
            array( 'Serwis',         '/serwis/' ),
            array( 'Centrum wiedzy', '/centrum-wiedzy/' ),
            array( 'O firmie',       '/o-firmie/' ),
            array( 'Kontakt',        '/kontakt/' ),
        );
        foreach ( $items as $item ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'  => $item[0],
                'menu-item-url'    => home_url( $item[1] ),
                'menu-item-status' => 'publish',
            ) );
        }
        $locations             = get_theme_mod( 'nav_menu_locations', array() );
        $locations['primary']  = $menu_id;
        $locations['handheld'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    // WP options
    update_option( 'blogname', 'Kreski' );
    update_option( 'blogdescription', 'Kompleksowe rozwiązania Auto ID i IT' );
    update_option( 'permalink_structure', '/%postname%/' );
    flush_rewrite_rules();

    update_option( 'kreski_setup_v3', '1' );
}
