<?php
defined('ABSPATH') || exit;

add_action('wp_dashboard_setup', function() {
    global $wp_meta_boxes;
    $wp_meta_boxes['dashboard'] = [];
}, 999);
