<?php
defined( 'ABSPATH' ) || exit;

function theme_seo_description() {
    if ( is_front_page() ) {
        return (string) theme_opt( 'seo_description' );
    }
    if ( is_singular() ) {
        $text = get_the_excerpt() ?: wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content' ) ) );
        $text = trim( preg_replace( '/\s+/u', ' ', $text ) );
        if ( $text ) {
            return mb_strlen( $text ) > 160 ? rtrim( mb_substr( $text, 0, 157 ) ) . '…' : $text;
        }
    }
    return (string) theme_opt( 'seo_description' );
}

function theme_seo_image() {
    $image = theme_opt( 'og_image' );
    return is_array( $image ) && ! empty( $image['url'] ) ? $image : null;
}

add_filter( 'pre_get_document_title', function( $title ) {
    $custom = is_front_page() ? theme_opt( 'seo_title' ) : '';
    return $custom ?: $title;
} );

add_filter( 'document_title_separator', function() {
    return '—';
} );

add_action( 'wp_head', function() {
    if ( is_404() ) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
        return;
    }

    $description = theme_seo_description();
    $title       = wp_get_document_title();
    $url         = is_singular() ? get_permalink() : home_url( '/' );
    $image       = theme_seo_image();

    if ( $description ) {
        printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
    }

    $tags = [
        'og:locale'    => 'ru_RU',
        'og:type'      => is_front_page() ? 'website' : 'article',
        'og:site_name' => get_bloginfo( 'name' ),
        'og:title'     => $title,
        'og:url'       => $url,
    ];
    if ( $description ) $tags['og:description'] = $description;
    if ( $image ) {
        $tags['og:image']        = $image['url'];
        $tags['og:image:width']  = $image['width'];
        $tags['og:image:height'] = $image['height'];
    }
    foreach ( $tags as $property => $content ) {
        printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
    }

    echo '<meta name="twitter:card" content="' . ( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
}, 5 );

add_filter( 'robots_txt', function( $output, $public ) {
    if ( ! $public ) return $output;
    return "User-agent: *\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\n\nSitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
}, 10, 2 );

add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
    return in_array( $name, [ 'users', 'taxonomies' ], true ) ? false : $provider;
}, 10, 2 );
