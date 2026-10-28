<?php
defined('ABSPATH') || exit;

function theme_is_anchor_item($item) {
    return isset($item->url) && strpos($item->url, '#') !== false;
}

function theme_opt($name, $default = '') {
    if (!function_exists('get_field')) return $default;
    $value = get_field($name, 'options');
    return ($value === null || $value === '' || $value === false) ? $default : $value;
}

function theme_asset_ver($file) {
    $path = get_template_directory() . $file;
    return file_exists($path) ? filemtime($path) : null;
}

function theme_srcset($img) {
    $srcset = [];
    foreach (['thumbnail', 'medium', 'medium_large', 'large', '1536x1536', '2048x2048'] as $size) {
        $w = $img['sizes'][$size . '-width'] ?? 0;
        $u = $img['sizes'][$size] ?? '';
        if ($w && $u) $srcset[(int) $w] = $u . ' ' . (int) $w . 'w';
    }
    if (!empty($img['width'])) $srcset[(int) $img['width']] = $img['url'] . ' ' . (int) $img['width'] . 'w';
    ksort($srcset);
    return count($srcset) > 1 ? implode(', ', $srcset) : '';
}

/**
 * <img> from an ACF image array (return format: Image Array).
 * Always outputs width/height (no CLS), srcset for rasters, plain <img> for SVG.
 * $lazy = false marks the LCP image: eager + fetchpriority=high.
 */
function theme_image($img, $class = '', $sizes = '100vw', $lazy = true) {
    if (empty($img) || !is_array($img) || empty($img['url'])) return '';

    $attrs = [
        'src'      => esc_url($img['url']),
        'alt'      => esc_attr($img['alt'] ?? ''),
        'width'    => (int) ($img['width'] ?? 0),
        'height'   => (int) ($img['height'] ?? 0),
        'decoding' => 'async',
    ];
    if (!$attrs['width'])  unset($attrs['width']);
    if (!$attrs['height']) unset($attrs['height']);
    if ($class) $attrs['class'] = esc_attr($class);

    $is_svg = strtolower(pathinfo(wp_parse_url($img['url'], PHP_URL_PATH) ?: '', PATHINFO_EXTENSION)) === 'svg';
    $srcset = $is_svg ? '' : theme_srcset($img);
    if ($srcset) {
        $attrs['srcset'] = esc_attr($srcset);
        $attrs['sizes']  = esc_attr($sizes);
    }

    if ($lazy) {
        $attrs['loading'] = 'lazy';
    } else {
        $attrs['loading']       = 'eager';
        $attrs['fetchpriority'] = 'high';
    }

    $html = '';
    foreach ($attrs as $name => $value) $html .= ' ' . $name . '="' . $value . '"';
    return '<img' . $html . '>';
}

function theme_thumb($post_id, $class = '', $sizes = '100vw', $lazy = true) {
    $id = get_post_thumbnail_id($post_id);
    if (!$id || !function_exists('acf_get_attachment')) return '';
    return theme_image(acf_get_attachment($id), $class, $sizes, $lazy);
}

/** Back-compat wrapper: $size_attr like '800px' is used as the sizes attribute. */
function get_wp_srcset_img($img, $class = '', $size_attr = '800px', $is_lazy = true) {
    return theme_image($img, $class, $size_attr, $is_lazy);
}

function theme_link_parts($link) {
    if (empty($link) || !is_array($link) || empty($link['url'])) return null;
    return [
        'url'    => $link['url'],
        'title'  => $link['title'] ?? '',
        'target' => !empty($link['target']) ? $link['target'] : '_self',
    ];
}

function theme_logo($field = 'logo', $class = '') {
    $img = theme_opt($field);
    if (!is_array($img) || empty($img['url'])) return '';
    return theme_image($img, $class, '200px', false);
}
