<?php
defined( 'ABSPATH' ) || exit;

function theme_schema_business() {
    $url     = home_url( '/' );
    $address = (string) theme_opt( 'address' );
    $hours   = (string) theme_opt( 'hours' );
    $logo    = theme_opt( 'logo' );
    $image   = theme_seo_image();

    $business = [
        '@type' => 'LocalBusiness',
        '@id'   => $url . '#organization',
        'name'  => get_bloginfo( 'name' ),
        'url'   => $url,
    ];

    $phone = theme_opt( 'phone' );
    $email = theme_opt( 'email' );
    if ( $phone ) $business['telephone'] = preg_replace( '/[^\d+]/', '', $phone );
    if ( $email ) $business['email'] = $email;

    if ( $address ) {
        $locality = preg_match( '/г\.\s*([^,]+)/u', $address, $match ) ? trim( $match[1] ) : '';
        $street   = trim( preg_replace( '/^г\.\s*[^,]+,?\s*/u', '', $address ) );
        $business['address'] = array_filter( [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $street ?: $address,
            'addressLocality' => $locality,
            'addressCountry'  => 'BY',
        ] );
    }

    if ( preg_match( '/(\d{1,2}:\d{2})\D+(\d{1,2}:\d{2})/u', $hours, $match ) ) {
        $business['openingHoursSpecification'] = [
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ],
            'opens'     => $match[1],
            'closes'    => $match[2],
        ];
    }

    if ( is_array( $logo ) && ! empty( $logo['url'] ) ) {
        $business['logo'] = $logo['url'];
    }
    if ( $image ) $business['image'] = $image['url'];

    $same_as = array_values( array_filter( array_map( 'theme_opt', [ 'telegram' ] ) ) );
    if ( $same_as ) $business['sameAs'] = $same_as;

    return $business;
}

function theme_schema_catalog() {
    $items = get_field( 'catalog_items' ) ?: [];
    $list  = [];
    foreach ( $items as $item ) {
        if ( empty( $item['title'] ) ) continue;
        $list[] = [
            '@type'    => 'ListItem',
            'position' => count( $list ) + 1,
            'item'     => array_filter( [
                '@type'       => 'Product',
                'name'        => $item['title'],
                'description' => $item['description'] ?? '',
                'image'       => is_numeric( $item['image'] ?? null ) ? wp_get_attachment_url( $item['image'] ) : ( $item['image']['url'] ?? '' ),
                'brand'       => [ '@type' => 'Brand', 'name' => get_bloginfo( 'name' ) ],
            ] ),
        ];
    }
    return $list ? [
        '@type'           => 'ItemList',
        'name'            => wp_strip_all_tags( str_replace( '*', '', (string) get_field( 'catalog_title' ) ) ),
        'itemListElement' => $list,
    ] : null;
}

add_action( 'wp_head', function() {
    if ( is_404() ) return;

    $home  = home_url( '/' );
    $graph = [
        theme_schema_business(),
        [
            '@type'     => 'WebSite',
            '@id'       => $home . '#website',
            'url'       => $home,
            'name'      => get_bloginfo( 'name' ),
            'inLanguage' => 'ru-RU',
            'publisher' => [ '@id' => $home . '#organization' ],
        ],
    ];

    if ( is_singular() ) {
        $page = [
            '@type'       => 'WebPage',
            '@id'         => get_permalink() . '#webpage',
            'url'         => get_permalink(),
            'name'        => wp_get_document_title(),
            'isPartOf'    => [ '@id' => $home . '#website' ],
            'inLanguage'  => 'ru-RU',
            'description' => theme_seo_description(),
        ];
        $graph[] = array_filter( $page );

        if ( ! is_front_page() ) {
            $graph[] = [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    [ '@type' => 'ListItem', 'position' => 1, 'name' => 'Главная', 'item' => $home ],
                    [ '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => get_permalink() ],
                ],
            ];
        } else {
            $catalog = theme_schema_catalog();
            if ( $catalog ) $graph[] = $catalog;
        }
    }

    echo '<script type="application/ld+json">' . wp_json_encode( [ '@context' => 'https://schema.org', '@graph' => $graph ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}, 6 );
