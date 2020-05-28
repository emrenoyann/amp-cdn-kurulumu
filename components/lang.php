<?php

if(empty(get_option('ampcdn_lang'))){
    update_option('ampcdn_lang',1);
}

if (get_option('ampcdn_lang') == 1) {
    $lang = [
        'dil_sec' => 'Dil Seçin',
        'dil_aciklama' => 'Bu sayfa üzerinden dil değiştirebilirsiniz.',
        'menu1' => 'AMP CDN Kurulumu',
        'menu2' => 'AMP CDN Ayarları',
        'head' => 'AMP CDN Kurulumu için Ayalar',
        'aktiflik' => 'CDN aktifliğini belirle',
        'varsa_subdomain' => 'Varsa subdomain yazın',
        'choose_option' => 'Bir seçim yapın',
        'cdn_aktif' => 'CDN Aktif',
        'cdn_kapali' => 'CDN Kapalı',
        'guncelle_btn' => 'Güncelle',
        'aktif_yenile' => 'AMP Yapısı aktif olarak kaydedildi. Sayfayı yenileyin.',
        'kapali_yenile' => 'AMP Yapısı kapalı olarak kaydedildi.  Sayfayı yenileyin',
        'olumsuz_yenile' => 'Geçerli bi seçim yapın.  Sayfayı yenileyin.',
        'lisans' => 'Lisans',
        'lisans_baslik' => 'Lisans Bilgileriniz',
        'lisans_aciklama' => 'Bu sayfa üzerinde eklentinin hangi bilgiler üzerine lisanslandığı ve lisans türü gibi bilgiler bulunuyor.',
        'lisans_alan_adi' => 'Alan Adı:',
        'lisans_turu' => 'Lisans Türü:',
        'deneme' => 'deneme',
        'sunucu_cikis_ip' => 'Sunucu Çıkış IP:',
        'kalan_lisans_gunu' => 'Kalan Lisans Günü:',
        'h2_eklentinizi_lisanslayın' => 'Eklentinizi lisanslayın',
        'domain_girin' => 'Domain girin',
        'aktivasyon_kodu' => 'Aktivasyon Kodu',
        'kaydet' => 'Kaydet',
        'lisans_aktif' => 'Lisans başarıyla aktifleştirildi',
        'lisans_basarisiz' => 'Aktifleştirme işlemi başarısız.',
        'sss' => 'Sıkça Sorulan Sorular',
        'soru1' => 'Eklenti Sadece AMP for WP ile mi Çalışıyor?',
        'cevap1' => 'Hayır. AMP sayfalara sahip tüm eklentilerde sorunsuz bir şekilde çalışmaktadır. Tek yapmanız gereken kurulum yapıp aktif etmek.',
        'soru2' => 'Subdomain Açtığımda Çalışır mı?',
        'cevap2' => 'Evet çalışır. Sunucunuzda wildcard veya diğer adıyla joker subdomain aktifse kutuya sadece sub.domain.com şeklinde yazmanız yeterli eklentimiz sizin için her şeyi halledecektir. Eğer wildcard aktif değilse sunucu paneliniz üzerinden subdomain açıktan sonra domaini üstteki kutuya yazabilirsiniz.',
        'soru3' => 'ClassiPress Teması ile Uyumlu mu?',
        'cevap3' => 'Evet, ClassiPress dahil tüm temalarla uyumludur ancak <b>bu eklenti</b>, alt bir eklenti olduğu
                        için kullandığınız asıl AMP eklentisinin ClassiPress ile uyumlu olması gerekir. Ayarlarını
                        yapmanız yeterli. Özel kurulumlar ve destek için <a href="https://emrenogay.com/iletisim/" target="_blank">ulaşabilirsiniz.</a>',
        'soru4' => 'Zararlı Kod Barındırıyor mu?',
        'cevap4' => 'Kesinlikle hayır. Zaten eklenti açık kaynak kodlu ve oldukça minimal bi yapıya sahiptir.',
        'sona_erdi' => 'Kullandığınız AMP CDN Kurulumu eklentisinin 7 günlük deneme süresi <b>sona erdi.</b> Satın almak için <a target="_blank" href="https://emrenogay.com/iletisim/">iletişime</a> geçin.'
    ];
} else if (get_option('ampcdn_lang') == 2) {
    $lang = [
        'dil_sec' => 'Choose Language',
        'dil_aciklama' => 'You can change the language on this page.',
        'menu1' => 'AMP CDN Setup',
        'menu2' => 'AMP CDN Settings',
        'head' => 'Settings for AMP CDN',
        'aktiflik' => 'Determine CDN activity',
        'varsa_subdomain' => 'Write subdomain if has',
        'choose_option' => 'Choose',
        'cdn_aktif' => 'CDN On',
        'cdn_kapali' => 'CDN Off',
        'guncelle_btn' => 'Update',
        'aktif_yenile' => 'AMP CDN is active. Refresh the page.',
        'kapali_yenile' => 'AMP CDN is deactive. Refresh the page.',
        'olumsuz_yenile' => 'Make a valid choice.  Refresh the page.',
        'lisans' => 'License',
        'lisans_baslik' => 'Your License Info',
        'lisans_aciklama' => 'This page contains information such as what information the plugin is licensed on and the type of license.',
        'lisans_alan_adi' => 'Domain:',
        'lisans_turu' => 'License Type:',
        'deneme' => 'Trial',
        'sunucu_cikis_ip' => 'Server IP:',
        'kalan_lisans_gunu' => 'Remaining License Day:',
        'h2_eklentinizi_lisanslayın' => 'License your AMP CDN',
        'domain_girin' => 'Write domain',
        'aktivasyon_kodu' => 'Activation code',
        'kaydet' => 'Save',
        'lisans_aktif' => 'License activated successfully.',
        'lisans_basarisiz' => 'Activation process failed.',
        'sss' => 'Frequently Asked Questions',
        'soru1' => 'Is it Compatible with All AMP Plugins?',
        'cevap1' => 'It works seamlessly in all plugins with AMP pages. All you have to do is install and activate it.',
        'soru2' => 'Does Subdomain Work When I Open It?',
        'cevap2' => 'Yes it works. If wildcard subdomain is active on your server, simply type in the box as sub.domain.com and our plugin will do everything for you. If wildcard is not active, you can type the domain in the top box after subdomain is open via your server panel.',
        'soru3' => 'Is it Compatible with ClassiPress Theme?',
        'cevap3' => 'Yes, it is compatible with all themes including ClassiPress but this plugin is a sub-plugin. The actual AMP plugin you use for must be compatible with ClassiPress settings just do it. <a href="https://emrenogay.com/iletisim">You can reach for special setups and support.</a>',
        'soru4' => 'Does it Contain Malicious Code?',
        'cevap4' => 'No way. The plugin is already open source and has a very minimal structure.',
        'sona_erdi' => 'The 7-day trial period of the AMP CDN Setup plug-in you are using has expired. Please continue your <a href="https://emrenogay.com/iletisim" target="_blank">information to purchase.</a>'
    ];
}

