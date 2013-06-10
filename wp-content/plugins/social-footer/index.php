<?php
/*
Plugin Name: Social Icons + Logo Widget
Version: 1.0
Description: Displays an image and icons for social links
Author: Martin Tóth
*/


function socialfooter_get_plugin_file_url( $file ) {
    $plugin_dir = explode( '/', dirname( __FILE__ ) );
    $plugin_name = $plugin_dir[ count( $plugin_dir ) - 1 ];

    $plugin_url = plugins_url( '/' ) . $plugin_name;

    return $plugin_url . '/' . $file;
}


function socialfooterwidget_internationalization() {
    load_plugin_textdomain( 'socialfooterwidget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'socialfooterwidget_internationalization' );


function socialfooterwidget_add_stylesheet() {
    wp_register_style( 'socialfooter-style', socialfooter_get_plugin_file_url( 'style.css' ) );
    wp_enqueue_style( 'socialfooter-style' );
}

add_action( 'wp_enqueue_scripts', 'socialfooterwidget_add_stylesheet' );


class SocialFooterWidget extends WP_Widget {
    var $links = array( 'facebook', 'twitter', 'google', 'email', 'rss' );

    function SocialFooterWidget() {
        $widget_ops = array( 'classname' => 'SocialFooterWidget', 'description' => __( 'Link to single page', 'socialfooterwidget' ) );
        $this->WP_Widget( 'SocialFooterWidget', __( 'Social Footer', 'socialfooterwidget' ), $widget_ops );
    }

    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'logo_url' => '' ));
        $logo_url = $instance[ 'logo_url' ];
?>
    <p><label><?php _e( 'Logo URL', 'socialfooterwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'logo_url' ); ?>" name="<?php echo $this->get_field_name( 'logo_url' ); ?>" type="text" value="<?php echo attribute_escape( $logo_url ); ?>" /></label></p>
<?php
        foreach ( $this->links as $link_name ) {
            $field_name = $link_name . '_link';
            $field = $instance[ $field_name ];
?>
    <p><label><?php echo ucfirst( $link_name ) . ' ' . __( 'Link', 'socialfooterwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id( $field_name ); ?>" name="<?php echo $this->get_field_name( $field_name ); ?>" type="text" value="<?php echo attribute_escape( $field ); ?>" /></label></p>

<?php
        }
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'logo_url' ] = $new_instance[ 'logo_url' ];

        foreach ( $this->links as $link_name ) {
            $field_name = $link_name . '_link';
            $instance[ $field_name ] = $new_instance[ $field_name ];
        }

        return $instance;
    }

    function widget($args, $instance) {
        global $wpdb;

        extract($args, EXTR_SKIP);

        echo $before_widget;

        if ( $instance['logo_url'] ) {
            echo '<img class="logo" src="' . $instance[ 'logo_url' ] . '" />';
        }

        foreach ( $this->links as $link_name ) {
            $field = $instance[ $link_name . '_link' ];

            if ( $field ) {
                echo '<a href="' . $field . '""><img src="' . socialfooter_get_plugin_file_url( 'images/icon-' . $link_name .'.png' ) . '" /></a>';
            }
        }

        echo $after_widget;
    }
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "SocialFooterWidget" );' ) );

?>
