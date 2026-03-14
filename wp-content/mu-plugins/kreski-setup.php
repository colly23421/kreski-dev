<?php
/**
 * Kreski Setup — runs once automatically
 */

if ( ! defined('ABSPATH') ) exit;

// Uruchom tylko raz
if ( get_option('kreski_setup_done') ) return;

add_action('init', function() {
    if ( get_option('kreski_setup_done') ) return;

    // ── THEME MODS ──
    $mods = [
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
    ];

    foreach ( $mods as $key => $value ) {
        set_theme_mod( $key, $value );
    }

    // ── MENU ──
    $existing = wp_get_nav_menu_object('Menu główne');
    if ( $existing ) wp_delete_nav_menu( $existing->term_id );

    $menu_id = wp_create_nav_menu('Menu główne');

    if ( ! is_wp_error($menu_id) ) {
        $items = [
            ['Produkty',        '/produkty/'],
            ['Usługi',          '/uslugi/'],
            ['Serwis',          '/serwis/'],
            ['Centrum wiedzy',  '/centrum-wiedzy/'],
            ['O firmie',        '/o-firmie/'],
            ['Kontakt',         '/kontakt/'],
        ];
        foreach ( $items as $item ) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title'  => $item[0],
                'menu-item-url'    => home_url($item[1]),
                'menu-item-status' => 'publish',
            ]);
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['primary']  = $menu_id;
        $locations['handheld'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // ── WP OPTIONS ──
    update_option('blogname', 'Kreski');
    update_option('blogdescription', 'Kompleksowe rozwiązania Auto ID i IT');
    update_option('permalink_structure', '/%postname%/');
    flush_rewrite_rules();

    // Oznacz jako wykonane
    update_option('kreski_setup_done', '1');
}, 20);
// Sat Mar 14 23:52:15 UTC 2026
