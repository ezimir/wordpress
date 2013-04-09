<?php
/*
Plugin Name: Facebook Widget
Version: 1.0
Description: Like button and link to profile
Author: Martin Tóth
*/

function facebookwidget_internationalization() {
    load_plugin_textdomain('facebookwidget', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action('plugins_loaded', 'facebookwidget_internationalization');


class FacebookWidget extends WP_Widget {
    function FacebookWidget() {
        $widget_ops = array('classname' => 'FacebookWidget', 'description' => __('Facebook profile and Like button', 'facebookwidget'));
        $this->WP_Widget('FacebookWidget', __('Facebook', 'partnerswidget'), $widget_ops);
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array( 'title' => '', 'mediatag' => '' ));
        $title = $instance['title'];
        $profile = $instance['profile'];
    ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'facebookwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
    <p><label for="<?php echo $this->get_field_id('profile'); ?>"><? _e( 'Profile', 'facebookwidget' ) ?>: <input class="widefat" id="<?php echo $this->get_field_id('profile'); ?>" name="<?php echo $this->get_field_name('profile'); ?>" type="text" value="<?php echo attribute_escape($profile); ?>" /></label></p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['profile'] = $new_instance['profile'];
        $fb = json_decode(file_get_contents('https://graph.facebook.com/'.$instance['profile']));
        $instance['fb_name'] = $fb->name;
        $instance['fb_about'] = $fb->about;
        $instance['fb_link'] = $fb->link;
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (!empty($title))
        echo $before_title . $title . $after_title;;

        echo '<p> ' . __('Up-to-date information and gossip on our Facebook website', 'facebookwidget') . ': </p>';
        //Vždy čerstvé informácie a pikošky na našej stránke na Facebooku

        echo '<div class="fb">';
        echo '<a href="' . $instance['fb_link'] .'"><img src="https://graph.facebook.com/' . $instance['profile'] .'/picture/normal" /></a>';
        echo '<a href="' . $instance['fb_link'] .'"><strong>' . $instance['fb_name'] . '</strong></a>';
        echo '<p>' . $instance['fb_about'] . '</p>';
        echo '<iframe src="//www.facebook.com/plugins/like.php?href=' . urlencode($instance['fb_link']) . '&amp;locale=' . get_locale() . '&amp;send=false&amp;layout=button_count&amp;width=155&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=trebuchet+ms&amp;height=21&amp;appId=349582238426804" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:155px; height:21px;" allowTransparency="true"></iframe>';
        echo '</div>';

        echo $after_widget;
    }
}
add_action( 'widgets_init', create_function('', 'return register_widget("FacebookWidget");') );

?>
