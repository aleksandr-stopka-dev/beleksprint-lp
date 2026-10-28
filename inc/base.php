<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style'
    ]);

    register_nav_menus([
        'header_menu' => 'Header menu',
        'footer_menu' => 'Footer menu',
    ]);
});

add_filter('show_admin_bar', '__return_false');

add_filter('big_image_size_threshold', '__return_false');

add_filter('wp_editor_set_quality', function() {
    return 92;
});

add_filter('nav_menu_item_id', '__return_false');

add_filter('nav_menu_css_class', function($classes, $item, $args) {
    $important_classes = [
        'current-menu-item',
        'current-menu-ancestor',
        'menu-item-has-children'
    ];

    $classes = array_intersect($classes, $important_classes);

    if (theme_is_anchor_item($item)) {
        $classes = array_diff($classes, ['current-menu-item', 'current-menu-ancestor']);
    }

    return $classes;
}, 10, 3);
