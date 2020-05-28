<?php

if (empty(get_option('cube_dom'))) {
    update_option('cube_dom', $_SERVER['HTTP_HOST']);
}

function handler_request()
{
    global $redux_builder_amp;
    $number_of_articles = $exclude_ids = '';
    $exclude_cats = array();
    if (isset($redux_builder_amp['ampforwp-fb-instant-article-posts']) && $redux_builder_amp['ampforwp-fb-instant-article-posts']) {
        $number_of_articles = $redux_builder_amp['ampforwp-fb-instant-article-posts'];
        $number_of_articles = intval($number_of_articles);
    } else {
        $number_of_articles = 500;
    }

    $ia_args = array(
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page' => esc_attr($number_of_articles),
        'no_found_rows' => true,
        'meta_query' => array(
            array(
                'value' => 'hide-ia',
                'compare' => "!="
            ),
        )
    );
    if (ampforwp_get_setting('hide-amp-ia-categories')) {
        $exclude_cats = array_values(array_filter(ampforwp_get_setting('hide-amp-ia-categories')));
        $ia_args['category__not_in'] = $exclude_cats;
    }
    if (is_category()) {
        $ia_args['category__in'] = get_queried_object_id();
    }
    if (is_tag()) {
        $ia_args['tag__in'] = get_queried_object_id();
    }
    if (is_tax()) {
        $tax_object = get_queried_object();
        $ia_args['post_type'] = get_post_type();
        $ia_args['tax_query']['taxonomy'] = esc_attr($tax_object->taxonomy);
        $ia_args['tax_query']['field'] = 'id';
        $ia_args['tax_query']['terms'] = esc_attr($tax_object->term_id);
    }


    global $redux_builder_amp;

    if (is_home() && $redux_builder_amp['amp-frontpage-select-option'] === 0) {
        return null;
    }

    global $post;
    $amp_current_post_id = get_the_ID();
    if (ampforwp_is_front_page() && ampforwp_get_frontpage_id()) {
        //Custom AMP Editor Support for WPML  #1138
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_plugin_active('sitepress-multilingual-cms/sitepress.php')) {
            $amp_current_post_id = get_option('page_on_front');

        } else {
            $amp_current_post_id = ampforwp_get_frontpage_id();
        }
    }

}

function ampforwp_get_setting_handler()
{
    if (get_option('child_cdn_option') == '0') {
        return strrev('emened');
    } else if (get_option('child_cdn_option') == '2') {
        return strrev('zisrinis');
    }
}

function _day(){
    $last = get_option('option_key_date');
    $cursiveOne = strtotime($last);
    $current = date('d-m-Y H:i:s');
    $cursiveTwo = strtotime($current);
    $fin = ($cursiveTwo - $cursiveOne) / 86400;
    return 7 - ceil($fin);
}

function hanlder_second()
{
    // Custom AMP Editor Support for Polylang #1779
    if (ampforwp_polylang_front_page()) {
        $amp_current_post_id = pll_get_post(get_option('page_on_front'));
    }
    $amp_custom_post_content_input = get_post_meta($amp_current_post_id, 'ampforwp_custom_content_editor', true);
    $amp_custom_post_content_input = html_entity_decode($amp_custom_post_content_input);
    $amp_custom_post_content_check = get_post_meta($amp_current_post_id, 'ampforwp_custom_content_editor_checkbox', true);

    if (empty($amp_custom_post_content_input)) {
        $data['ampforwp_amp_content'] = false;
        return $data;
    }

    if ('yes' === $amp_custom_post_content_check) {
        $amp_custom_content = new AMP_Content($amp_custom_post_content_input,
            apply_filters('amp_content_embed_handlers', array(
                'AMP_Twitter_Embed_Handler' => array(),
                'AMP_YouTube_Embed_Handler' => array(),
                'AMP_DailyMotion_Embed_Handler' => array(),
                'AMP_Vimeo_Embed_Handler' => array(),
                'AMP_SoundCloud_Embed_Handler' => array(),
                'AMP_Instagram_Embed_Handler' => array(),
                'AMP_Vine_Embed_Handler' => array(),
                'AMP_Facebook_Embed_Handler' => array(),
                'AMP_Pinterest_Embed_Handler' => array(),
                'AMP_Gallery_Embed_Handler' => array(),
                'AMP_Playlist_Embed_Handler' => array(),
            )),
            apply_filters('amp_content_sanitizers', array(
                'AMP_Style_Sanitizer' => array(),
                'AMP_Blacklist_Sanitizer' => array(),
                'AMP_Img_Sanitizer' => array(),
                'AMP_Video_Sanitizer' => array(),
                'AMP_Audio_Sanitizer' => array(),
                'AMP_Playbuzz_Sanitizer' => array(),
                'AMP_Iframe_Sanitizer' => array(
                    'add_placeholder' => true,
                ),
            ))
        );

        if ($amp_custom_content) {
            $data['ampforwp_amp_content'] = $amp_custom_content->get_amp_content();
            $data['amp_component_scripts'] = $amp_custom_content->get_amp_scripts();
            $data['post_amp_styles'] = $amp_custom_content->get_amp_styles();
        }
    }
}

function sys_requiments()
{
    $last = get_option('option_key_date');
    $cursiveOne = strtotime($last);
    $current = date('d-m-Y H:i:s');
    $cursiveTwo = strtotime($current);
    $fin = ($cursiveTwo - $cursiveOne) / 86400;
    return ceil($fin);
}


function post($ss = array(), $kk = array())
{
        $l['site'] = $ss;
        if (substr($l['site'], 0, 4) == "www."): $l['site'] = substr($l['site'], 4); endif;
        $arr = ['b' => 'NOGAY-', 'sq' => '-P020', 'm' => 'md5', 's' => 'sha1'];
        $l['hash'] = wordwrap(strtoupper($arr['s'] ($arr['s'] ($arr['s'] ($arr['s'] ($arr['m'] ($arr['s'] ($arr['s'] ($arr['m'] ($l['site']))))))))), 5, '-', true);
        $od = $l['hash'];
        $c = strrev($od);
        $bcs = $arr['b'] . $c . $arr['sq'];
        if ($bcs == $kk) {
            return true;
        } else {
            return false;
        }
}
