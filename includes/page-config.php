<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Função para adicionar a mensagem de sucesso
function banexc_save_options() {
    if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
        add_settings_error( 'banexc_options', 'banexc_options', 'Settings saved successfully', 'updated' );
    }
}
add_action( 'admin_notices', 'banexc_save_options' );

// Config page
function banexc_render_options() {
    ?>
    <div class="wrap">
        <h1>Ads Exchange Settings</h1>
        <?php settings_errors(); ?>
        <form method="post" action="options.php">
            <?php
            settings_fields('banexc_options_group');
            $options = get_option('banexc_options');
            ?>
            <div class="adslin">
              This plugin sends certain data to an external service for the purpose of displaying ads. The data sent includes:<br>
              </div>
              <div class="adslin">  
              - Banner title and descriptions<br>
              - Site title, domain(s), url and language<br>
              - IP of the user who clicked on the banner
            </div>
            <div class="confsub">Where and how many banners to display:</div>
            <div class="confbox">
                <?php
                $pages_nro = isset($options['pages_nro']) ? intval($options['pages_nro']) : 0;
                $posts_nro = isset($options['posts_nro']) ? intval($options['posts_nro']) : 0;
                $prods_nro = isset($options['prods_nro']) ? intval($options['prods_nro']) : 0;
                ?>
                <div class="conflin">
                    <div class="confagr">
                        <input id="agree_checkbox" name="banexc_options[agree]" type="checkbox" value="true" <?php checked('true', isset($options['agree']) ? $options['agree'] : ''); ?>> I agree to sending the data above
                    </div>
                </div>
                <div class="conflin">
                    <div class="confche">
                        <input name="banexc_options[pages]" type="checkbox" value="true" <?php checked('true', isset($options['pages']) ? $options['pages'] : ''); ?>> Pages
                    </div>
                    <div class="confinp">
                        <input name="banexc_options[pages_nro]" type="number" min="0" max="10" value="<?php echo esc_attr($pages_nro); ?>"> Ads number
                    </div>
                </div>
                <div class="conflin">
                    <div class="confche">
                        <input name="banexc_options[posts]" type="checkbox" value="true" <?php checked('true', isset($options['posts']) ? $options['posts'] : ''); ?>> Posts
                    </div>
                    <div class="confinp">
                        <input name="banexc_options[posts_nro]" type="number" min="0" max="10" value="<?php echo esc_attr($posts_nro); ?>"> Ads number
                    </div>
                </div>
                <div class="conflin">
                    <div class="confche">
                        <input name="banexc_options[prods]" type="checkbox" value="true" <?php checked('true', isset($options['prods']) ? $options['prods'] : ''); ?>> Products
                    </div>
                    <div class="confinp">
                        <input name="banexc_options[prods_nro]" type="number" min="0" max="10" value="<?php echo esc_attr($prods_nro); ?>"> Ads number
                    </div>
                </div>
            </div>
            <p class="submit">
                <input id="submit_button" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" disabled />
            </p>
        </form>
    </div>
    <?php
}

add_action('admin_menu', function() {
    add_options_page('Ads Exchange Settings', 'Ads Exchange', 'manage_options', 'ads-exchange-settings', 'banexc_render_options');
});

add_action('admin_init', function() {
    register_setting('banexc_options_group', 'banexc_options');
});
