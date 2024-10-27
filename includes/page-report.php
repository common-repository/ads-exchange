<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
  }

function banexc_report_page() {
    // Carregar dados externos com segurança
    $url = 'https://www.alcmidia.com.br/aplicativos/ads-exchange/api-report.php?referer=' . urlencode(get_home_url());
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

    $sites = $dom->getElementsByTagName('site');
    $valid = $valtit = $valurl = $valvisfor = $valvisrec = $valvissal = $valclifor = $valclirec = $valclisal = '';

    foreach ($sites as $site) {
        $valid = sanitize_text_field($site->getElementsByTagName("idsit")->item(0)->nodeValue);
        $valtit = sanitize_text_field($site->getElementsByTagName("title")->item(0)->nodeValue);
        $valurl = esc_url_raw($site->getElementsByTagName("url")->item(0)->nodeValue);
        $valvisfor = intval($site->getElementsByTagName("visfor")->item(0)->nodeValue);
        $valvisrec = intval($site->getElementsByTagName("visrec")->item(0)->nodeValue);
        $valvissal = intval($site->getElementsByTagName("vissal")->item(0)->nodeValue);
        $valclifor = intval($site->getElementsByTagName("clifor")->item(0)->nodeValue);
        $valclirec = intval($site->getElementsByTagName("clirec")->item(0)->nodeValue);
        $valclisal = intval($site->getElementsByTagName("clisal")->item(0)->nodeValue);
    }

    // Início do HTML
    echo '
    <div class="wrap">
        <h1>Ads Report</h1>
        <div class="replin">
            <div class="colunatit">Views Provided</div>
            <div class="colunatit">Views Received</div>
            <div class="colunatit">Views Balance</div>
            <div class="colunatit">Clicks Provided</div>
            <div class="colunatit">Clicks Received</div>
            <div class="colunatit">Clicks Balance</div>
        </div>
        <div class="replin">
            <div class="coluna">' . esc_html($valvisfor) . '</div>
            <div class="coluna">' . esc_html($valvisrec) . '</div>
            <div class="coluna">' . esc_html($valvissal) . '</div>
            <div class="coluna">' . esc_html($valclifor) . '</div>
            <div class="coluna">' . esc_html($valclirec) . '</div>
            <div class="coluna">' . esc_html($valclisal) . '</div>
        </div>
    </div>
    ';
}

?>
