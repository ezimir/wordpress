<?php


class Options {
    var $category = 'pipedrive-settings';
    var $options;

    public function __construct() {
        $this->options = get_option($this->category);
    }

    public function get($option_name) {
        return $this->options[$option_name];
    }
}


class OptionsPage extends Options {

    public function __construct() {
        parent::__construct();

        add_action( 'admin_init', array($this, 'register_settings') );
        add_action( 'admin_menu', array($this, 'add_options_page') );
    }

    public function register_settings() {
        add_settings_section( 'api', __( 'Pipedrive API' ), array( $this, 'display_section' ), $_GET['page'] );
        add_settings_field( 'api-token', __( 'API token' ), array( $this, 'display_setting' ), $_GET['page'], 'api' );

        register_setting( $this->category, $this->category );
    }

    public function display_section() {
    }

    public function display_setting( $args = array() ) {
        echo '<input class="regular-text" type="text" id="api-token" name="' . $this->category . '[api-token]" value="' . esc_attr( $this->options['api-token'] ) . '" />';
    }

    public function add_options_page() {
        add_options_page( __( 'Pipedrive Deal Form' ), __( 'Pipedrive Deal Form' ), 'manage_options', 'pipedrive-options', array($this, 'display_options_page') );
    }

    public function display_options_page() {
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
        settings_fields( $this->category );
        do_settings_sections( $_GET['page'] );
?>
        <p class="submit">
            <button type="submit" class="button-primary"> <?php _e('Save Changes'); ?> </button>
        </p>
    </form>
</div>
<?php
    }


}

if ( is_admin() ) {
    new OptionsPage();
}

?>
