<?php


class Options {
    var $category = 'pipedrive-settings';
    var $options;

    public function __construct() {
        $this->options = get_option( $this->category );
    }

    public function get( $option_name ) {
        return $this->options[$option_name];
    }
}


class OptionsPage extends Options {
    var $sections = array();

    public function __construct() {
        parent::__construct();

        $this->buildFields();

        add_action( 'admin_init', array($this, 'registerSettings') );
        add_action( 'admin_menu', array($this, 'addOptionsPage') );
    }

    public function buildFields() {
        $api = new Pipedrive( $this->get('api-token') );

        $section = $this->addSection( 'api', __( 'Pipedrive API' ) );
            $this->addField( $section, 'api-token', __( 'API token' ) );

        $section = $this->addSection( 'organization', __( 'Organization Related Fields' ) );
            $choices = $this->getChoices( $api->getList( 'users' ) );
            $this->addField( $section, 'organization-owner', __( 'Owner' ), 'select', $choices );

            $fields = $api->getList( 'organizationFields' );
            $sets = array_filter( $fields, function ( $field ) {
                return $field->field_type === 'set';
            } );
            $choices = $this->getChoices( $sets );
            $this->addField( $section, 'organization-relation', __( 'Relation' ), 'select', $choices );

            $varchars = array_filter( $fields, function ( $field ) {
                return $field->field_type === 'varchar';
            } );
            $choices = $this->getChoices( $varchars );
            $this->addField( $section, 'organization-name', __( 'Name' ), 'select', $choices );
            $this->addField( $section, 'organization-address', __( 'Address' ), 'select', $choices );
            $this->addField( $section, 'organization-web', __( 'Web' ), 'select', $choices );

        $section = $this->addSection( 'person-fields', __( 'Person Related Fields' ) );
            $choices = $this->getChoices( $api->getList( 'personFields' ) );
            $this->addField( $section, 'person-name', __( 'Name' ), 'select', $choices );
            $this->addField( $section, 'person-email', __( 'Email' ), 'select', $choices );
            $this->addField( $section, 'person-phone', __( 'Phone' ), 'select', $choices );

    }

    public function getChoices($fields) {
        return array_map( function ( $field ) {
            $attributes = array(
                'value' => (string) $field->id,
                'title' => $field->name
            );
            if ( $field->field_type === 'set' ) {
                $attributes['options'] = (object) $field->options;
            }
            return (object) $attributes;
        }, $fields );
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
        foreach ( $this->sections as $section ) {
            add_settings_section( $section->id, $section->title, array( $this, 'displaySectionDescription' ), $_GET['page'] );
            foreach ( $section->fields as $field ) {
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
            echo '<option> -- select -- </option>';
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
