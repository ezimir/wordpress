<?php
/*
Plugin Name: Contact Widget
Version: 1.0
Description: Simple contact widget
Author: Martin Tóth
*/

function contactwidget_internationalization() {
    load_plugin_textdomain('contactwidget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'contactwidget_internationalization');


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

        echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title', 'contactwidget') . ': <input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . attribute_escape($title) . '" class="widefat" type="text" /></label></p>';
        echo '<p><label for="' . $this->get_field_id('address') . '">' . __('Address', 'contactwidget') . ': <input id="' . $this->get_field_id('address') . '" name="' . $this->get_field_name('address') . '" value="' . attribute_escape($address) . '" class="widefat" type="text" /></label></p>';
        echo '<p><label for="' . $this->get_field_id('phone') . '">' . __('Phone', 'contactwidget') . ': <input id="' . $this->get_field_id('phone') . '" name="' . $this->get_field_name('phone') . '" value="' . attribute_escape($phone) . '" class="widefat" type="text" /></label></p>';
        echo '<p><label for="' . $this->get_field_id('email') . '">' . __('Email', 'contactwidget') . ': <input id="' . $this->get_field_id('email') . '" name="' . $this->get_field_name('email') . '" value="' . attribute_escape($email) . '" class="widefat" type="text" /></label></p>';
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;;
        }

        echo '<p>' . $instance['address'] . '</p>';
        echo '<p> ' . __('P', 'contactwidget') . ': ' . $instance['phone'] . '</p>';
        echo '<p> ' . __('E', 'contactwidget') . ': ' . $instance['email'] . '</p>';

        echo $after_widget;
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("ContactWidget");') );

?>
