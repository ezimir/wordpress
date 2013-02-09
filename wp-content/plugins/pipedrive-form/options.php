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

        $section = $this->addSection( 'api', __( 'Pipedrive API' ) );
        $this->addField( $section, 'api-token', __( 'API token' ) );

        $section = $this->addSection( 'organization', __( 'Organization Related Fields' ) );
        $this->addField( $section, 'organization-name', __( 'Name' ), 'select' );
        $this->addField( $section, 'organization-address', __( 'Adddress' ), 'select' );
        $this->addField( $section, 'organization-web', __( 'Web' ), 'select' );

        $section = $this->addSection( 'person-fields', __( 'Person Related Fields' ) );
        $this->addField( $section, 'person-name', __( 'Name' ), 'select' );
        $this->addField( $section, 'person-email', __( 'Email' ), 'select' );
        $this->addField( $section, 'person-mobile', __( 'Mobile' ), 'select' );

        add_action( 'admin_init', array($this, 'registerSettings') );
        add_action( 'admin_menu', array($this, 'addOptionsPage') );
    }

    public function addSection( $id, $title ) {
        return $this->sections[$id] = (object) array(
            'id' => $id,
            'title' => $title,
            'fields' => array()
        );
    }

    public function addField( $section, $id, $title, $type = 'text', $choices = array() ) {
        $field = array(
            'id' => $id,
            'name' => $this->category . '[' . $id . ']',
            'title' => $title,
            'type' => $type,
            'value' => $this->options[$id]
        );
        if ($type === 'select') {
            $field['choices'] = (object) $choices;
        }
        return $section->fields[$id] = (object) $field;
    }

    public function registerSettings() {
        foreach ($this->sections as $section) {
            add_settings_section( $section->id, $section->title, array( $this, 'displaySectionDescription' ), $_GET['page'] );
            foreach ($section->fields as $field) {
                add_settings_field( $field->id, $field->title, array( $this, 'displayField' ), $_GET['page'], $section->id, get_object_vars( $field ) );
            }
        }

        register_setting( $this->category, $this->category );
    }

    public function displaySectionDescription() {
    }

    public function displayField( $args ) {
        extract( $args );
        if ($type === 'text') {
            echo '<input class="regular-text" type="text" id="' . $id . '" name="' . $name . '" value="' . esc_attr( $value ) . '" />';
        }
        if ($type === 'select') {
            echo '<select id="' . $id . '" name="' . $name . '">';
            foreach ($choices as $choice) {
                echo '<option value="' . $choice->value . '"' . ($choice->value === $value ? ' selected="selected"' : '')  . '>' . $choice->title . '</option>';
            }
            echo '</select>';
        }
    }

    public function addOptionsPage() {
        add_options_page( __( 'Pipedrive Deal Form' ), __( 'Pipedrive Deal Form' ), 'manage_options', 'pipedrive-options', array($this, 'displayOptionsPage') );
    }

    public function displayOptionsPage() {
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
