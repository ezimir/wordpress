<?php
/*
Plugin Name: Page Link Widget
Version: 1.0
Description: Link to page with optional description
Author: Martin Tóth
*/

function pagelinkwidget_internationalization() {
    load_plugin_textdomain('pagelinkwidget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'pagelinkwidget_internationalization');


class PageLinkWidget extends WP_Widget {
    function PageLinkWidget() {
        $widget_ops = array('classname' => 'PageLinkWidget', 'description' => __('Link to single page', 'pagelinkwidget'));
        $this->WP_Widget('PageLinkWidget', __('PageLink', 'pagelinkwidget'), $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
        $title = $instance['title'];
        $description = $instance['description'];
        $selected_page = $instance['page'];
        $link_text = $instance['link_text'];
        $icon_class = $instance['icon_class'];

        $pages = get_pages();
    ?>
    <p><label><?php _e( 'Title', 'pagelinkwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
    <p><label><?php _e( 'Description', 'pagelinkwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" type="text" value="<?php echo attribute_escape($description); ?>" /></label></p>
    <p><label><?php _e( 'Page', 'pagelinkwidget' ) ?>:
        <select name="<?php echo $this->get_field_name('page'); ?>">
        <?php foreach ($pages as $page ) { ?>
            <option value="<?php echo $page->ID; ?>"<?php if ($page->ID == $selected_page) { echo 'selected="selected"';} ?>>#<?php echo $page->ID; ?>: <?php echo $page->post_title; ?> /<?php echo $page->post_name; ?>/</option>
        <?php } ?>
        </select>
    </label></p>
    <p><label><?php _e( 'Link Text', 'pagelinkwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo attribute_escape($link_text); ?>" /></label></p>
    <p><label><?php _e( 'Icon Class', 'pagelinkwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('icon_class'); ?>" name="<?php echo $this->get_field_name('icon_class'); ?>" type="text" value="<?php echo attribute_escape($icon_class); ?>" /></label></p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['description'] = $new_instance['description'];
        $instance['page'] = $new_instance['page'];
        $instance['link_text'] = $new_instance['link_text'];
        $instance['icon_class'] = $new_instance['icon_class'];

        return $instance;
    }

    function widget($args, $instance) {
        global $wpdb;

        extract($args, EXTR_SKIP);

        $before_widget = str_replace('class="', 'class="' . $instance['icon_class'] . ' ', $before_widget);

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;;
        }

        if (!empty($instance['description'])) {
            echo $instance['description'];
        }

        echo '<p><a href="' . get_permalink( $instance['page'] ) . '">' . $instance['link_text'] . '</a></p>';

        echo $after_widget;
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("PageLinkWidget");') );

