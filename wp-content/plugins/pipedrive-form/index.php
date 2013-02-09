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

$plugin_dir = plugin_dir_path( __FILE__ );
include_once $plugin_dir . 'options.php';
include_once $plugin_dir . 'pipedrive.php';



function pipedrive_shortcode() {
    global $pipedrive_settings;

    $options = get_option($pipedrive_settings['category']);
    $api_key = $options[$pipedrive_settings['apikeyname']];

    $pipedrive = new Pipedrive($api_key);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $organization = $pipedrive->get_organization($_POST['organization']);
        var_dump($organization);
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
            <input type="text" name="organization" />
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
