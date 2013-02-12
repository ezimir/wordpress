<?php
/*
Plugin Name: Pipedrive Deal Form
Version: 1.0
Description: Adds a deal to Pipedrive CRM
Author: Martin Tóth
*/

$plugin_dir = plugin_dir_path( __FILE__ );
include_once $plugin_dir . 'pipedrive.php';
include_once $plugin_dir . 'options.php';


function clean( $input ) {
    $input = trim( htmlentities( strip_tags( $input, ',' ) ) );

    if ( get_magic_quotes_gpc() )
        $input = stripslashes( $input );

    $input = mysql_real_escape_string( $input );

    return $input;
}

function pipedrive_shortcode() {
    $options = new Options();
    $pipedrive = new Pipedrive( $options->get( 'api-token' ) );

    $form = array(
        'organization' => array(
            'name' => '',
            'address' => '',
            'web' => ''
        ),
        'person' => array(
            'name' => '',
            'email' => '',
            'phone' => ''
        )
    );

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        foreach ( $form as $name => $section ) {
            foreach ( $section as $field => $default ) {
                $value = clean( $_POST[$name . '-' . $field] );
                if ($value) {
                    $form[$name][$field] = $value;
                }
            }
        }
        $form['organization']['relation'] = $options->get( 'organization-relation-option' );

        $attrs = array();
        $owner = $options->get( 'organization-owner' );
        if ( $owner ) {
            $attrs['owner_id'] = $owner;
        }

        $organization_attrs = $attrs;
        $fields = array( 'relation', 'name', 'address', 'web' );
        foreach ( $fields as $field ) {
            $attr = $options->get( 'organization-' . $field );
            if ( $attr ) {
                $organization_attrs[$attr] = $form['organization'][$field];
            }
        }
        $organization = $pipedrive->getOrCreate( 'organizations', $form['organization']['name'], $organization_attrs );

        $person_attrs = $attrs;
        $person_attrs['org_id'] = $organization->id;
        $fields = array( 'name', 'email', 'phone' );
        foreach ( $fields as $field ) {
            $attr = $options->get( 'person-' . $field );
            if ( $attr ) {
                $person_attrs[$attr] = $form['person'][$field];
            }
        }
        $person = $pipedrive->getOrCreate( 'persons', $form['person']['name'], $person_attrs );

        $deal_attrs = array(
            'title' => '"' . $organization->name . '" - web lead',
            'org_id' => $organization->id,
            'person_id' => $person->id
        );
        if ( $owner ) {
            $deal_attrs['user_id'] = $owner;
        }
        $stage = $options->get( 'deal-stage' );
        if ( $stage ) {
            $deal_attrs['stage_id'] = $stage;
        }
        $deal = $pipedrive->create( 'deals', $deal_attrs );

        var_dump( $deal );
    }
    ob_start();
?>
<link rel="stylesheet" href="<?php echo plugins_url('pipedrive-form/style.css'); ?>" />
<form class="pipedrive" method="post">
    <fieldset>
        <legend> Kto ste? </legend>

        <label> Meno
            <input type="text" class="regular-text" name="person-name" value="<?php echo $form['person']['name']; ?>" />
        </label>
        <label> Email
            <input type="email" class="regular-text" name="person-email" value="<?php echo $form['person']['email']; ?>" />
        </label>
        <label> Mobil
            <input type="text" name="person-phone" value="<?php echo $form['person']['phone']; ?>" />
        </label>
    </fieldset>

    <fieldset>
        <legend> Koho zastupujete? </legend>

        <label> Názov spoločnosti
            <input type="text" name="organization-name" value="<?php echo $form['organization']['name']; ?>" />
        </label>
        <label> Sídlo
            <input type="text" name="organization-address" value="<?php echo $form['organization']['address']; ?>" />
        </label>
        <label> Web
            <input type="url" name="organization-web" value="<?php echo $form['organization']['web']; ?>" />
        </label>
    </fieldset>


    <fieldset>
        <legend> Ako vám pomôžeme? </legend>

        <label>
            <textarea></textarea>
        </label>
    </fieldset>

    <button> Odoslať </button>
</form>
<?php
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

add_shortcode( 'pipedrive', 'pipedrive_shortcode' );

?>
