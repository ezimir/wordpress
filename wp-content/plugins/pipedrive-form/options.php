<?php


$email_template = 'Hey You!

Someone wants to work with you, here\'s some info:

Person: {person.link}
    Name: {person.name}
    Email: {person.name}
    Phone: {person.phone}

Organization: {organization.link}
    Name: {organization.name}
    Address: {organization.address}
    Web: {organization.web}

Here\'s a deal in Pipedrive CRM:
    {deal.link}

Here\'s what they wrote:
{notes}

Get back to them soon!

WP ' . get_bloginfo( 'name' );


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
        global $email_template;

        $section = $this->addSection( 'api', __( 'Pipedrive API' ) );
            $this->addField( $section, 'api-token', __( 'API token' ) );

        $api_token = $this->get('api-token');
        if ( !$api_token ) {
            $section->error = __( 'Provide API token for full setup.' );
            return;
        }

        $api = new Pipedrive( $api_token );
        if ( !$api->has_connection ) {
            $section->error = __( 'API token is invalid. Provide API token for full setup.' );
            return;
        }

        $section = $this->addSection( 'email', __( 'Notification Email' ) );
            $this->addField( $section, 'email-address', __( 'Email' ), 'text' );
            $this->addField( $section, 'email-subject', __( 'Subject' ), 'text', false, 'WP: {deal.name}' );
            $this->addField( $section, 'email-template', __( 'Template' ), 'textarea', false, $email_template );

        $section = $this->addSection( 'organization', __( 'Organization Fields' ) );
            $choices = $this->getChoices( $api->getList( 'users' ) );
            $this->addField( $section, 'organization-owner', __( 'Owner' ), 'select', $choices );

            $fields = $api->getList( 'organizationFields' );
            $sets = array_filter( $fields, function ( $field ) {
                return in_array( $field->field_type,  array('set', 'enum') );
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

        $section = $this->addSection( 'person-fields', __( 'Person Fields' ) );
            $choices = $this->getChoices( $api->getList( 'personFields' ) );
            $this->addField( $section, 'person-name', __( 'Name' ), 'select', $choices );
            $this->addField( $section, 'person-email', __( 'Email' ), 'select', $choices );
            $this->addField( $section, 'person-phone', __( 'Phone' ), 'select', $choices );

        $section = $this->addSection( 'deal-fields', __( 'Deal Fields' ) );
            $choices = $this->getChoices( $api->getList( 'stages' ) );
            $this->addField( $section, 'deal-stage', __( 'Stage' ), 'select', $choices );

        $section = $this->addSection( 'success-page', __( 'Success Page' ) );
            $pages = array();
            foreach ( get_pages() as $page ) {
                $pages[] = (object) array(
                    'id' => $page->ID,
                    'name' => $page->post_title . ' (/' . $page->post_name . ')'
                );
            }
            $choices = $this->getChoices( $pages );
            $this->addField( $section, 'success-page', __( 'Page' ), 'select', $choices );
    }

    public function getChoices($fields) {
        return array_map( function ( $field ) {
            $attributes = array(
                'value' => (string) (isSet( $field->key ) ? $field->key : $field->id ),
                'title' => $field->name
            );
            if ( in_array( $field->field_type,  array('set', 'enum') ) ) {
                $attributes['options'] = array_map( function ( $option ) {
                    return (object) array(
                        'value' => (string) $option->id,
                        'title' => $option->label
                    );
                }, $field->options );
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

    public function addField( $section, $id, $title, $type = 'text', $choices = array(), $default = '' ) {
        $field = array(
            'id' => $id,
            'name' => $this->category . '[' . $id . ']',
            'title' => $title,
            'type' => $type,
            'value' => $this->options[$id],
            'default' => $default
        );
        if ($type === 'select') {
            $field['choices'] = (object) $choices;

            $withoptions = array_filter( $choices, function ( $choice ) {
                return (boolean) $choice->options;
            } );
            if ( count( $withoptions ) > 0 ) {
                $field['optionname'] = $this->category . '[' . $id . '-option]';
                $field['optionvalue'] = $this->options[$id . '-option'];
            }
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

    public function displaySectionDescription( $section ) {
        $section_obj = $this->sections[ $section[ 'id' ] ];

        if ( isSet( $section_obj->error ) ) {
            echo '<div class="error below-h2">' . $section_obj->error . '</div>';
        }
    }

    public function displayField( $args ) {
        extract( $args );

        if ($type === 'text') {
            echo '<input class="regular-text" type="text" id="' . $id . '" name="' . $name . '" value="' . esc_attr( $value ? $value : $default ) . '" />';
        }

        if ($type === 'textarea') {
            echo '<textarea class="large-text" id="' . $id . '" name="' . $name . '" rows="10">' . esc_attr( $value ? $value : $default ) . '</textarea>';
        }

        if ($type === 'select') {
            echo '<select id="' . $id . '" name="' . $name . '">';
            echo '<option value=""> -- select -- </option>';
            foreach ($choices as $choice) {
                echo '<option value="' . $choice->value . '"' . ($choice->value === $value ? ' selected="selected"' : '')  . '>' . $choice->title . '</option>';
            }
            echo '</select>';

            $withoptions = array_filter( (array) $choices, function ( $choice ) {
                return (boolean) $choice->options;
            } );
            foreach ($withoptions as $choice) {
                echo '<select id="' . $choice->value . '-option" name="' . $optionname . '">';
                echo '<option value=""> -- select -- </option>';
                foreach ($choice->options as $option) {
                    echo '<option value="' . $option->value . '"' . ($option->value === $optionvalue ? ' selected="selected"' : '')  . '>' . $option->title . '</option>';
                }
                echo '</select>';
            }
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
        <script>
            var $ = jQuery;

            $('form select[name$="option]"]').hide().prop('disabled', true);

            $('form select:not([name$="option]"])')
                .on('change', function () {
                    var $select = $(this),
                        value = $select.val();

                    $select.parents('td').find('select[name$="option]"]').hide().prop('disabled', true);

                    if (value) {
                        $('#' + value + '-option').show().prop('disabled', false);
                    }
                })
                .each(function (index, elem) {
                    $('#' + $(elem).val() + '-option').show().prop('disabled', false);
                });
        </script>
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
