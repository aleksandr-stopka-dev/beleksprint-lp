<?php
defined('ABSPATH') || exit;

add_filter('upload_mimes', function($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'svg' || !current_user_can('manage_options')) {
        return $data;
    }

    $filetype = wp_check_filetype($filename, $mimes);
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
}, 10, 4);

function theme_svg_is_safe($svg) {
    if (!mb_check_encoding($svg, 'UTF-8') || strpos($svg, "\0") !== false) return false;
    if (!preg_match('/<svg[\s>]/i', $svg)) return false;

    // Browsers decode entities in attribute values (jav&#x61;script:) - check what they will actually see.
    $decoded = $svg;
    for ($i = 0; $i < 3; $i++) {
        $decoded = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    // Tabs/newlines inside URLs are stripped by the browser (java\tscript:).
    $squashed = preg_replace('/[\x00-\x20\x{00A0}]+/u', '', $decoded);

    $forbidden = [
        '/<script/i',
        '/<\?php/i',
        '/<!ENTITY/i',
        '/<!DOCTYPE/i',
        '/<iframe/i',
        '/<object/i',
        '/<embed/i',
        '/<foreignObject/i',
        '/<(animate|set)\b[^>]*attributeName\s*=\s*["\']?(href|xlink:href|on)/i',
        '/\bon[a-z]+\s*=/i',
        '/javascript:/i',
        '/vbscript:/i',
        '/data:text\/html/i',
        '/data:image\/svg/i',
        '/(xlink:)?href\s*=\s*["\']\s*(https?:)?\/\//i',
        '/url\(\s*["\']?\s*(https?:)?\/\//i',
        '/@import/i',
        '/expression\s*\(/i',
    ];

    foreach ($forbidden as $pattern) {
        if (preg_match($pattern, $decoded)) return false;
    }
    foreach (['/javascript:/i', '/vbscript:/i', '/data:text\/html/i', '/data:image\/svg/i'] as $pattern) {
        if (preg_match($pattern, $squashed)) return false;
    }
    return true;
}

add_filter('wp_handle_upload_prefilter', function($file) {
    $is_svg = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION)) === 'svg';
    if (!$is_svg || !empty($file['error'])) return $file;

    $size = (int) @filesize($file['tmp_name']);
    $svg  = $size > 0 && $size <= 2 * MB_IN_BYTES ? file_get_contents($file['tmp_name']) : false;

    if ($svg === false || !theme_svg_is_safe($svg)) {
        $file['error'] = 'Security: The SVG file is too large, malformed or contains suspicious code and has been rejected.';
    }
    return $file;
});

// WP can't read SVG dimensions: store them from width/height/viewBox so ACF and srcset helpers get real values.
add_filter('wp_generate_attachment_metadata', function($metadata, $attachment_id) {
    if (get_post_mime_type($attachment_id) !== 'image/svg+xml') return $metadata;

    $path = get_attached_file($attachment_id);
    $svg  = $path && file_exists($path) ? file_get_contents($path) : '';
    if (!$svg || !preg_match('/<svg\b[^>]*>/i', $svg, $tag)) return $metadata;

    $w = $h = 0;
    if (preg_match('/\sviewBox\s*=\s*["\']\s*[-\d.]+[\s,]+[-\d.]+[\s,]+([\d.]+)[\s,]+([\d.]+)/i', $tag[0], $m)) {
        $w = (float) $m[1];
        $h = (float) $m[2];
    }
    if (preg_match('/\swidth\s*=\s*["\']\s*([\d.]+)\s*(px)?\s*["\']/i', $tag[0], $m))  $w = (float) $m[1];
    if (preg_match('/\sheight\s*=\s*["\']\s*([\d.]+)\s*(px)?\s*["\']/i', $tag[0], $m)) $h = (float) $m[1];

    if ($w > 0 && $h > 0) {
        $metadata['width']  = (int) round($w);
        $metadata['height'] = (int) round($h);
        $metadata['file']   = _wp_relative_upload_path($path);
    }
    return $metadata;
}, 10, 2);

add_filter('manage_media_columns', function($columns) {
    $columns['svg_preview'] = 'SVG Preview';
    return $columns;
});

add_action('manage_media_custom_column', function($column_name, $id) {
    if ($column_name === 'svg_preview') {
        $url = wp_get_attachment_url($id);
        if ($url && strtolower(pathinfo(wp_parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION)) === 'svg') {
            echo '<img src="' . esc_url($url) . '" alt="" style="width:40px; height:40px; background:#f0f0f0; border:1px solid #ddd; object-fit:contain; padding:2px;">';
        }
    }
}, 10, 2);

add_action('admin_head', function() {
    echo '<style>
        .attachment-266x266, .thumbnail img[src$=".svg"], .media-icon img[src$=".svg"] {
            width: 100% !important;
            height: auto !important;
        }
        .column-svg_preview img { max-width: 100%; }
    </style>';
});
