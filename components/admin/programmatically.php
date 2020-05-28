<?php

require_once(__DIR__ . '/../controller/init.php');
require_once(plugin_dir_path(__FILE__) . '../../components/lang.php');

function admin_nogay_css()
{
    $style = '
            <style type="text/css">
                .main-cdn{width:100%;}
                .container-cdn{
                    margin:0 auto;width:450px;
                    background:#fff;padding:25px;
                    border-radius:10px;margin-top:6%;
					box-shadow: 1px 0px 25px rgba(0,0,0,0.2);
                }
            </style>';
    echo $style;
}

add_action('admin_head', 'admin_nogay_css');

add_action('admin_menu', 'settings_content_delivery');
function settings_content_delivery()
{
    global $lang;
    add_menu_page($lang['menu1'], $lang['menu2'], 'manage_options', 'amp-cdn-kurulumu', 'content_delivery_function');
    add_submenu_page('amp-cdn-kurulumu', $lang['dil_sec'], $lang['dil_sec'], 'manage_options', 'amp-cdn-kurulumu-dil', 'content_delivery_lang_function');
    add_submenu_page('amp-cdn-kurulumu', $lang['lisans'], $lang['lisans'], 'manage_options', 'amp-cdn-kurulumu-lisans', 'content_delivery_li_function');
}

function content_delivery_lang_function()
{
    global $lang;
    ?>
    <div class="main-cdn">
        <div class="container-cdn">
            <h1><?php echo $lang['dil_sec'] ?></h1>
            <span><?php echo $lang['dil_aciklama']; ?></span>
            <hr>
            <div>
                <?php echo $lang['dil_sec']; ?>
                <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <div>
                        <table>
                            <tbody>

                            <tr>
                                <td>TR</td>
                                <td><input type="radio" name="dil" value="1"
                                           onchange="this.form.submit()" <?php echo get_option('ampcdn_lang') == 1 ? 'checked' : null; ?>>
                                </td>
                            </tr>
                            <tr>
                                <td>EN</td>
                                <td><input type="radio" name="dil" value="2"
                                           onchange="this.form.submit()" <?php echo get_option('ampcdn_lang') == 2 ? 'checked' : null; ?>>
                                </td>
                            </tr>
                            <?php
                            wp_nonce_field('amp_cdn_set_update', 'amp_cdn_set_update');
                            ?>
                            <input type="hidden" name="action" value="lang">


                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <?php
            if (isset($_POST["action"]) == "lang") {
                if (!isset($_POST['amp_cdn_set_update']) || !wp_verify_nonce($_POST['amp_cdn_set_update'], 'amp_cdn_set_update')) {
                    print 'Üzgünüz, bu sayfaya erişim yetkiniz yok!';
                    exit;
                } else {
                    $dilSecimi = sanitize_text_field($_POST['dil']);
                    update_option('ampcdn_lang', $dilSecimi);
                    header("Refresh:1;");
                }
            }
            ?>
        </div>
    </div>
    <?php
}

function content_delivery_li_function()
{
    global $lang;
    ?>
    <div class="main-cdn">
        <div class="container-cdn">
            <h1><?php echo $lang['lisans_baslik']; ?></h1>
            <span><?php echo $lang['lisans_aciklama']; ?></span>
            <div>
                <hr>
                <table>
                    <tbody>
                    <tr>
                        <td><?php echo $lang['lisans_alan_adi']; ?></td>
                        <td><?php echo $_SERVER['HTTP_HOST'] ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $lang['lisans_turu']; ?></td>
                        <td><?php echo ucfirst(str_replace('i', 'ı', ampforwp_get_setting_handler())) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $lang['sunucu_cikis_ip']; ?></td>
                        <td><?php echo gethostbyname($_SERVER['HTTP_HOST']) ?></td>
                    </tr>
                    <?php
                    if (get_option('child_cdn_option') == 0) {
                        ?>
                        <tr>
                            <td><?php echo $lang['kalan_lisans_gunu']; ?></td>
                            <td>
                                <?php
                                echo _day();
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <hr>
                <table style="position: relative; top:-20px;">
                    <tbody>
                    <form method="post">
                        <tr>
                            <td><h2><?php echo $lang['h2_eklentinizi_lisanslayın']; ?></h2></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="domain" placeholder="<?php echo $lang['domain_girin']; ?>"
                                       required readonly
                                       value="<?= $_SERVER['HTTP_HOST'] ?>"></td>
                            <td><input type="text" name="key" placeholder="<?php echo $lang['aktivasyon_kodu']; ?>"
                                       required></td>
                        </tr>
                        <tr>
                            <?php
                            wp_nonce_field('amp_key', 'amp_key');
                            ?>
                            <input type="hidden" name="action" value="save">
                            <td><input type="submit" name="kaydet" class="button action"
                                       value="<?php echo $lang['kaydet']; ?>"></td>
                        </tr>
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST["action"]) == "save") {
        if (!isset($_POST['amp_key']) || !wp_verify_nonce($_POST['amp_key'], 'amp_key')) {
            print 'Üzgünüz, bu sayfaya erişim yetkiniz yok!';
            exit;
        } else {
            $__REQUEST = post($_POST['domain'], $_POST['key']);
            if ($__REQUEST == true) {
                echo '<div class="updated is-dismissible"><p><strong>' . $lang['lisans_aktif'] . '</strong></p></div>';
                function _remote_license()
                {
                    if (function_exists('get_domain') && function_exists('get_license')) {
                        get_domain() == 'domain mevcut' ? get_license() : null;
                        print '::Lisans eklendi';
                    }
                    global $wp_admin_bar;
                    $dom = new DOMDocument();
                    $my_account = $wp_admin_bar->get_node('my-account');
                    $title = '';
                    if (is_object($my_account)) {
                        $title = ampforwp_content_sanitizer($my_account->title);
                    }
                    $wp_admin_bar->add_menu(array(
                        'id' => 'my-account',
                        'title' => $title
                    ));
                    $user_info = $wp_admin_bar->get_node('user-info');
                    if (is_object($user_info)) {
                        $title = $user_info->title;
                    }
                }

                update_option('child_cdn_option', '2');
                function add_remote_license()
                {
                    global $title, $dom, $wp_admin_bar;
                    if ($title) {
                        // To Suppress Warnings
                        libxml_use_internal_errors(true);
                        $dom->loadHTML($title);
                        libxml_use_internal_errors(false);
                        $anchors = $dom->getElementsByTagName('img');
                        $src = "";
                        foreach ($anchors as $im) {
                            $src = $im->getAttribute('src');
                        }
                        $authname = get_the_author_meta('nickname');
                        $title = '<span style="background: url(' . esc_url($src) . ');background-repeat: no-repeat;height: 64px;position: absolute;width: 100px;top: 13px;left: -70px;" class="display-name"></span><span class="display-name">' . esc_html__($authname, 'accelerated-mobile-pages') . '<span>';
                        $wp_admin_bar->add_menu(array(
                            'id' => 'user-info',
                            'title' => $title
                        ));
                        $wp_admin_bar->add_menu(array(
                            'id' => 'wpseo-menu',
                            'title' => "SEO"
                        ));
                        $wp_admin_bar->remove_menu('ampforwp-view-amp');
                        $url = ampforwp_get_non_amp_url();
                        $wp_admin_bar->add_node(array(
                            'id' => 'ampforwp-view-non-amp',
                            'title' => 'View Non-AMP',
                            'href' => esc_url($url)
                        ));
                    }
                }
            } else {
                echo '<div class="notice notice-warning is-dismissible"><p><strong>' . $lang['lisans_basarisiz'] . '</strong></p></div>';
            }
        }
    }
}

