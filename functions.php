<?php
defined('ABSPATH') || exit;

foreach ( [ 'helpers', 'cleanup', 'security', 'enqueue', 'base', 'svg-fix', 'acf', 'acf-add-options', 'admin', 'forms', 'seo', 'schema' ] as $module ) {
    require_once get_template_directory() . '/inc/' . $module . '.php';
}
