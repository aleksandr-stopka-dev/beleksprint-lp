<?php
defined( 'ABSPATH' ) || exit;

const THEME_THANKS_SLUG   = 'spasibo';
const THEME_THANKS_COOKIE = 'theme_thanks';
const THEME_THANKS_TTL    = 30 * MINUTE_IN_SECONDS;

function theme_thanks_url() {
    return home_url( '/' . THEME_THANKS_SLUG . '/' );
}

function theme_thanks_sign( $time ) {
    return hash_hmac( 'sha256', (string) $time, wp_salt( 'auth' ) );
}

function theme_thanks_issue() {
    $time = time();
    setcookie(
        THEME_THANKS_COOKIE,
        $time . '.' . theme_thanks_sign( $time ),
        [
            'expires'  => $time + THEME_THANKS_TTL,
            'path'     => COOKIEPATH,
            'secure'   => is_ssl(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]
    );
}

function theme_thanks_valid() {
    $cookie = isset( $_COOKIE[ THEME_THANKS_COOKIE ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ THEME_THANKS_COOKIE ] ) ) : '';
    if ( ! preg_match( '/^(\d{10})\.([a-f0-9]{64})$/', $cookie, $match ) ) {
        return false;
    }
    $age = time() - (int) $match[1];
    return $age >= 0 && $age <= THEME_THANKS_TTL && hash_equals( theme_thanks_sign( $match[1] ), $match[2] );
}

add_action( 'template_redirect', function() {
    if ( ! is_page( THEME_THANKS_SLUG ) ) {
        return;
    }

    nocache_headers();
    header( 'X-LiteSpeed-Cache-Control: no-cache' );
    if ( ! defined( 'DONOTCACHEPAGE' ) ) {
        define( 'DONOTCACHEPAGE', true );
    }

    if ( ! theme_thanks_valid() ) {
        wp_safe_redirect( home_url( '/' ), 302 );
        exit;
    }

    add_filter( 'wp_robots', 'wp_robots_no_robots' );
} );

add_filter( 'wp_sitemaps_posts_query_args', function( $args, $post_type ) {
    if ( 'page' !== $post_type ) {
        return $args;
    }
    $page = get_page_by_path( THEME_THANKS_SLUG );
    if ( $page ) {
        $args['post__not_in'] = array_merge( $args['post__not_in'] ?? [], [ $page->ID ] );
    }
    return $args;
}, 10, 2 );
