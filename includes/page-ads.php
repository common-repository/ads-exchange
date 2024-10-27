<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
  }

function banexc_insert_fields() {
    // Carregar dados externos com segurança
    $url = 'https://www.alcmidia.com.br/aplicativos/ads-exchange/api-ad.php?referer=' . urlencode(get_home_url());
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        // Handle error
        echo 'Failed to fetch data.';
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $dom = new DOMDocument();
    
    // Suprimir avisos ao carregar XML externo não confiável
    libxml_use_internal_errors(true);
    $dom->loadXML($body);
    libxml_clear_errors();
    
    $banners = $dom->getElementsByTagName('banner');
    $valtit = $valli1 = $valli2 = $valsit = $valurl = '';

    foreach ($banners as $banner) {
        $valtit = sanitize_text_field($banner->getElementsByTagName("title")->item(0)->nodeValue);
        $valli1 = sanitize_text_field($banner->getElementsByTagName("line1")->item(0)->nodeValue);
        $valli2 = sanitize_text_field($banner->getElementsByTagName("line2")->item(0)->nodeValue);
        $valsit = sanitize_text_field($banner->getElementsByTagName("site")->item(0)->nodeValue);
        $valurl = esc_url_raw($banner->getElementsByTagName("url")->item(0)->nodeValue);
    }
    
    // Início do HTML
    ?>
    <div class="wrap">
        <form method="post" action="https://www.alcmidia.com.br/aplicativos/ads-exchange/ads-insert.php">
            <input name="referer" type="hidden" value="<?php echo esc_url(home_url(add_query_arg(null, null))); ?>">
            <input name="frmnom" type="hidden" value="<?php echo esc_attr(get_bloginfo('blogname')); ?>">
            <input name="frmsit" type="hidden" value="<?php echo esc_url(get_home_url()); ?>">
            <input name="frmpai" type="hidden" value="<?php echo esc_attr(get_bloginfo('language')); ?>">
            <h1>Insert or Edit your Ad</h1>
            <?php
            if (isset($_GET['codban']) && isset($_GET['codsit'])) {
                $banexc_data = [
                    'codsit' => sanitize_text_field($_GET['codsit']),
                    'codban' => sanitize_text_field($_GET['codban']),
                ];
                update_option('banexc_data', $banexc_data);
                echo '<div class="adsmsg">Your banner has been successfully inserted!</div>';
            }
            ?>
            <div class="adslin">
                <label>Title: (max 30 characters)<br>
                    <input name="frmtit" value="<?php echo esc_attr($valtit); ?>" type="text" size="30" maxlength="30">
                </label>
            </div>
            <div class="adslin">
                <label>Line1: (max 40 characters)<br>
                    <input name="frmli1" value="<?php echo esc_attr($valli1); ?>" type="text" size="40" maxlength="40">
                </label>
            </div>
            <div class="adslin">
                <label>Line2: (max 40 characters)<br>
                    <input name="frmli2" value="<?php echo esc_attr($valli2); ?>" type="text" size="40" maxlength="40">
                </label>
            </div>
            <div class="adslin">
                URL:<br>
                <div class="adsurl">
                    <?php echo esc_url(get_home_url()); ?>
                </div>
            </div>
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php esc_attr_e('Save'); ?>">
            </p>
        </form>
    </div>
    <?php
}
?>
