<?php
defined('ABSPATH') || exit;

if ( ! defined('DISALLOW_FILE_EDIT') ) define('DISALLOW_FILE_EDIT', true);

add_action('send_headers', function() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
    if ( is_ssl() ) header('Strict-Transport-Security: max-age=31536000');
});

add_action('template_redirect', function() {
    if ( is_author() || isset($_GET['author']) ) {
        wp_safe_redirect(home_url('/'), 301);
        exit;
    }
});

add_filter('rest_endpoints', function($endpoints) {
    if ( ! is_user_logged_in() ) {
        foreach ( array_keys($endpoints) as $route ) {
            if ( preg_match('#^/wp/v2/users#', $route) ) unset($endpoints[$route]);
        }
    }
    return $endpoints;
});

add_filter('wp_sitemaps_add_provider', function($provider, $name) {
    return $name === 'users' ? false : $provider;
}, 10, 2);

add_filter('oembed_response_data', function($data) {
    unset($data['author_name'], $data['author_url']);
    return $data;
});

add_filter('login_errors', function() {
    return 'Неверный логин или пароль.';
});

add_filter('xmlrpc_methods', '__return_empty_array');
add_filter('wp_is_application_passwords_available', '__return_false');
