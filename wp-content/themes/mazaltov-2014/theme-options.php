<?php

// Theme settings
function mazaltov_theme_options_init() {
    register_setting(
        'mazaltov_options', // Options group, see settings_fields() call in twentyeleven_theme_options_render_page()
        'mazaltov_theme_options', // Database option, see twentyeleven_get_theme_options()
        'mazaltov_theme_options_validate' // The sanitization callback, see twentyeleven_theme_options_validate()
    );

    // Register our settings field group
    add_settings_section(
        'general', // Unique identifier for the settings section
        '', // Section title (we don't want one)
        '__return_false', // Section callback (we don't want anything)
        'theme_options' // Menu slug, used to uniquely identify the page; see twentyeleven_theme_options_add_page()
    );

    // Register our individual settings fields
    add_settings_field( 'highlighted_category',     __( 'Highlighted Category',     'mazaltov' ), 'mazaltov_settings_render_fields', 'theme_options', 'general', array( 'name' => 'highlighted_category' ) );
    add_settings_field( 'highlighted_count',        __( 'Highlighted Count',        'mazaltov' ), 'mazaltov_settings_render_fields', 'theme_options', 'general', array( 'name' => 'highlighted_count' ));
    add_settings_field( 'highlighted_hide_empty',   __( 'Highlighted Hide Empty',   'mazaltov' ), 'mazaltov_settings_render_fields', 'theme_options', 'general', array( 'name' => 'highlighted_hide_empty' ));
}
add_action( 'admin_init', 'mazaltov_theme_options_init' );


$mazaltov_theme_defaults = array(
    'highlighted_category' => 'highlighted',
    'highlighted_count' => 2,
    'hide_empty' => false,
);


function mazaltov_theme_options_validate( $input ) {
    global $mazaltov_theme_defaults;

    foreach ( $mazaltov_theme_defaults as $key => $value ) {
        if ( !isSet( $input[$key] ) ) {
            $input[$key] = $value;
        }
    }

    $input['highlighted_count'] = (int)$input['highlighted_count'];
    $input['highlighted_hide_empty'] = (bool)$input['highlighted_hide_empty'];

    return $input;
}


function mazaltov_settings_render_fields( $args ) {
    $options = get_option( 'mazaltov_theme_options', $mazaltov_theme_defaults );

    $name = $args[ 'name' ];
    $value = $options[ $name ];

    if ( is_bool( $value ) ) {
        echo '<input type="checkbox" name="mazaltov_theme_options[' . $name . ']" ' . ( $value ? 'checked="checked"' : '' ) . '" />';
    } else {
        echo '<input type="text" name="mazaltov_theme_options[' . $name . ']" value="' . esc_attr( $value ) . '" />';
    }
}

function mazaltov_theme_options_render_page() {
?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
        <h2><?php printf( __( '%s Theme Options', 'mazaltov' ), $theme_name ); ?></h2>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php
                settings_fields( 'mazaltov_options' );
                do_settings_sections( 'theme_options' );
                submit_button();
            ?>
        </form>
    </div>
<?php
}

function mazaltov_theme_options_add_page() {
    $theme_page = add_theme_page(
        __( 'Theme Options', 'mazaltov' ),   // Name of page
        __( 'Theme Options', 'mazaltov' ),   // Label in menu
        'edit_theme_options',                // Capability required
        'theme_options',                     // Menu slug, used to uniquely identify the page
        'mazaltov_theme_options_render_page' // Function that renders the options page
    );
}
add_action( 'admin_menu', 'mazaltov_theme_options_add_page' );

?>
