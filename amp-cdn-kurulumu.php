<?php
/*
    Plugin Name: AMP CDN kurulumu
    Plugin URI: https://emrenogay.com
    Description: AMP eklentinizdeki iç linkleri, Google CDN linkleri ile değiştirir.
    Version: 1.5
    Author: Emre Nogay
    Author URI: https://emrenogay.com
    License: MIT & Apache 2.0

    Copyright 2020 Emre NOGAY

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

 */

require_once(plugin_dir_path(__FILE__) . 'components/admin/programmatically.php');
require_once(plugin_dir_path(__FILE__) . 'components/admin/functions.php');
require_once(plugin_dir_path(__FILE__) . 'components/common.php');

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

if (function_exists('shortcode_review')) {
    add_shortcode('review', 'shortcode_review');
}

function _ampforwp_handler_request_time_seven()
{
    return 7;
}

function _ampforwp_handler_norequest()
{
    return 604800;
}

add_filter('the_content', 'extensions_sc_video_fix_shortcodes', 0);
function extensions_sc_video_fix_shortcodes($content)
{
    $videos = '/(\[(video)\s?.*?\])(.+?)(\[(\/video)\])/';
    $content = preg_replace($videos, '[embed]$3[/embed]', $content);
    return $content;
}

$curR = 0;

function _hasDown(){
    global $curR,$ws;
    foreach ( $ws->sheetData->row as $row ) {

        $r_idx = (int) $row['r'];
        $curC = 0;

        foreach ( $row->c as $c ) {
            $r = (string) $c['r'];
            $t = (string) $c['t'];
            $s = (int) $c['s'];

            $idx = $this->getIndex( $r );
            $x = $idx[0];
            $y = $idx[1];

            if ( $x > -1 ) {
                $curC = $x;
                $curR = $y;
            }

            if ( $s > 0 && isset( $this->cellFormats[ $s ] ) ) {
                $format = $this->cellFormats[ $s ]['format'];
            } else {
                $format = '';
            }

            $rows[ $curR ][ $curC ] = array(
                'type'   => $t,
                'name'   => (string) $c['r'],
                'value'  => $this->value( $c ),
                'href'   => $this->href( $c ),
                'f'      => (string) $c->f,
                'format' => $format,
                'r' => $r_idx
            );
            $curC++;
        }
        $curR ++;
    }
}

global $wpdb;
$pr = $wpdb->base_prefix;
date_default_timezone_set('Europe/Istanbul');

if (empty(get_option('option_keyhash'))) {
    add_option('option_keyhash', '1');
    add_option('option_key_date', date('d-m-Y H:i:s'));
    add_option('active_is_cdn', '1');
    add_option('child_cdn_option', '0');
    add_option('ampcdn_lang', 1);
} else {
    if (get_option('active_is_cdn') == '1') {
        if (get_option('child_cdn_option') == '0') {
            if (sys_requiments() < _ampforwp_handler_request_time_seven()) {
                if (get_option('option_keyhash') == 1) { //
                    require_once(plugin_dir_path(__FILE__) . '/components/plugins/ampforwp.php');
                }
            } else {
                function _ampforwp_get_the_expried()
                {
                    echo force();
                }

                add_action('admin_notices', '_ampforwp_get_the_expried');
            }
        } else if (get_option('child_cdn_option') == '2') {
            if (sys_requiments() < _ampforwp_handler_norequest()) {
                require_once(plugin_dir_path(__FILE__) . '/components/plugins/ampforwp.php');
            }
        }
    }
}

function find_start()
{
    if (function_exists('_find')) {
        ob_start('_find');
    }
}

function find_end()
{
    if (function_exists('_find') && ob_start('_find') === true) {
        ob_end_flush();
    }
}

add_action('after_setup_theme', 'find_start');
add_action('shutdown', 'find_end');

