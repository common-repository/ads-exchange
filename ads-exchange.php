<?php
/**
 * Plugin Name: Ads Exchange
 * Plugin URI: https://www.alcmidia.com.br/ads-exchange
 * Description: Exchange views and clicks with other sites for free.
 * Version: 1.08
 * Author: pileggi
 * Author URI: https://www.alcmidia.com.br 
 * Tested up to: 6.5
 * Stable tag: 1.07
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
 
require_once plugin_dir_path(__FILE__) . 'includes/menu.php';

require_once plugin_dir_path(__FILE__) . 'includes/page-config.php';

require_once plugin_dir_path(__FILE__) . 'includes/page-ads.php';

require_once plugin_dir_path(__FILE__) . 'includes/page-report.php';

require_once plugin_dir_path(__FILE__) . 'includes/ads-feed.php';

?>