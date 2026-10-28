<?php
defined('ABSPATH') || exit;

add_action('acf/init', function() {
    if ( function_exists('acf_add_options_page') ) {
        acf_add_options_page([
            'page_title' => 'Настройки сайта',
            'menu_title' => 'Настройки сайта',
            'menu_slug'  => 'site-options',
            'capability' => 'manage_options',
            'redirect'   => false
        ]);
    }
});
