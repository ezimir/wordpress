<?php
/*
Plugin Name: Partners Widget
Version: 1.0
Description: List of images selected by media tag
Author: Martin Tóth
*/

if (!function_exists('sort_media_by_title')) {
    function sort_media_by_title($a, $b) {
        if ($a->post_title == $b->post_title) return 0;
        return ($a->post_title > $b->post_title) ? 1 : -1;
    }
}

function partnerswidget_get_list_html( $media_tag ) {
    $media = get_attachments_by_media_tags('media_tags=' . $media_tag);
    if (!count($media)) {
        return '';
    }

    usort($media, 'sort_media_by_title');

    $result = '<p>';
    foreach ($media as $image) {
        if (strlen($image->post_excerpt) > 0) {
            $result .= '<a href="' . $image->post_excerpt . '" class="partner"><img src="' . $image->guid . '" /></a>';
        } else {
            $result .= '<img src="' . $image->guid . '" class="partner" />';
        }
    }
    $result .= '</p>';

    return $result;
}

function partnerswidget_showlist( $atts ) {
    return '<div class="PartnersList">' . partnerswidget_get_list_html( $atts['mediatag'] ) . '</div>';
}
add_shortcode( 'partners', 'partnerswidget_showlist' );

function partnerswidget_internationalization() {
    load_plugin_textdomain('partnerswidget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'partnerswidget_internationalization');


class PartnersWidget extends WP_Widget {
    function PartnersWidget() {
        $widget_ops = array('classname' => 'PartnersWidget', 'description' => __('Partner list', 'partnerswidget'));
        $this->WP_Widget('PartnersWidget', __('Partners', 'partnerswidget'), $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array( 'title' => '', 'mediatag' => '' ));
        $title = $instance['title'];
        $mediatag = $instance['mediatag'];
    ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'partnerswidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
    <p><label for="<?php echo $this->get_field_id('mediatag'); ?>"><? _e( 'Tag', 'partnerswidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('mediatag'); ?>" name="<?php echo $this->get_field_name('mediatag'); ?>" type="text" value="<?php echo attribute_escape($mediatag); ?>" /></label></p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['mediatag'] = $new_instance['mediatag'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (!empty($title))
        echo $before_title . $title . $after_title;;

        echo partnerswidget_get_list_html( $instance['mediatag'] );

        echo $after_widget;
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("PartnersWidget");') );

?>
