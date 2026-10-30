<?php
defined('ABSPATH') || exit;

add_action( 'wp_enqueue_scripts', function() {
    $theme_uri = get_template_directory_uri();

    $style_file = '/assets/css/style.min.css';
    wp_enqueue_style( 'main-style', $theme_uri . $style_file, [], theme_asset_ver( $style_file ), 'all' );

    $js_file = '/assets/js/app.js';
    wp_enqueue_script_module( 'main-app', $theme_uri . $js_file, [], theme_asset_ver( $js_file ) );
});

add_action( 'wp_head', function() {
    $theme_uri = get_template_directory_uri();
    $fonts = [
        '/assets/fonts/Montserrat/montserrat-cyrillic.woff2',
        '/assets/fonts/OpenSans/open-sans-cyrillic-wght-normal.woff2',
    ];
    foreach ( $fonts as $font ) {
        printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( $theme_uri . $font ) );
    }
}, 1 );

add_action( 'wp_head', function() {
    $dir   = get_template_directory() . '/assets/js';
    $uri   = get_template_directory_uri() . '/assets/js';
    $imports = [];
    foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir, FilesystemIterator::SKIP_DOTS ) ) as $file ) {
        if ( 'js' !== $file->getExtension() ) continue;
        $relative = str_replace( '\\', '/', substr( $file->getPathname(), strlen( $dir ) ) );
        $imports[ $uri . $relative ] = $uri . $relative . '?ver=' . $file->getMTime();
    }
    printf( '<script type="importmap">%s</script>' . "\n", wp_json_encode( [ 'imports' => $imports ], JSON_UNESCAPED_SLASHES ) );
}, 1 );
