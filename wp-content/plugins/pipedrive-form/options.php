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
    var $sections = array();

    public function __construct() {
        parent::__construct();

        $section = $this->add_section( 'api', __( 'Pipedrive API' ) );
        $this->add_field( $section, 'api-token', __( 'API token' ) );

        $section = $this->add_section( 'organization', __( 'Organization Related Fields' ) );
        $this->add_field( $section, 'organization-name', __( 'Name' ) );
        $this->add_field( $section, 'organization-address', __( 'Adddress' ) );
        $this->add_field( $section, 'organization-web', __( 'Web' ) );

        $section = $this->add_section( 'person-fields', __( 'Person Related Fields' ) );
        $this->add_field( $section, 'person-name', __( 'Name' ) );
        $this->add_field( $section, 'person-email', __( 'Email' ) );
        $this->add_field( $section, 'person-mobile', __( 'Mobile' ) );

        add_action( 'admin_init', array($this, 'register_settings') );
        add_action( 'admin_menu', array($this, 'add_options_page') );
    }

    public function add_section($id, $title) {
        return $this->sections[$id] = (object) array(
            'id' => $id,
            'title' => $title,
            'fields' => array()
        );
    }

    public function add_field($section, $id, $title, $type = 'text') {
        return $section->fields[$id] = (object) array(
            'id' => $id,
            'name' => $this->category . '[' . $id . ']',
            'title' => $title,
            'type' => $type,
            'value' => $this->options[$id]
        );
    }

    public function register_settings() {
        foreach ($this->sections as $section) {
            add_settings_section( $section->id, $section->title, array( $this, 'display_section' ), $_GET['page'] );
            foreach ($section->fields as $field) {
                add_settings_field( $field->id, $field->title, array( $this, 'display_setting' ), $_GET['page'], $section->id, get_object_vars( $field ) );
            }
        }

        register_setting( $this->category, $this->category );
    }

    public function display_section() {
    }

    public function display_setting( $args ) {
        extract( $args );
        echo '<input class="regular-text" type="text" id="' . $id . '" name="' . $name . '" value="' . esc_attr( $value ) . '" />';
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
