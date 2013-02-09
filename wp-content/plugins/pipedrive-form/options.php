<?php

$pipedrive_settings = array(
    'category' => 'pipedrive-settings',
    'section' => 'pipedrive-api',
    'apikeyname' => 'pipedrive-apikey',
);


function pipedrive_display_options_page() {
    global $pipedrive_settings;

?>
<div class="wrap">
    <img src="<?php echo plugins_url('pipedrive-form/pipedrive_128.png'); ?>" class="icon32" />
    <h2> <?php _e( 'Pipedrive Deal Form' ) ?> </h2>

    <p>
        <?php _e( 'Set up API key and you can use following shortcode in pages/articles:' ); ?>
        <pre>[pipedrive]</pre>
    </p>

    <form action="options.php" method="post">
<?php
        settings_fields( $pipedrive_settings['category'] );
        do_settings_sections( $_GET['page'] );
?>
        <p class="submit">
            <button type="submit" class="button-primary"> <?php _e('Save Changes'); ?> </button>
        </p>
    </form>
</div>
<?php
}

function pipedrive_add_options_page() {
    $admin_page = add_options_page( __( 'Pipedrive Deal Form' ), __( 'Pipedrive Deal Form' ), 'manage_options', 'pipedrive-options', 'pipedrive_display_options_page' );
}
add_action( 'admin_menu', 'pipedrive_add_options_page' );



function pipedrive_display_section() {
}

function pipedrive_display_setting( $args = array() ) {
    global $pipedrive_settings;

    $options = get_option( $pipedrive_settings['category'] );

    echo '<input class="regular-text" type="text" id="' . $pipedrive_settings['apikeyname'] . '" name="' . $pipedrive_settings['category'] . '[' . $pipedrive_settings['apikeyname'] . ']" value="' . esc_attr( $options[$pipedrive_settings['apikeyname']] ) . '" />';
}

function register_settings() {
    global $pipedrive_settings;

    add_settings_section( $pipedrive_settings['section'], __( 'Pipedrive API' ), 'pipedrive_display_section', $_GET['page'] );
    add_settings_field( $pipedrive_settings['apikeyname'], __( 'API key' ), 'pipedrive_display_setting', $_GET['page'], $pipedrive_settings['section'] );

    register_setting( $pipedrive_settings['category'], $pipedrive_settings['category'] );
}
add_action( 'admin_init', 'register_settings' );

?>
