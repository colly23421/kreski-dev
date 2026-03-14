<?php
/**
 * Flatsome Child — Kreski
 */

add_action( 'wp_enqueue_scripts', 'flatsome_child_enqueue_styles' );
function flatsome_child_enqueue_styles() {
    wp_enqueue_style(
        'flatsome-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'flatsome-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'flatsome-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
    wp_enqueue_style(
        'kreski-fonts',
        'https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Barlow+Condensed:wght@600;700;800&display=swap',
        array(),
        null
    );
}

// Preconnect Google Fonts dla wydajności
add_action( 'wp_head', 'kreski_preconnect_fonts', 1 );
function kreski_preconnect_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
