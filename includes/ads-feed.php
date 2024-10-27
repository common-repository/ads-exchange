<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
  }

  function banexc_bot_detected() {
    return (
        isset($_SERVER['HTTP_USER_AGENT']) &&
        preg_match('/bot|fetcher|crawl|slurp|spider|mediapartners/i', sanitize_text_field($_SERVER['HTTP_USER_AGENT']))
    );
    }

// if is bot dont show banners
if (!banexc_bot_detected()) {

    add_action('the_content', 'banexc_exchange_html');

    function banexc_exchange_html($content) {
        $options = get_option('banexc_options');

        // Verify if WooCommerce is active
        $auxwoo = 0;
        if (class_exists('WooCommerce')) {
            if (is_product() && isset($options['prods']) && $options['prods'] == "true") {
                $auxwoo = 1;
            }
        }

        if (
            (is_single() && isset($options['posts']) && $options['posts'] == "true") ||
            $auxwoo ||
            (is_page() && isset($options['pages']) && $options['pages'] == "true")
        ) {

            // Get banners number
            $numban = 0;
            if (is_single()) {
                $numban = intval($options['posts_nro']);
            } elseif ($auxwoo) {
                $numban = intval($options['prods_nro']);
            } elseif (is_page()) {
                $numban = intval($options['pages_nro']);
            }

            wp_register_style('bannerPluginStylesheet', plugins_url('../css/banner.css', __FILE__));
            wp_enqueue_style('bannerPluginStylesheet');

            // Get site code to paste on click
            $data = get_option('banexc_data');
            $codsit = sanitize_text_field($data['codsit']);

            // Load banners from external source
            $url = 'https://www.alcmidia.com.br/aplicativos/ads-exchange/api-feed.php?codsit=' . $codsit . '&numban=' . $numban . '&ipview=' . sanitize_text_field($_SERVER['REMOTE_ADDR']);
            $response = wp_remote_get($url);

            if (is_wp_error($response)) {
                // Handle error
                return $content;
            }

            $body = wp_remote_retrieve_body($response);
            $dom = new DOMDocument();

            // Suppress warnings when loading external XML
            libxml_use_internal_errors(true);
            $dom->loadXML($body);
            libxml_clear_errors();

            $banners = $dom->getElementsByTagName('banner');
            $auxban = '
                <div class="banwrp">
                    <div class="bantop">
                        <div class="bantoplnk"><a href="https://www.alcmidia.com.br/ads-exchange">Ads Exchange</a></div>
                    </div>';

            $i = 0;
            foreach ($banners as $banner) {
                $i++;
                if (($i > $options['posts_nro'] && is_single()) ||
                    ($i > $options['pages_nro'] && is_page()) ||
                    (class_exists('WooCommerce') && ($i > $options['prods_nro'] && is_product()))) {
                    break;
                }

                $valban = sanitize_text_field($banner->getElementsByTagName("idban")->item(0)->nodeValue);
                $valtit = sanitize_text_field($banner->getElementsByTagName("title")->item(0)->nodeValue);
                $valli1 = sanitize_text_field($banner->getElementsByTagName("line1")->item(0)->nodeValue);
                $valli2 = sanitize_text_field($banner->getElementsByTagName("line2")->item(0)->nodeValue);
                $valsit = sanitize_text_field($banner->getElementsByTagName("site")->item(0)->nodeValue);
                $valurl = esc_url_raw($banner->getElementsByTagName("url")->item(0)->nodeValue);

                $auxban .= '
                    <div class="banban">
                        <a onclick="window.open(\'https://www.alcmidia.com.br/aplicativos/ads-exchange/ads-click.php?codsit=' . esc_attr($codsit) . '&codban=' . esc_attr($valban) . '\', \'_blank\');" href="#" rel="nofollow"><b>' . esc_html($valtit) . '</b></a><br>
                        ' . esc_html($valli1) . '<br>' . esc_html($valli2) . '<br>
                        <a onclick="window.open(\'https://www.alcmidia.com.br/aplicativos/ads-exchange/ads-click.php?codsit=' . esc_attr($codsit) . '&codban=' . esc_attr($valban) . '\', \'_blank\');" href="#" rel="nofollow">' . esc_html($valsit) . '</a>
                    </div>
                ';
            }

            $auxban .= '</div>';
            return $auxban . $content;
        } else {
            return $content;
        }
    }
}
?>
