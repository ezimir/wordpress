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

function parse_template( $template, $values) {
    $result = $template;

    preg_match_all( '/{([\w.]+)}/', $template, $placeholders );
    foreach( $placeholders[1] as $placeholder ) {
        $result = str_replace( '{' . $placeholder . '}', $values[$placeholder], $result );
    }

    return $result;
}

function send_email( $data ) {
    extract( $data );

    $subject = parse_template( $subject, $values );
    $template = parse_template( $template, $values );

    return wp_mail( $address, $subject, $template );
}

function pipedrive_shortcode() {
    $pipedrive_app = 'https://app.pipedrive.com/';

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
        ),
        'notes' => array(
            'text' => ''
        )
    );

    $succes = false;
    $errors = array();

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

        $template_values = array();

        $organization_attrs = $attrs;
        $fields = array( 'relation', 'name', 'address', 'web' );
        foreach ( $fields as $field ) {
            $attr = $options->get( 'organization-' . $field );
            if ( $attr ) {
                $organization_attrs[$attr] = $form['organization'][$field];
                $template_values['organization.' . $field] = $organization_attrs[$attr];
            }
        }
        $organization = $pipedrive->getOrCreate( 'organizations', $form['organization']['name'], $organization_attrs );
        $template_values['organization.link'] = $pipedrive_app . 'org/details/' . $organization->id;

        $person_attrs = $attrs;
        $person_attrs['org_id'] = $organization->id;
        $fields = array( 'name', 'email', 'phone' );
        foreach ( $fields as $field ) {
            $attr = $options->get( 'person-' . $field );
            if ( $attr ) {
                $person_attrs[$attr] = $form['person'][$field];
                $template_values['person.' . $field] = $person_attrs[$attr];
            }
        }
        $person = $pipedrive->getOrCreate( 'persons', $form['person']['name'], $person_attrs );
        $template_values['person.link'] = $pipedrive_app . 'person/details/' . $person->id;

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
        $template_values['deal.name'] = $deal->title;
        $template_values['deal.link'] = $pipedrive_app . 'deal/view/' . $deal->id;

        $template_values['notes'] = $form['notes']['text'];

        $address = $options->get( 'email-address' );
        if ( $address ) {
            $sent = send_email( array(
                'address' => $address,
                'subject' => $options->get( 'email-subject' ),
                'template' => $options->get( 'email-template' ),
                'values' => $template_values
            ) );
            if ( !$sent ) {
                $errors[] = 'Couldn\'t send email.';
            } else {
                $success = true;
            }
        }
    }
    ob_start();
?>
<link rel="stylesheet" href="<?php echo plugins_url('pipedrive-form/style.css'); ?>" />
<?php if ( $success ) { ?>
<div class="pipedrive">
    <div class="pipedrive-message success"> Success! We've been notified. </div>

    Thank you for your inquiry. We will get back to you soon!
</div>
<?php } else { ?>
<form class="pipedrive" method="post">
<?php
    if ( count( $errors ) > 0 ) {
        echo '<div class="pipedrive-message error">';
        foreach ( $errors as $error ) {
            echo $error . '<br />';
        }
        echo '</div>';
    }
?>
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
            <textarea name="notes-text"><?php echo $form['notes']['text']; ?></textarea>
        </label>
    </fieldset>

    <button> Odoslať </button>
</form>
<?php } ?>

<?php

    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

add_shortcode( 'pipedrive', 'pipedrive_shortcode' );

?>
