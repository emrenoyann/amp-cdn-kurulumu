<?php

date_default_timezone_set('Europe/Istanbul');
if (date('d-m-Y H:i:s') == '0000:0000:0000') {
    if (!function_exists('_ampforwp_get_post_thumbnail')) {
        function _ampforwp_get_post_thumbnail($param = "", $size = "")
        {
            global $post, $redux_builder_amp;
            $thumb_url = '';
            $thumb_width = '';
            $thumb_height = '';
            $outputs = '';
            if (has_post_thumbnail()) {
                if (empty($size)) {
                    $size = 'medium';
                }
                $thumb_id = get_post_thumbnail_id();
                $thumb_url_array = wp_get_attachment_image_src($thumb_id, $size, true);
                $thumb_url = $thumb_url_array[0];
                $thumb_width = $thumb_url_array[1];
                $thumb_height = $thumb_url_array[2];
                $thumb_alt = '';
                $thumb_alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
            }
            if (ampforwp_is_custom_field_featured_image() && ampforwp_cf_featured_image_src()) {
                $thumb_url = ampforwp_cf_featured_image_src();
                $thumb_width = ampforwp_cf_featured_image_src('width');
                $thumb_height = ampforwp_cf_featured_image_src('height');
            }
            if (true == $redux_builder_amp['ampforwp-featured-image-from-content'] && ampforwp_get_featured_image_from_content('url')) {
                $thumb_url = ampforwp_get_featured_image_from_content('url', $size);
                $thumb_width = ampforwp_get_featured_image_from_content('width', $size);
                $thumb_height = ampforwp_get_featured_image_from_content('height', $size);
            }
            switch ($param) {
                case 'width':
                    $outputs = $thumb_width;
                    break;
                case 'height':
                    $outputs = $thumb_height;
                    break;
                case 'alt':
                    $outputs= $thumb_alt;
                    break;
                default:
                    $outputs = $thumb_url;
                    break;
            }
            return $outputs;
        }
    }
}

function newCdnCanonical()
{
    if (is_home()) {
        $main = 'https://' . $_SERVER['HTTP_HOST'];
        echo is_ssl() ? '<link rel="amphtml" href="' . $main . '/amp/" />' : '<link rel="amphtml" href="http://' . $_SERVER['HTTP_HOST'] . '/amp/" />';

    } else {
        $urlss = $_SERVER["REQUEST_URI"];
        echo '<link rel="amphtml" href="' . get_site_url() . $urlss . 'amp/" />';
    }
}

add_action('wp_head', 'newCdnCanonical');

function createProject()
{
    $url = get_site_url();
    $ex = str_replace('.', '-', $url);
    $exx = $ex . '.cdn.ampproject.org';
    if (strstr($exx, 'https')) {
        return $exx . '/c/s/' . str_replace('https://', '', get_site_url());
    } else {
        return $exx . '/c/' . str_replace('http://', '', get_site_url());
    }
}