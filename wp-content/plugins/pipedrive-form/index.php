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

        $organization = $pipedrive->getOrganization( $form['organization']['name'], (object) array(
            'owner_id' => $options->get( 'organization-owner' ),
            $options->get( 'organization-relation' ) => $options->get( 'organization-relation-option' ),

            $options->get( 'organization-name' ) => $form['organization']['name'],
            $options->get( 'organization-address' ) => $form['organization']['address'],
            $options->get( 'organization-web' ) => $form['organization']['web']
        ) );
        var_dump( $organization );
    }
    ob_start();
?>
<link rel="stylesheet" href="<?php echo plugins_url('pipedrive-form/style.css'); ?>" />
<form class="pipedrive" method="post">
    <fieldset>
        <legend> Kto ste? </legend>

        <label> Meno
            <input type="text" class="regular-text" />
        </label>
        <label> Email
            <input type="email" class="regular-text" />
        </label>
        <label> Mobil
            <input type="text" />
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
