<?php

	/* ========================================================================================================= */

	/*
		These are functions specific to this theme
	*/

	/* ========================================================================================================= */


	/*
		Add Theme Support
	*/

	add_theme_support( 'post-thumbnails', array( 'post', 'page', 'portfolio' ) ); // Add it for posts
	add_image_size( 'home-page-blog-section-thumbnail', 190, 105 );
	add_theme_support( 'automatic-feed-links' );

	/* ===================================================================================================== */

	/*
		Register our menu locations
	*/

	if( function_exists( 'register_nav_menus' ) )
	{

		function friendly_register_menus()
		{

			register_nav_menus(
				array(
					'main-menu' => __( '01 - Main Menu','impresso' )
				)
			);

		}/* friendly_register_menus() */

		add_action('init','friendly_register_menus');

	}

	/*
		Register our sidebars
	*/

	function impresso_register_sidebars()
	{

		/*
			Register the 'primary' sidebar for use on pages/posts.
		*/

		register_sidebar(
			array(
				'id' => 'primary',
				'name' => __( 'Primary','impresso' ),
				'description' => __( 'The primary sidebar used on pages and posts. By default on the Left Hand Side','impresso' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		/* ===================================================================================================== */

		/*
			Register Footer Widget Area
		*/

		register_sidebar(
			array(
				'id' => 'footer_widget_area',
				'name' => __( 'Footer','impresso' ),
				'description' => __( 'This is the widget area for the footer','impresso' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);

		/* ===================================================================================================== */

		/*
			Register Home Page Widget Area - default used by Client Slider
		*/

		register_sidebar(
			array(
				'id' => 'home_page_widget_area',
				'name' => __( 'Home Page','impresso' ),
				'description' => __( 'This widget area is full-width on the home page, just above the blog section. In the demo, we use the client slider (see widget)','impresso' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		/* ===================================================================================================== */


	}/* impresso_register_sidebars() */

	add_action( 'widgets_init', 'impresso_register_sidebars' );


	/* ========================================================================================================= */
	/* ========================================================================================================= */

	/*
		Register the portfolio Categories Taxonomy
	*/


	function friendly_custom_taxonomies()
	{

		/* This may be created in the install_postdata.php file */
		if(!taxonomy_exists('type'))
		{
			register_taxonomy( 'type', 'portfolio', array( 'hierarchical' => false, 'label' => 'Type', 'query_var' => true, 'rewrite' => true ) );
		}

	}/* friendly_custom_taxonomies() */

	add_action( 'init', 'friendly_custom_taxonomies', 0 );

	/* ========================================================================================================= */

	/*
		Function to list the portfolio 'types' as data-ids for the filtering
	*/

	function friendly_list_types_of_this_item($post_id)
	{

		$types_array = wp_get_object_terms( $post_id, 'type' );

		if( (is_array($types_array)) && (count($types_array) > 0) )
		{

			//There's at least 1 'type'
			echo "data-type='";
			foreach($types_array as $type_object)
			{

				$type_name = $type_object->slug;
				echo $type_name." ";

			}
			echo "' ";

			echo "class='lifted project ";
			foreach($types_array as $type_object)
			{

				$type_name = $type_object->slug;
				echo $type_name." ";

			}
			echo "'";

			echo "data-id='post-".$post_id."'";

		}

	}/* friendly_list_types_of_this_item() */

	/* ========================================================================================================= */

	/*
		Register the Portfolio Custom Post Type
	*/

	function friendly_register_portfolio_cpt()
	{

		$labels = array(
		    'name' => _x('Portfolio', 'post type general name','impresso'),
		    'singular_name' => _x('Portfolio', 'post type singular name','impresso'),
		    'add_new' => _x('Add New', 'portfolio','impresso'),
		    'add_new_item' => __('Add New Portfolio Item','impresso'),
		    'edit_item' => __('Edit Portfolio Item','impresso'),
		    'new_item' => __('New Portfolio Item','impresso'),
		    'view_item' => __('View Portfolio Item','impresso'),
		    'search_items' => __('Search Portfolio','impresso'),
		    'not_found' =>  __('No portfolio items found','impresso'),
		    'not_found_in_trash' => __('No portfolio items found in Trash','impresso'),
		    'parent_item_colon' => '',
		    'menu_name' => 'Portfolio'

		  );
		  $args = array(
		    'labels' => $labels,
		    'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true,
		    'show_in_menu' => true,
		    'query_var' => true,
		    'rewrite' => true,
		    'rewrite' => array(
		    	'slug' => 'portfolio',
		    	'with_front' => FALSE
		    ),
		    'capability_type' => 'post',
		    'has_archive' => true,
		    'hierarchical' => false,
		    'menu_position' => 20,
		    'supports' => array('title','editor','author','thumbnail','excerpt','comments','revisions','custom-fields'),
		    'register_meta_box_cb' => 'friendly_add_featured_metabox_to_portfolio',
		    //'taxonomies' => array('category')
		    'menu_icon' => get_stylesheet_directory_uri()."/admin/images/portfolio-icon.png"
		  );

	  register_post_type('portfolio',$args);

	}/* iamfriendly_regitser_portfolio_post_type() */

	add_action('init', 'friendly_register_portfolio_cpt');

	/* ========================================================================================================= */

	/*
		Add a 'featured' column to the portfolio listings page
	*/

	function friendly_register_featured_column_for_portfolio( $columns )
	{

		$columns['featured'] = __( 'Featured','impresso' );
		$columns['type'] = __( 'Type','impresso' );

		return $columns;

	}/* friendly_register_featured_column_for_portfolio() */

	function friendly_display_if_featured_in_featured_column( $column )
	{

		if($column == "featured")
		{

			global $post;
			$featured_meta = get_post_meta($post->ID,'featured_portfolio_item', true);

			if($featured_meta == "1")
			{
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#x2714;";
			}

		}

		if($column == "type")
		{

			global $post;
			$types_array = wp_get_object_terms( $post->ID, 'type' );

			if( (is_array($types_array)) && (count($types_array) > 0) )
			{
				foreach($types_array as $type)
				{
					$type_name = $type->slug;
					echo $type_name.", ";
				}
			}

		}

	}/* friendly_display_if_featured_in_featured_column() */

	add_filter( 'manage_edit-portfolio_columns', 'friendly_register_featured_column_for_portfolio' );
	add_action( 'manage_posts_custom_column', 'friendly_display_if_featured_in_featured_column' );

	/* ========================================================================================================= */


	/*
		Function for the Featured metabox in the portfolio edit screens
	*/

	function friendly_add_featured_metabox_to_portfolio()
	{

	    add_meta_box( 'friendly_portfolio_featured_item', __( 'Featured Portfolio Item','impresso' ),
	                'friendly_display_portfolio_featured_metabox', 'portfolio', 'side', 'default' );

	}/* friendly_add_custom_sidebar_metabox() */


	/* Prints the box content */
	function friendly_display_portfolio_featured_metabox()
	{

		global $post;
		// Use nonce for verification
		wp_nonce_field( plugin_basename(__FILE__), 'friendly_portfolio_nonce' );

		$featured_portfolio_value = get_post_meta($post->ID, "featured_portfolio_item", true );

		$featured_portfolio_item_pre = friendly_check_for_featured_portfolio_items();
		$featured_portfolio_item = $featured_portfolio_item_pre[0][0];

		//enable the checkbox only if we're on the page where it is checked
		if($featured_portfolio_item)
		{
			$disable_cb = true;
			if($post->ID == $featured_portfolio_item)
				$disable_cb = false;
		}


		?>

		<input type="checkbox" id="featured_portfolio_item" name="featured_portfolio_item" <?php if($disable_cb) : ?>disabled="disabled"<?php endif; ?> value="1" <?php checked($featured_portfolio_value, 1); ?> /><label for="featured_portfolio_item">&nbsp;Set As Featured Portfolio Item</label>
		<?php if($disable_cb) : ?>
			<p style="margin-top: 10px; padding: 5px; background: rgb(255,255,224); border: 1px solid rgb(230,219,85);">
				Note: This checkbox is disabled as you have already selected a <a href="<?php echo site_url(); ?>/wp-admin/post.php?post=<?php echo $featured_portfolio_item; ?>&action=edit" title="">featured portfolio item</a>.
			</p>
		<?php endif; ?>

		<?php


	}/* friendly_display_portfolio_featured_metabox() */


	/* When the post is saved, saves our custom data */
	function friendly_save_portfolio_featured_metabox( $post_id )
	{

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times

		if ( !wp_verify_nonce( $_POST['friendly_portfolio_nonce'], plugin_basename(__FILE__) ))
		{
			return $post_id;
		}

		// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
		// to do anything
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
			return $post_id;


		if ( !current_user_can( 'edit_post', $post_id ) )
		  	return $post_id;

		// OK, we're authenticated: we need to find and save the data

		$mydata = $_POST['featured_portfolio_item'];

		update_post_meta($post_id,"featured_portfolio_item",$mydata);

		return $mydata;

	}/* friendly_save_portfolio_featured_metabox() */

	add_action('save_post', 'friendly_save_portfolio_featured_metabox');


	/* ========================================================================================================= */

	/*
		Function to check if the user has selected a 'featured' portfolio item (checks for a meta box being checked)
	*/

	function friendly_check_for_featured_portfolio_items()
	{

		global $wpdb, $post;
		$we_have_featured_portfolio_items = false;

		//Start with a test to see if any of the portfolio items are 'featured'
		$check_for_featured_portfolio_items_query_pre = "SELECT DISTINCT post_id FROM $wpdb->postmeta WHERE (post_id IN (SELECT ID FROM $wpdb->posts WHERE (post_type = 'portfolio' AND post_status = 'publish')) AND (meta_key = 'featured_portfolio_item' AND meta_value = 1))";

		$featured_posts = $wpdb->get_results($check_for_featured_portfolio_items_query_pre, ARRAY_N);

		if(is_array($featured_posts))
		{

			$number_of_featured_posts = count($featured_posts);

			if($number_of_featured_posts > 0)
			{

				//^^ just a double check to make sure we have a featured portfolio item
				$we_have_featured_portfolio_items = true;

			}

		}

		if($we_have_featured_portfolio_items)
		{

			//Return the array of IDs
			return $featured_posts;

		}
		else
		{
			return false;
		}


	}/* friendly_check_for_featured_portfolio_items() */


	/* ========================================================================================================= */

	/*
		Function to disable the checkbox for the featured portfolio item if one is already selected
	*/

	function friendly_disable_checkbox_for_featured_portfolio_item_if_one_selected_already()
	{

		$featured_portfolio_item = friendly_check_for_featured_portfolio_items();

		if($featured_portfolio_item)
		{

			//There is a portfolio item set, disable the checkbox

		}

	}/* friendly_disable_checkbox_for_featured_portfolio_item_if_one_selected_already() */

	/* ========================================================================================================= */

	/*
		A callback function for the wp_list_comments function in single.php
	*/


	function friendly_comment_callback($comment, $args, $depth)
	{

		$GLOBALS['comment'] = $comment;
		?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

				<div id="comment-<?php comment_ID(); ?>">

					<div class="comment-author vcard">

						<?php printf(__('<cite class="fn">%s</cite> <span class="wrote">wrote</span><br />','impresso'), get_comment_author_link()) ?>

						<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
							<?php printf(__('<span>on</span> %1$s<br/> <span>at</span> %2$s','impresso'), get_comment_date(),  get_comment_time()) ?>
						</a>

						<div class="comment_avatar avatar-comment-<?php comment_ID(); ?>">
							<?php echo get_avatar($comment,$size='32',$default=get_bloginfo("stylesheet_directory").'/admin/images/friendly-logo-32.png' ); ?>
						</div>

						<div class="reply">
							<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
						</div>

						<?php edit_comment_link(__('Edit','impresso'),'  ','') ?>
					</div>

					<div class="comment_text">

						<?php if ($comment->comment_approved == '0') : ?>

							<div class="comment_under_moderation">
								<em><?php _e('Your comment is awaiting moderation.','impresso') ?></em>
							</div>

					<?php endif; ?>

						<?php comment_text() ?>
					</div>

				</div>

		<?php

		/* Note the lack of a trailing </li>. WP will add it itself once it's done listing any children and whatnot.  */

	}/* friendly_comment_callback() */


	/* ========================================================================================================= */

	/*
		Stop WordPress putting unnecessary <br> tags (not even <br />!) into the HTML editor. (Helps make our slide
		work properly)
	*/

	function friendly_better_wpautop($pee)
	{

		return wpautop($pee,$br=0);

	}/* friendly_better_wpautop() */

	remove_filter('the_content','wpautop');
	add_filter('the_content','friendly_better_wpautop');


	/* ========================================================================================================= */

	/*
		Allow shortcodes in widgets
	*/

	if ( !is_admin() )
	{
    	add_filter('widget_text', 'do_shortcode', 11);
	}


	/* ========================================================================================================= */


	/*
		Adjust the default search form markup
	*/

	function friendly_main_search_form( $form )
	{

		$default_search_value = "Vyhľadávanie...";

		$search_form_value = get_search_query();
		$search_form_value = ($search_form_value && ($search_form_value != "")) ? $search_form_value : $default_search_value;

	    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" ><div>
	    <input type="text" value="' . $search_form_value . '" name="s" id="s" onFocus="clearText(this)" onBlur="clearText(this)" />
	    <input type="submit" id="searchsubmit" value="'. esc_attr__('Hľadať','impresso') .'" />
	    </div>
		<script>var defaultSearchValue = \'' . $default_search_value . '\';</script>
	    </form>';

	    return $form;

	}/* friendly_main_search_form() */

	add_filter( 'get_search_form', 'friendly_main_search_form' );


	/* ========================================================================================================= */

	/*
		WordPress Editor Styles
	*/

	function friendly_add_editor_styles()
	{

		global $current_screen;

		if( ($current_screen) && ($current_screen != '') )
		{

			if( property_exists($current_screen,'post_type') )
			{

				switch ($current_screen->post_type)
				{
					case 'post':
						add_editor_style('css/friendly-editor-style.css');
					break;

					case 'page':
						add_editor_style('css/friendly-editor-style.css');
					break;

					case 'portfolio':
						add_editor_style('css/friendly-editor-style.css');
					break;
				}

			}

		}

	}/* friendly_add_editor_styles() */

	add_action( 'admin_head', 'friendly_add_editor_styles' );


	/*
		Add body class to Visual Editor to match class used live
	*/

	function friendly_mce_settings_adjust_body_class( $initArray )
	{

		$initArray['body_class'] = 'post_content';
		return $initArray;

	}/* friendly_mce_settings_adjust_body_class() */

	add_filter( 'tiny_mce_before_init', 'friendly_mce_settings_adjust_body_class' );


	/*
		Register JS for tabs/sliders/accordions etc.
	*/

	function friendly_add_js_to_editors()
	{

		global $post, $current_screen;

		if( $post && (property_exists($post, 'post_type')) )
		{

			if( property_exists($current_screen,'post_type') )
			{

				switch ($current_screen->id)
				{
					case 'post':
						wp_enqueue_script( 'friendly_inline_js_for_editor', CHILDTHEME . '/inc/js/friendly_inject_js_posts.js' );
						require_once( INC_PATH . 'js/getpaths.php' );
					break;

					case 'page':
						wp_enqueue_script( 'friendly_inline_js_for_editor', CHILDTHEME . '/inc/js/friendly_inject_js_posts.js' );
						require_once( INC_PATH . 'js/getpaths.php' );
					break;

					case 'portfolio':
						wp_enqueue_script( 'friendly_inline_js_for_editor', CHILDTHEME . '/inc/js/friendly_inject_js_posts.js' );
						require_once( INC_PATH . 'js/getpaths.php' );
					break;
				}

			}

		}

	}/* friendly_add_js_to_editors() */

	/* We only want to do this if the user has turned this option on */

	global $data;
	$data = get_option(OPTIONS);

	if(!is_array($data)){$data = array();}

	if( (array_key_exists('make_visual_editor_a_true_wysiwyg_editor', $data)) && (($data['make_visual_editor_a_true_wysiwyg_editor'] == "1")) )
	{

		add_action('admin_print_scripts', 'friendly_add_js_to_editors');

	}


	/* ========================================================================================================= */

	/*
		If we're not in the admin, load jQuery 1.4.2 (topUp)
	*/

	function friendly_reregister_jquery()
	{

		if (!is_admin())
		{
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
			wp_enqueue_script( 'jquery' );
		}

	}/* friendly_reregister_jquery */

	add_action('init', 'friendly_reregister_jquery');


	/* ========================================================================================================= */

	if ( ! isset( $content_width ) ) $content_width = 960;

?>
