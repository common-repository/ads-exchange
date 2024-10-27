<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
  }

add_action( 'admin_init', 'banexc_options_init' );
add_action( 'admin_menu', 'banexc_options_page' );

function banexc_options_init(){
    register_setting(
        'banexc_options_group',
        'banexc_options',
        'banexc_options_validate'
    );
}

// insert options
function banexc_options_validate( $input ) {
    // do some validation here if necessary
    return $input;
}

function banexc_options_page() {
    $style = add_menu_page(
        'Ads Exchange',
        'Ads Exchange',
        'manage_options',
        'banexc_options',
        'banexc_render_options'
    );
    add_submenu_page('banexc_options','Settings','Settings','manage_options','banexc_options','banexc_render_options');
    add_submenu_page('banexc_options','Insert Your Add','Insert Your Ad ','manage_options','ad_insert','banexc_insert_fields');
    add_submenu_page('banexc_options','Ads Report','Ads Report','manage_options','ads_report','banexc_report_page');

    // Check screen base and page
    if (isset($_GET['page']) && ($_GET['page'] === 'banexc_options' || $_GET['page'] === 'ad_insert' || $_GET['page'] === 'ads_report')) {
        // Enfileirar o CSS
        wp_register_style( 'adminPluginStylesheet', plugins_url( '../css/admin.css', __FILE__ ) );
        wp_enqueue_style( 'adminPluginStylesheet' );

        // Enfileirar o JavaScript
        wp_register_script( 'adminPluginScript', plugins_url( '../js/admin-script.js', __FILE__ ), array('jquery'), null, true );
        wp_enqueue_script( 'adminPluginScript' );
    }

}


// setting link on plugins page
add_filter( 'plugin_action_links_ads-exchange/ads-exchange.php', 'banexc_settings_link' );
function banexc_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		'page',
		'banexc_options',
		get_admin_url() . 'admin.php'
	) );
	// Create the link.
	$settings_link = "<a href='$url'>". __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}//end banexc_settings_link()


?>