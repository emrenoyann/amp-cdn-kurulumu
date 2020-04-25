<?php

function _ampforwp_add_custom_rewrite_rules() {
    global $redux_builder_amp, $wp_rewrite;
    add_rewrite_rule(
        'amp/?$',
        'index.php?amp',
        'top'
    );
    add_rewrite_rule(
        'amp/'.$wp_rewrite->pagination_base.'/([0-9]{1,})/?$',
        'index.php?amp=1&paged=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        'index.php/amp/'.$wp_rewrite->pagination_base.'/([0-9]{1,})/?$',
        'index.php?amp=1&paged=$matches[1]',
        'top'
    );
    if( ampforwp_name_blog_page() ) {
        add_rewrite_rule(
            ampforwp_name_blog_page(). '/amp/'.$wp_rewrite->pagination_base.'/([0-9]{1,})/?$',
            'index.php?amp=1&paged=$matches[1]&page_id=' .ampforwp_get_the_page_id_blog_page(),
            'top'
        );
        add_rewrite_rule(
            ampforwp_name_blog_page(). '(.+?)/amp/'.$wp_rewrite->pagination_base.'/([0-9]{1,})/?$',
            'index.php?amp=1&paged=$matches[2]&page_id=' .ampforwp_get_the_page_id_blog_page(),
            'top'
        );
    }

    // For Author pages
    add_rewrite_rule(
        'author\/([^/]+)\/amp\/?$',
        'index.php?amp=1&author_name=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        'author\/([^/]+)\/amp\/'.$wp_rewrite->pagination_base.'\/?([0-9]{1,})\/?$',
        'index.php?amp=1&author_name=$matches[1]&paged=$matches[2]',
        'top'
    );

    $rewrite_category = '';
    $rewrite_category = get_transient('ampforwp_category_base');

    if ( false == $rewrite_category ) {
        $rewrite_category = get_option('category_base');
        if (  empty($rewrite_category) ) {
            $rewrite_category = 'category';
        }
        set_transient('ampforwp_category_base', $rewrite_category);
    }

    add_rewrite_rule(
        $rewrite_category.'\/(.+?)\/amp/?$',
        'index.php?amp=1&category_name=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        $rewrite_category.'/(.+?)\/amp\/'.$wp_rewrite->pagination_base.'\/?([0-9]{1,})\/?$',
        'index.php?amp=1&category_name=$matches[1]&paged=$matches[2]',
        'top'
    );

    $permalink_structure = '';
    $permalink_structure = get_transient('ampforwp_permalink_structure');
    if ( false == $permalink_structure ) {
        $permalink_structure = get_option('permalink_structure');
        set_transient('ampforwp_permalink_structure', $permalink_structure );
    }
    $permalink_structure = preg_replace('/(%.*%)/', '', $permalink_structure);
    $permalink_structure = preg_replace('/\//', '', $permalink_structure);
    if ( $permalink_structure ) {
        add_rewrite_rule(
            $permalink_structure.'\/'.$rewrite_category.'\/(.+?)\/amp\/'.$wp_rewrite->pagination_base.'\/?([0-9]{1,})\/?$',
            'index.php?amp=1&category_name=$matches[1]&paged=$matches[2]',
            'top'
        );
    }

    $rewrite_tag = '';
    $rewrite_tag = get_transient('ampforwp_tag_base');
    if ( false == $rewrite_tag ) {
        $rewrite_tag = get_option('tag_base');
        if ( empty($rewrite_tag) ) {
            $rewrite_tag = 'tag';
        }
        set_transient('ampforwp_tag_base',$rewrite_tag);
    }

    add_rewrite_rule(
        $rewrite_tag.'\/(.+?)\/amp/?$',
        'index.php?amp=1&tag=$matches[1]',
        'top'
    );
    add_rewrite_rule(
        $rewrite_tag.'\/(.+?)\/amp\/'.$wp_rewrite->pagination_base.'\/?([0-9]{1,})\/?$',
        'index.php?amp=1&tag=$matches[1]&paged=$matches[2]',
        'top'
    );

    if ( $permalink_structure ) {
        add_rewrite_rule(
            $permalink_structure.'\/'.$rewrite_tag.'\/(.+?)\/amp\/'.$wp_rewrite->pagination_base.'\/?([0-9]{1,})\/?$',
            'index.php?amp=1&tag=$matches[1]&paged=$matches[2]',
            'top'
        );
    }
    add_rewrite_rule(
        '([0-9]{4})/([0-9]{1,2})\/amp\/?$',
        'index.php?year=$matches[1]&monthnum=$matches[2]&amp=1',
        'top'
    );
    add_rewrite_rule(
        '([0-9]{4})/([0-9]{1,2})/amp/'.$wp_rewrite->pagination_base.'/?([0-9]{1,})/?$',
        'index.php?year=$matches[1]&monthnum=$matches[2]&amp=1&paged=$matches[3]',
        'top'
    );
    $taxonomies = array();
    if( function_exists('ampforwp_generate_taxonomies_transient')){
        $taxonomies = ampforwp_generate_taxonomies_transient();
    }

    if(!function_exists('amp_woocommerce_pro_add_woocommerce_support') ) {
        if( class_exists( 'WooCommerce' ) ) {
            $wc_permalinks = '';
            $wc_permalinks = get_transient('ampforwp_woocommerce_permalinks');
            if( false == $wc_permalinks ) {
                $wc_permalinks 	= get_option( 'woocommerce_permalinks' );
                set_transient('ampforwp_woocommerce_permalinks', $wc_permalinks);
            }
            if ( $wc_permalinks && !empty( $taxonomies) ) {
                $taxonomies = array_merge($taxonomies, $wc_permalinks);
            }
        }
    }
    $post_types = ampforwp_get_all_post_types();
    if ( $post_types ) {
        foreach ($post_types as $post_type ) {
            if ( 'post' !=  $post_type && 'page' != $post_type ){
                add_rewrite_rule(
                    $post_type.'\/amp/?$',
                    'index.php?amp&post_type='.$post_type,
                    'top'
                );
                add_rewrite_rule(
                    $post_type.'\/(.+?)\/amp\/?$',
                    'index.php?amp&'.$post_type.'=$matches[1]',
                    'top'
                );
                add_rewrite_rule(
                    $post_type.'\/(.+?)\/amp\/?$',
                    'index.php?amp&'.$post_type.'=$matches[1]',
                    'top'
                );
                add_rewrite_rule(
                    $post_type.'\/amp/'.$wp_rewrite->pagination_base.'/([0-9]{1,})/?$',
                    'index.php?amp=1&post_type='.$post_type.'&paged=$matches[1]',
                    'top'
                );
            }
        }
    }

}
$taxonomies = '';
if ( $taxonomies ) {
    $taxonomySlug = '';
    foreach ( $taxonomies  as  $taxonomyName => $taxonomyLabel ) {
        $taxonomies = get_taxonomy( $taxonomyName );
        if(isset($taxonomies->rewrite['slug']) && !empty($taxonomies->rewrite['slug']) ){
            $taxonomySlug = $taxonomies->rewrite['slug'];
        }else{
            $taxonomySlug = $taxonomyName;
        }
        if ( ! empty( $taxonomySlug ) ) {
            add_rewrite_rule(
                $taxonomySlug.'\/([^/]+)\/amp/?$',
                'index.php?amp&'.$taxonomyName.'=$matches[1]',
                'top'
            );
        }
    }
}

function force(){
    return '<div class="notice notice-warning is-dismissible">
                 <p>Kullandığınız AMP CDN Kurulumu eklentisinin 7 günlük deneme süresi <b>sona erdi.</b> Satın almak için <a target="_blank" href="https://emrenogay.com/iletisim/">iletişime</a> geçin.</p>
             </div>';
};