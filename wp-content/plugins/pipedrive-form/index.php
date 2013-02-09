<?php
/*
Plugin Name: Pipedrive Deal Form
Version: 1.0
Description: Adds a deal to Pipedrive CRM
Author: Martin Tóth
*/

$pipedrive_settings = array(
    'category' => 'pipedrive-settings',
    'section' => 'pipedrive-api',
    'apikeyname' => 'pipedrive-apikey',
);

include_once plugin_dir_path( __FILE__ ) . 'options.php';


function pipedrive_shortcode() {
    ob_start();
?>
<link rel="stylesheet" href="<?php echo plugins_url('pipedrive-form/style.css'); ?>" />
<form class="pipedrive">
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
            <input type="text" />
        </label>
        <label> Sídlo
            <input type="text" />
        </label>
        <label> Web
            <input type="url" />
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
