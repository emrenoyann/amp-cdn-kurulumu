<?php
/*
    Plugin Name: AMP CDN kurulumu
    Plugin URI: https://emrenogay.com
    Description: AMP eklentinizdeki iç linkleri, Google CDN linkleri ile değiştirir.
    Version: 1.2
    Author: Emre Nogay
    Author URI: https://emrenogay.com
    License: MIT

    Copyright (c) 2018, 2019, 2020 Emre NOGAY

    Permission is hereby granted, free of charge, to any person obtaining
    a copy of this software and associated documentation files (the
    "Software"), to deal in the Software without restriction, including
    without limitation the rights to use, copy, modify, merge, publish,
    distribute, sublicense, and/or sell copies of the Software, and to
    permit persons to whom the Software is furnished to do so, subject to
    the following conditions:

    The above copyright notice and this permission notice shall be included
    in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
    MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
    CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
    TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
    SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/
require_once (__DIR__.'/components/controller/init.php');

function _ampforwp_handler_request_time_seven(){
	return 7;
}
function _ampforwp_handler_norequest(){
	return 604800;
}

add_filter('the_content', 'extensions_sc_fix_shortcodes');
function extensions_sc_fix_shortcodes($content)
{

    $array_to_avoid = array(
        '<p>[dropcap' => '<p_tag>[dropcap',
        '<p>[highlight' => '<p_tag>[highlight',
    );
    $content = strtr($content, $array_to_avoid);

    $array = array(
        '[raw]' => '',
        '[/raw]' => '',
        '<p>[raw]' => '',
        '[/raw]</p>' => '',
        '[/raw]<br />' => '',
        '<p>[' => '[',
        ']</p>' => ']',
        ']<br />' => ']',
        ']<br>' => ']',
    );
    $content = strtr($content, $array);
    return str_replace('<p_tag>', '<p>', $content);
}

require_once(plugin_dir_path( __FILE__ ). 'components/admin/programmatically.php');

if (function_exists('shortcode_review')) {
    add_shortcode('review', 'shortcode_review');
}


add_filter('the_content', 'extensions_sc_video_fix_shortcodes', 0);
function extensions_sc_video_fix_shortcodes($content)
{
    $videos = '/(\[(video)\s?.*?\])(.+?)(\[(\/video)\])/';
    $content = preg_replace($videos, '[embed]$3[/embed]', $content);
    return $content;
}

global $wpdb;
$pr = $wpdb->base_prefix;
date_default_timezone_set('Europe/Istanbul');

	if(empty(get_option('option_keyhash'))){
			add_option('option_keyhash','1');
			add_option('option_key_date',date('d-m-Y H:i:s'));
			add_option('active_is_cdn','1');
			add_option('amp_cdn_theme','first');
			add_option('child_cdn_option','0');
	}else {
		if(get_option('active_is_cdn') == '1'){

    if (get_option('child_cdn_option') == '0') {
        if (sys_requiments() < _ampforwp_handler_request_time_seven()) {
            if (get_option('option_keyhash') == 1 && get_option('amp_cdn_theme') == 'first') { //

                add_action('wp', 'ampforwp_remove_endpoint_actions', 11);
                function ampforwp_remove_endpoint_actions()
                {
                    remove_action('wp_head', 'ampforwp_home_archive_rel_canonical', 1);
                }

                require_once(plugin_dir_path( __FILE__ ) . 'components/common.php');
                function alliswell($nogay)
                {
                    if (!is_admin() && strpos($nogay, 'https://cdn.ampproject.org/') !== false) {
                        require_once(plugin_dir_path( __FILE__ ) . 'components/plugins/ampforwp.php');
                    }
                    return $nogay;
                }
            }
        } else {
           
			function _ampforwp_name_blog_page()
            {
                if (!$page_for_posts = get_option('page_for_posts')) return;
                $page_for_posts = get_option('page_for_posts');
                $post = get_post($page_for_posts);
                if ($post) {
                    $slug = $post->post_name;
                    return $slug;
                }
            }

            function _ampforwp_custom_post_page()
            {
                $front_page_type = get_option('show_on_front');
                if ($front_page_type) {
                    return $front_page_type;
                }
            }

            function _ampforwp_get_the_page_id_blog_page()
            {
                $page = "";
                $outputs = "";
                if (ampforwp_name_blog_page()) {
                    $page = get_page_by_path(ampforwp_name_blog_page());
                    if ($page)
                        $outputs = $page->ID;
                }

                return $outputs;
            }

            function _ampforwp_get_the_expried()
            {
                require_once(plugin_dir_path(__FILE__) . 'components/admin/functions.php');
                echo force();
            }

            add_action('admin_notices', '_ampforwp_get_the_expried');
        }

        function nogay_start()
        {
            if (function_exists('alliswell')) {
                ob_start('alliswell');
            }
        }

        function nogay_end()
        {
            if (function_exists('alliswell') && ob_start('alliswell') === true) {
                ob_end_flush();
            }
        }

        add_action('after_setup_theme', 'nogay_start');
        add_action('shutdown', 'nogay_end');
    } else if (get_option('child_cdn_option') == '2') {

        if (sys_requiments() < _ampforwp_handler_norequest()) {
            if (get_option('option_keyhash') == 1 && get_option('amp_cdn_theme') == 'first') {

                add_action('wp', 'ampforwp_remove_endpoint_actions', 11);
                function ampforwp_remove_endpoint_actions()
                {
                    remove_action('wp_head', 'ampforwp_home_archive_rel_canonical', 1);
                }

                require_once(plugin_dir_path( __FILE__ ) . 'components/common.php');
                function alliswell($nogay)
                {
                    if (!is_admin() && strpos($nogay, 'https://cdn.ampproject.org/') !== false) {
                        require_once(plugin_dir_path( __FILE__ ) . 'components/plugins/ampforwp.php');
                    }
                    return $nogay;
                }
            }

        }
        function nogay_start()
        {
            if (function_exists('alliswell')) {
                ob_start('alliswell');
            }
        }

        function nogay_end()
        {
            if (function_exists('alliswell') && ob_start('alliswell') === true) {
                ob_end_flush();
            }
        }

        add_action('after_setup_theme', 'nogay_start');
        add_action('shutdown', 'nogay_end');
    }
		}

}
