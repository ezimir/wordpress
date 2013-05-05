<?php

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'mazaltov', TEMPLATEPATH . '/languages' );

// Get the page number
function get_page_number() {
    if ( get_query_var( 'paged' ) ) {
        print ' | ' . __( 'Page ' , 'mazaltov' ) . get_query_var( 'paged' );
    }
} // end get_page_number



// Register widgetized areas
function theme_widgets_init() {
    // Area 1
    register_sidebar( array (
        'name' => __( 'Primary Widget Area', 'mazaltov' ),
        'id' => 'primary_widget_area',
        'before_widget' => '<li id="%1$s" class="widget-container well %2$s">',
        'after_widget' => "</li>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
  ) );
} // end theme_widgets_init

add_action( 'init', 'theme_widgets_init' );



$preset_widgets = array (
    'primary_widget_area'  => array( 'search', 'pages', 'categories', 'archives' )
);
if ( isset( $_GET['activated'] ) ) {
    update_option( 'sidebars_widgets', $preset_widgets );
}
// update_option( 'sidebars_widgets', NULL );


// Check for static widgets in widget-ready areas
function is_sidebar_active( $index ) {
    global $wp_registered_sidebars;

    $widgetcolums = wp_get_sidebars_widgets();

    if ( $widgetcolums[$index] ) {
        return true;
    }

    return false;
} // end is_sidebar_active


if ( !function_exists( 'disableAdminBar' ) ) {

    function disableAdminBar() {
        remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );

        function remove_admin_bar_style_frontend() { // css override for the frontend
            echo '<style type="text/css" media="screen">
            html { margin-top: 0px !important; }
            * html body { margin-top: 0px !important; }
            </style>';
        }

        add_filter( 'wp_head','remove_admin_bar_style_frontend', 99 );
    }
}

add_action( 'init', 'disableAdminBar' );


function get_random_header_image_from_media( $mediatag ) {
    $media = get_attachments_by_media_tags( 'media_tags=' . $mediatag );
    if ( count( $media ) === 0 ) {
        return '';
    }

    $random = array_rand( $media );
    return $media[$random]->guid;
}


include_once get_stylesheet_directory() . '/theme-options.php';


add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );

register_nav_menu( 'top', __( 'Top menu in header', 'mazaltov' ) );
register_nav_menu( 'main', __( 'Main menu in header', 'mazaltov' ) );
register_nav_menu( 'footer', __( 'Main menu v footer', 'mazaltov') );

?>
