<?php

require_once(__DIR__ . '/../controller/init.php');

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
    add_menu_page('AMP CDN Kurulumu', 'AMP CDN Ayarları', 'manage_options', 'amp-cdn-kurulumu', 'content_delivery_function');
    add_submenu_page('amp-cdn-kurulumu', 'Lisans', 'Lisans', 'manage_options', 'amp-cdn-kurulumu-lisans', 'content_delivery_li_function');
}

function content_delivery_li_function()
{
    ?>
    <div class="main-cdn">
        <div class="container-cdn">
            <h1>Lisans Bilgileriniz</h1>
            <span>Bu sayfa üzerinde eklentinin hangi bilgiler üzerine lisanslandığı ve lisans türü gibi bilgiler bulunuyor.</span>
            <div>
                <hr>
                <table>
                    <tbody>
                    <tr>
                        <td>Alan Adı:</td>
                        <td><?=$_SERVER['HTTP_HOST']?></td>
                    </tr>
                    <tr>
                        <td>Lisans Türü:</td>
                        <td><?=ucfirst(str_replace('i','ı',ampforwp_get_setting_handler()))?></td>
                    </tr>
                    <tr>
                        <td>Sunucu Çıkış IP:</td>
                        <td><?=gethostbyname($_SERVER['HTTP_HOST'])?></td>
                    </tr>
                    </tbody>
                </table>
                <hr>
                <table style="position: relative; top:-20px;">
                    <tbody>
                    <form method="post">
                        <tr>
                            <td><h2>Eklentinizi lisanslayın</h2></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="domain" placeholder="Domain girin" required readonly
                                       value="<?= $_SERVER['HTTP_HOST'] ?>"></td>
                            <td><input type="text" name="key" placeholder="Aktivasyon kodu" required></td>
                        </tr>
                        <tr>
                            <?php
                            wp_nonce_field('amp_key', 'amp_key');
                            ?>
                            <input type="hidden" name="action" value="save">
                            <td><input type="submit" name="kaydet" class="button action" value="Kaydet"></td>
                        </tr>
                    </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?
    if (isset($_POST["action"]) == "save") {
        if (!isset($_POST['amp_key']) || !wp_verify_nonce($_POST['amp_key'], 'amp_key')) {
            print 'Üzgünüz, bu sayfaya erişim yetkiniz yok!';
            exit;
        } else {
            $__REQUEST = post($_POST['domain'], $_POST['key']);
            if ($__REQUEST == true) {
                update_option('child_cdn_option', '2');
                echo '<div class="updated is-dismissible"><p><strong>Lisans başarıyla aktifleştirildi.</strong></p></div>';
            } else {
                echo '<div class="notice notice-warning is-dismissible"><p><strong>Aktifleştirme işlemi başarısız.</strong></p></div>';
            }
        }
    }

}
function content_delivery_function()
{
    ?>
    <div class="main-cdn">
        <div class="container-cdn">
            <h1>AMP CDN Kurulumu için Ayalar</h1>
            <form method="post">
                <table>
                    <thead>
                    <th>
                        <label>CDN aktifliğini belirle</label>
                    </th>
                    <th>
                        <label>Eklenti Seçin</label>
                    </th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <select name="choose" required>
                                <option readonly>Bir seçim yapın</option>
                                <option readonly>------------------</option>
                                <option <? echo get_option('active_is_cdn') == '1' ? 'selected' : null; ?> value="1">CDN
                                    Aktif
                                </option>
                                <option <? echo get_option('active_is_cdn') == '0' ? 'selected' : null; ?> value="0">CDN
                                    Kapalı
                                </option>
                            </select>
                        </td>
                        <td>
                            <select name="choose-theme" id="choose-theme" required>
                                <option <? echo get_option('amp_cdn_theme') == 'first' ? 'selected' : null; ?>
                                        value="first">WordPress için AMP (AMP for WP)
                                </option>
                                <option disabled>Better AMP (Yakında)</option>
                                <option disabled>AMP (Yakında)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            wp_nonce_field('amp_cdn_set_update', 'amp_cdn_set_update');
                            ?>
                            <input type="hidden" name="action" value="update">
                            <input type="submit" value="Güncelle" class="button action">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
            <div>
                <hr>
                <h2>Sıkça Sorulan Sorular</h2>
                <ul>
                    <li>
                        <strong>Eklenti Sadece AMP for WP ile mi Çalışıyor?</strong>
                    </li>
                    <li>Evet, bu işi en iyi yapan eklenti AMP for WP eklentisi olduğu için sadece onunla çalışıyoruz.
                        Belki diğer eklentilerle uyumlu hale getiririz ama şimdilik sadece AMP for WP.
                    </li>
                    <li>
                        <strong>Subdomain Açtığımda Çalışır mı?</strong>
                    </li>
                    <li>Evet çalışır. Bunun için ekstra bir ayar yapmanıza gerek yok. Eklentinin kurulu ve aktif olması
                        yeterli.
                    </li>
                    <li><strong>ClassiPress Teması ile Uyumlu mu?</strong></li>
                    <li>Evet, ClassiPress dahil tüm temalarla uyumludur ancak <b>bu eklenti</b>, alt bir eklenti olduğu
                        için kullandığınız asıl AMP eklentisinin ClassiPress ile uyumlu olması gerekir. AMP for WP
                        uyumludur, ayarlarını yapmanız yeterli. Özel kurulumlar ve destek için <a
                                href="https://emrenogay.com/iletisim/" target="_blank">ulaşabilirsiniz.</a></li>
                    <li><strong>Zararlı Kod Barındırıyor mu?</strong></li>
                    <li>Kesinlikle hayır. Zaten eklenti açık kaynak kodlu ve oldukça minimal bi yapıya sahiptir.
                        İnceleme yapabilirsiniz ancak MIT lisansı ile lisansladığımız için kullanmak dışında hiç bir şey
                        yapılamaz.
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
            $chooseTheme = sanitize_text_field($_POST['choose-theme']);
            update_option('active_is_cdn', $userChoose);
            update_option('amp_cdn_theme', $chooseTheme);
            if ($userChoose == 1) {
                echo '<div class="updated"><p><strong>AMP Yapısı aktif olarak kaydedildi. Sayfayı yenileyin.</strong></p></div>';
            } else if ($userChoose == 0) {
                echo '<div class="updated"><p><strong>AMP Yapısı kapalı olarak kaydedildi.  Sayfayı yenileyin.</strong></p></div>';
            } else {
                echo '<div class="notice notice-error"><p><strong>Geçerli bi seçim yapın.  Sayfayı yenileyin.</strong></p></div>';
            }
        }
    }
}