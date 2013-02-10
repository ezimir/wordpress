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

        $organization = $pipedrive->getOrCreate( 'organizations', $form['organization']['name'], array(
            'owner_id' => $options->get( 'organization-owner' ),
            $options->get( 'organization-relation' ) => $options->get( 'organization-relation-option' ),

            $options->get( 'organization-name' ) => $form['organization']['name'],
            $options->get( 'organization-address' ) => $form['organization']['address'],
            $options->get( 'organization-web' ) => $form['organization']['web']
        ) );

        $email_attr = $options->get( 'person-email' );
        $phone_attr = $options->get( 'person-phone' );
        $person = $pipedrive->getOrCreate( 'persons', $form['person']['name'], array(
            'owner_id' => $options->get( 'organization-owner' ),
            'org_id' => $organization->id,

            $options->get( 'person-name' ) => $form['person']['name'],
            $email_attr => $form['person']['email'],
            $phone_attr => $form['person']['phone']
        ) );
        $update = array();
        if ( $form['person']['email'] ) {
            $emails = $person->$email_attr;
            if ( count( $emails ) === 1 && !$emails[0]->value) {
                $person->$email_attr = array();
            }
            $emails = array_map( function ( $email ) {
                return $email->value;
            }, $person->$email_attr );
            if ( !in_array( $form['person']['email'], $emails ) ) {
                $update['email'] = $person->$email_attr;
                $update['email'][] = array(
                    'value' => $form['person']['email']
                );
            }
        }
        if ( $form['person']['phone'] ) {
            $phones = $person->$phone_attr;
            if ( count( $phones ) === 1 && !$phones[0]->value) {
                $person->$phone_attr = array();
            }
            $phones = array_map( function ( $phone ) {
                return $phone->value;
            }, $person->$phone_attr );
            if ( !in_array( $form['person']['phone'], $phones ) ) {
                $update['phone'] = $person->$phone_attr;
                $update['phone'][] = array(
                    'value' => $form['person']['phone']
                );
            }
        }
        if ( count( $update ) > 0 ) {
            $pipedrive->update( 'persons', $person->id, $update );
        }

        $deal = $pipedrive->create( 'deals', array(
            'user_id' => $options->get( 'organization-owner' ),
            'stage_id' => $options->get( 'deal-stage' ),

            'title' => '"' . $organization->name . '" - web lead',
            'org_id' => $organization->id,
            'person_id' => $person->id
        ) );

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
