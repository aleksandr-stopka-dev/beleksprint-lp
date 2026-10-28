<?php
defined('ABSPATH') || exit;

add_action('init', function() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'wp_resource_hints', 2);
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

    remove_action('template_redirect', 'wp_shortlink_header', 11);
    remove_action('template_redirect', 'rest_output_link_header', 11);

    add_filter('the_generator', '__return_empty_string');
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('wp_img_auto_sizes_enabled', '__return_false');
    add_filter('wp_speculation_rules_configuration', '__return_null');
});

add_filter('wp_headers', function($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});

add_action('send_headers', function() {
    header_remove('X-Powered-By');
});

add_action('init', function() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
    remove_action('admin_enqueue_scripts', 'wp_enqueue_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    add_filter('tiny_mce_plugins', function($plugins) {
        return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
    });

    add_filter('emoji_svg_url', '__return_false');
});

add_action('init', function() {
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
    remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
    remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
    remove_action('in_admin_header', 'wp_global_styles_render_svg_filters');
});

add_action('wp_enqueue_scripts', function() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');

    wp_dequeue_style('wp-img-auto-sizes-contain');
    wp_dequeue_style('wp-img-auto-sizes-cover');

    wp_styles()->done[] = 'global-styles';
    wp_styles()->done[] = 'wp-img-auto-sizes-contain';
    wp_styles()->done[] = 'wp-img-auto-sizes-cover';

    wp_dequeue_style('contact-form-7');

    wp_dequeue_script('wp-embed');
}, 10000);

add_action('enqueue_block_assets', function() {
    if (!is_admin()) wp_dequeue_style('global-styles');
}, 10000);

add_action('wp_footer', function() {
    wp_dequeue_style('core-block-supports');
});

add_action('wp_enqueue_scripts', function() {
    wp_deregister_script('heartbeat');

    wp_deregister_script('jquery');
    wp_deregister_script('jquery-core');
    wp_deregister_script('jquery-migrate');
}, 1);

add_filter('heartbeat_settings', function($settings) {
    $settings['interval'] = 60;
    return $settings;
});

add_action('wp_default_scripts', function($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});

add_filter('wp_revisions_to_keep', function($num, $post) {
    return 3;
}, 10, 2);

function theme_remove_wp_ver_str($src) {
    if (strpos($src, 'ver=' . get_bloginfo('version'))) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'theme_remove_wp_ver_str', 15);
add_filter('style_loader_src', 'theme_remove_wp_ver_str', 15);

add_filter('get_avatar', '__return_false');

add_action('pre_ping', function(&$links) {
    $home = get_option('home');
    foreach ($links as $l => $link) {
        if (0 === strpos($link, $home)) unset($links[$l]);
    }
});

add_filter('wpcf7_autop_or_not', '__return_false');

add_action('init', function() {
    foreach (['post', 'page'] as $type) {
        remove_post_type_support($type, 'comments');
        remove_post_type_support($type, 'trackbacks');
    }
});

add_filter('comments_open', '__return_false', 20);
add_filter('pings_open', '__return_false', 20);
add_filter('comments_array', '__return_empty_array', 10);

if ( is_admin() ) {
    add_filter('acf/settings/remove_wp_meta', '__return_true');

    remove_action('welcome_panel', 'wp_welcome_panel');

    add_filter('use_block_editor_for_post', '__return_false', 100);
    add_filter('use_block_editor_for_post_type', '__return_false', 100);
    add_filter('use_widgets_block_editor', '__return_false');

    add_action('admin_menu', function() {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('options-general.php', 'options-discussion.php');
    });

    add_action('admin_init', function() {
        global $pagenow;
        if ($pagenow === 'edit-comments.php') {
            wp_safe_redirect(admin_url());
            exit;
        }
    });

    add_filter('update_footer', '__return_empty_string', 11);
}
