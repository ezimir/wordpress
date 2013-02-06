<?php
/*
Plugin Name: Contact Widget
Version: 1.0
Description: Simple contact widget
Author: Martin Tóth
*/


class ContactWidget extends WP_Widget {
    function ContactWidget() {
        $widget_ops = array('classname' => 'ContactWidget', 'description' => __('Simple contact widget', 'contactwidget'));
        $this->WP_Widget('ContactWidget', __('Contact', 'contactwidget'), $widget_ops);
    }

    function form($instance) {
        $args = array( 'title' => '', 'address' => '', 'phone' => '', 'email' => '' );
        $instance = wp_parse_args((array) $instance, $args);
        foreach ($args as $arg => $default) {
            $$arg = $instance[$arg];
        }

        foreach ($args as $arg => $default) {
    ?>
        <p><label for="<?php echo $this->get_field_id($arg); ?>"><?php _e( ucwords($arg), 'contactwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id($arg); ?>" name="<?php echo $this->get_field_name($arg); ?>" type="text" value="<?php echo attribute_escape($$arg); ?>" /></label></p>
    <?php
        }
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;;
        }

        echo '<p>' . $instance['address'] . '</p>';
        echo '<p> T: ' . $instance['phone'] . '</p>';
        echo '<p> E: ' . $instance['email'] . '</p>';

        echo $after_widget;
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("ContactWidget");') );

?>