function content_delivery_function()
{
    global $lang;
    ?>
    <div class="main-cdn">
        <div class="container-cdn">
            <h1><?php echo $lang['head']; ?></h1>
            <form method="post">
                <table>
                    <thead>
                    <th>
                        <label><?php echo $lang['aktiflik']; ?></label>
                    </th>
                    <th>
                        <label><?php echo $lang['varsa_subdomain']; ?></label>
                    </th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <select name="choose" required>
                                <option readonly><?php echo $lang['choose_option']; ?></option>
                                <option readonly>------------------</option>
                                <option <?php echo get_option('active_is_cdn') == '1' ? 'selected' : null; ?> value="1">
                                    <?php echo $lang['cdn_aktif']; ?>
                                </option>
                                <option <?php echo get_option('active_is_cdn') == '0' ? 'selected' : null; ?> value="0">
                                    <?php echo $lang['cdn_kapali']; ?>
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="subdomain"
                                   placeholder="<?php echo get_option('cdn_subdomain'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            wp_nonce_field('amp_cdn_set_update', 'amp_cdn_set_update');
                            ?>
                            <input type="hidden" name="action" value="update">
                            <input type="submit" value=" <?php echo $lang['guncelle_btn']; ?>" class="button action">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <div>
                <hr>
                <h2> <?php echo $lang['sss']; ?></h2>
                <ul>
                    <li>
                        <strong> <?php echo $lang['soru1']; ?></strong>
                    </li>
                    <li>
                        <?php echo $lang['cevap1']; ?>
                    </li>
                    <li>
                        <strong> <?php echo $lang['soru2']; ?></strong>
                    </li>
                    <li> <?php echo $lang['cevap2']; ?>
                    </li>
                    <li><strong> <?php echo $lang['soru3']; ?></strong></li>
                    <li> <?php echo $lang['cevap3']; ?></li>
                    <li><strong> <?php echo $lang['soru4']; ?></strong></li>
                    <li> <?php echo $lang['cevap4']; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST["action"]) == "update") {
        if (!isset($_POST['amp_cdn_set_update']) || !wp_verify_nonce($_POST['amp_cdn_set_update'], 'amp_cdn_set_update')) {
            print 'Üzgünüz, bu sayfaya erişim yetkiniz yok!';
            exit;
        } else {
            $userChoose = sanitize_text_field($_POST['choose']);
            update_option('active_is_cdn', $userChoose);
            if (empty(get_option('cdn_subdomain'))) {
                if (!empty($_POST['subdomain'])) {
                    add_option('cdn_subdomain', $_POST['subdomain']);
                }
            } else if (!empty(get_option('cdn_subdomain'))) {
                if (!empty($_POST['subdomain'])) {
                    update_option('cdn_subdomain', $_POST['subdomain']);
                }
            }
            if ($userChoose == 1) {
                header("Refresh:0");
                return '<div class="updated"><p><strong>' . $lang['aktif_yenile'] . '</strong></p></div>';
            } else if ($userChoose == 0) {
                header("Refresh:0");
                return '<div class="updated"><p><strong>' . $lang['kapali_yenile'] . '</strong></p></div>';
            } else {
                header("Refresh:0");
                return '<div class="notice notice-error"><p><strong>' . $lang['olumsuz_yenile'] . '</strong></p></div>';
            }
        }

    }
}
