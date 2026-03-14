<?php
/**
 * Flatsome Child — Kreski
 * functions.php
 */

// Załaduj style rodzica (Flatsome) + child theme
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
}
