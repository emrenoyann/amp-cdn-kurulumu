<?php


if (!function_exists('_ampforwp_get_author_page_url')) {
    function _ampforwp_get_author_page_url()
    {
        global $redux_builder_amp, $post;
        $author_id = '';
        $author_page_url = '';
        $author_id = get_the_author_meta('ID');
        $author_page_url = get_author_posts_url($author_id);
        
        if (isset($redux_builder_amp['ampforwp-archive-support']) && $redux_builder_amp['ampforwp-archive-support']) {
            $author_page_url = ampforwp_url_controller($author_page_url);
        }
        return $author_page_url;
    }
}

if (!function_exists('_ampforwp_get_author_details')) {
    function _ampforwp_get_author_details($post_author, $params = '')
    {
        global $redux_builder_amp, $post;
        $post_author_url = '';
        $post_author_name = '';
        $post_author_name = $post_author->display_name;
        $post_author_url = ampforwp_get_author_page_url();
        $and_text = '';
        $and_text = ampforwp_translation($redux_builder_amp['amp-translator-and-text'], 'and');
        if (function_exists('coauthors')) {
            $post_author_name = coauthors($and_text, $and_text, null, null, false);
        }
        if (function_exists('coauthors_posts_links')) {
            $post_author_url = coauthors_posts_links($and_text, $and_text, null, null, false);
        }
        switch ($params) {
            case 'meta-info':
                if (isset($redux_builder_amp['ampforwp-author-page-url']) && $redux_builder_amp['ampforwp-author-page-url']) {
                    if (function_exists('coauthors_posts_links')) {
                        return '<span class="amp-wp-author author vcard">' . $post_author_url . '</span>';
                    }
                    return '<span class="amp-wp-author author vcard"><a href="' . esc_url($post_author_url) . '"  title="' . esc_html($post_author_name) . '" >' . esc_html($post_author_name) . '</a></span>';
                } else {
                    return '<span class="amp-wp-author author vcard">' . esc_html($post_author_name) . '</span>';
                }
                break;

            case 'meta-taxonomy':
                if (isset($redux_builder_amp['ampforwp-author-page-url']) && $redux_builder_amp['ampforwp-author-page-url']) {
                    if (function_exists('coauthors_posts_links')) {
                        return $post_author_url;
                    }
                    return '<a href="' . esc_url($post_author_url) . ' "><strong>' . esc_html($post_author_name) . '</strong></a>: ';
                } else {
                    return '<strong> ' . esc_html($post_author_name) . '</strong>: ';
                }
                break;
        }
    }
}

		$search = 'action="'._is_ssl().$_SERVER['HTTP_HOST'].'"';
		$replace = 'action="'._is_ssl().createProject().'/amp/"';
		$nogay = str_replace('href="'._baseURL(), 'href="'._is_ssl().createProject(), $nogay);
		$nogay = str_replace($search, $replace, $nogay);
	
