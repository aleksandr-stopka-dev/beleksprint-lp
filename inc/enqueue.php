<?php
defined('ABSPATH') || exit;

add_action( 'wp_enqueue_scripts', function() {
    $theme_uri = get_template_directory_uri();

    $style_file = '/assets/css/style.min.css';
    wp_enqueue_style( 'main-style', $theme_uri . $style_file, [], theme_asset_ver( $style_file ), 'all' );

    $js_file = '/assets/js/app.js';
    wp_enqueue_script( 'main-app', $theme_uri . $js_file, [], theme_asset_ver( $js_file ), [ 'in_footer' => true, 'strategy' => 'defer' ] );
});

// Fonts are self-hosted, one variable file per subset (unicode-range): preload the ones the first screen uses.
add_action( 'wp_head', function() {
    $theme_uri = get_template_directory_uri();
    foreach ( [ 'montserrat-cyrillic', 'montserrat-latin' ] as $font ) {
        printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $theme_uri . '/assets/fonts/Montserrat/' . $font . '.woff2' ) );
    }
}, 1 );