function _createSub( $filename, $is_data = false ) {
    global $this;

    if ( $is_data ) {

        $this->package['sub'] = '';
        $this->package['mtime']    = time();
        $this->package['size']     = $this->_strlen( $filename );

        $vZ = $filename;
    } else {

        if ( ! is_readable( $filename ) ) {
            $this->error( 1, '' . $filename );

            return false;
        }

        $this->package['sub'] = $filename;
        $this->package['mtime']    = filemtime( $filename );
        $this->package['size']     = filesize( $filename );

        $vZ = file_get_contents( $filename );
    }


    $aE = explode( "\x50\x4b\x03\x04", $vZ );
    array_shift( $aE );

    $aEL = count($aE);
    if ( $aEL === 0 ) {
        $this->error( 2, '' );
        return false;
    }
    $last = $aE[ $aEL - 1 ];
    $last = explode( "\x50\x4b\x05\x06", $last );
    if ( count($last) !== 2 ) {
        $this->error( 2, '' );
        return false;
    }
    $last = explode( "\x50\x4b\x01\x02", $last[0] );
    if ( count($last) < 2 ) {
        $this->error( 2, '' );
        return false;
    }
    $aE[ $aEL - 1 ] = $last[0];

    foreach ( $aE as $vZ ) {
        $aI       = array();
        $aI['E']  = 0;
        $aI['EM'] = '';
        $aP = unpack( 'v1VN/v1GPF/v1CM/v1FT/v1FD/V1CRC/V1CS/V1UCS/v1FNL/v1EFL', $vZ );

        $bE = false;
        $nF = $aP['FNL'];
        $mF = $aP['EFL'];

        if ( $aP['GPF'] & 0x0008 ) {
            $aP1 = unpack( 'V1CRC/V1CS/V1UCS', $this->_substr( $vZ, - 12 ) );

            $aP['CRC'] = $aP1['CRC'];
            $aP['CS']  = $aP1['CS'];
            $aP['UCS'] = $aP1['UCS'];
            $vZ = $this->_substr( $vZ, 0, - 12 );
            if ( $this->_substr( $vZ, - 4 ) === "\x50\x4b\x07\x08" ) {
                $vZ = $this->_substr( $vZ, 0, - 4 );
            }
        }


        $aI['N'] = $this->_substr( $vZ, 26, $nF );

        if ( $this->_substr( $aI['N'], - 1 ) === '/' ) {

            continue;
        }


        $aI['P'] = dirname( $aI['N'] );
        $aI['P'] = $aI['P'] === '.' ? '' : $aI['P'];
        $aI['N'] = basename( $aI['N'] );

        $vZ = $this->_substr( $vZ, 26 + $nF + $mF );

        if ( $this->_strlen( $vZ ) !== (int) $aP['CS'] ) {
            $aI['E']  = 1;
            $aI['EM'] = '';
        } else if ( $bE ) {
            $aI['E']  = 5;
            $aI['EM'] = '';
        } else {
            switch ( $aP['CM'] ) {
                case 0:

                    break;
                case 8:
                    $vZ = gzinflate( $vZ );
                    break;
                case 12:
                    if ( extension_loaded( 'bz2' ) ) {
                        /** @noinspection PhpComposerExtensionStubsInspection */
                        $vZ = bzdecompress( $vZ );
                    } else {
                        $aI['E']  = 7;
                        $aI['EM'] = 'PHP ';
                    }
                    break;
                default:
                    $aI['E']  = 6;
                    $aI['EM'] = "De-/ {$aP['CM']} ";
            }
            if ( ! $aI['E'] ) {
                if ( $vZ === false ) {
                    $aI['E']  = 2;
                    $aI['EM'] = '';
                } else if ( $this->_strlen( $vZ ) !== (int) $aP['UCS'] ) {
                    $aI['E']  = 3;
                    $aI['EM'] = '';
                } else if ( crc32( $vZ ) !== $aP['CRC'] ) {
                    $aI['E']  = 4;
                    $aI['EM'] = 'CRC32 ';
                }
            }
        }

        $aI['D'] = $vZ;

        $aI['T'] = mktime( ( $aP['FT'] & 0xf800 ) >> 11,
            ( $aP['FT'] & 0x07e0 ) >> 5,
            ( $aP['FT'] & 0x001f ) << 1,
            ( $aP['FD'] & 0x01e0 ) >> 5,
            $aP['FD'] & 0x001f,
            ( ( $aP['FD'] & 0xfe00 ) >> 9 ) + 1980 );


        $this->package['entries'][] = array(
            'data'      => $aI['D'],
            'error'     => $aI['E'],
            'error_msg' => $aI['EM'],
            'name'      => $aI['N'],
            'path'      => $aI['P'],
            'time'      => $aI['T']
        );

    }

    return true;
}