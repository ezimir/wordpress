<?php

	/*
		This file is for generic functions which can be used in all themes, not specifically for one theme
	*/

	/* ========================================================================================================= */

	/*
		allow us to calculate the number of widgets assigned to a sidebar
	*/

	function friendly_count_widgets_on_sidebar($sidebar_name)
	{

		$current_sidebar_widgets = get_option('sidebars_widgets');

		if(is_array($current_sidebar_widgets))
		{
			if(array_key_exists($sidebar_name,$current_sidebar_widgets))
			{
				$number_of_widgets_on_home_page_row_two =  count($current_sidebar_widgets[$sidebar_name]);

				return $sidebar_name."_".$number_of_widgets_on_home_page_row_two;
			}
		}


	}/* friendly_count_widgets_on_sidebar() */


	/* ========================================================================================================= */


	/*
		Custom Excerpts
	*/

	function friendly_custom_excerpts( $post_id ,$length = 40, $content = false )
	{

		$post = get_post($post_id);
		$default_excerpt = $post->post_excerpt;

		if(!$default_excerpt)
		{
			//No excerpt is set
			$post_excerpt = $post->post_content;
		}
		else
		{
			//Already have an excerpt
			$post_excerpt = $post->post_excerpt;
		}

		$orig = $post->ID;

		$post_excerpt = strip_shortcodes($post_excerpt);
		$post_excerpt = str_replace(']]>', ']]&gt;', $post_excerpt);
		$post_excerpt = strip_tags($post_excerpt);


		if($length)
		{
			$excerpt_length = $length;
		}
		else
		{
			$excerpt_length = 40;
		}

		$words = explode(' ', $post_excerpt, $excerpt_length + 1);

		if(count($words) > $excerpt_length)
		{
			array_pop($words);
			$post_excerpt = implode(' ', $words);
		}

		$post_excerpt = '<p>' . $post_excerpt . ' ...</p>';

		return $post_excerpt;


	}/* friendly_custom_excerpts */

	/* ========================================================================================================= */


	/*
		Get the first image from the post
	*/

	function friendly_get_the_image()
	{

		//First check if this post has a featured image
		global $post;

		if ( has_post_thumbnail() )
		{
			$attachment_id =  get_post_thumbnail_id($post->ID);
			$image_with_atts = wp_get_attachment_image_src($attachment_id);

			return $image_with_atts[0];
		}
		else
		{

			//No featured image. First check for a custom field called 'thumbnail'
			if(get_post_meta($post->ID,'thumbnail'))
			{

				$thumbnail_url = get_post_meta($post->ID,'thumbnail', true);
				return $thumbnail_url;

			}
			else
			{

				//No featured image and no custom field. Trawl through the content
				$post_content = get_the_content();
				$number_of_images = substr_count($post_content, '<img');

				$start = 0;
			    for($i=1;$i<=$number_of_images;$i++)
				{

					$imgBeg = strpos($post_content, '<img', $start);
					$postRemaining = substr($post_content, $imgBeg);
					$imgEnd = strpos($postRemaining, '>');
					$postOutput = substr($postRemaining, 0, $imgEnd+1);
					$postOutput = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$postOutput);;
					$image[$i] = $postOutput;
					$start = $imgEnd+1;

				}

				if($image[1])
				{
					$pattern = '/src="([^"]+)/';
					$subject = $image[1];
					preg_match($pattern,$subject, $attributes);

					return $attributes[1];
				}
				else
				{
					//No images in content, so display a default image
					//See if the user has uploaded one to theme options
					global $data;
					if($data['home_page_no_feature_default_image'] && ($data['home_page_no_feature_default_image'] != "") )
					{
						return $data['home_page_no_feature_default_image'];
					}
					else
					{
						//Absolute last resort - display a holding image
						return get_stylesheet_directory_uri()."/images/default.jpg";
					}

				}

			}

		}


	}/* friendly_get_the_image() */

	/* ========================================================================================================= */


	/*
		Function to alter the url of images if we're on MultiSite
	*/

	function friendly_change_image_url_on_multisite($raw_image_url)
	{

		global $blog_id, $current_site;

		//If we're not on MultiSite, $current_site wont exist.
		if($current_site)
		{

			//Split the url on /files/
			$url_parts = explode('/files/', $raw_image_url);

			if(isset($url_parts[1]))
			{
				//Splt the first part of that url again
				$new_url = $url_parts[0] . '/wp-content/blogs.dir/' . $blog_id . '/files/' . $url_parts[1];
				return $new_url;
			}

		}
		else
		{
			return $raw_image_url;
		}

	}


	/* ========================================================================================================= */

	/*
		Function to allow us to conditionally load code to pages that contain certain content
	*/


	function friendly_load_code_conditionally($text_to_search="")
	{

		global $post;
		$found = false;

		if ( stripos($post->post_content, $text_to_search) !== false )
			$found = true;

		return $found;

	}/* friendly_load_code_conditionally() */


	/*
		function to parse content for the attributes for the sliders/accordions
	*/

	function friendly_parse_attributes($post_id=NULL,$item_name)
	{

		$attributes = array();

		switch ($item_name)
		{

			case 'friendly-slider':

				if($post_id)
				{
					$subjectpre = get_post($post_id);
					$subject = $subjectpre->post_content;
				}
				else
				{
					global $post;
					$subject = $post->post_content;
				}
				$pattern = '/<div id="FriendlySlider-container" class="([^"]+)">/';
				preg_match($pattern,$subject, $attributes);

				//$attributes[1] now has all the classes for the friendly slider
				//Similar to:
				/*
					friendly-slider-width-672 friendly-slider-height-360 friendly-slider-transition-fade friendly-slider-controls-false friendly-slider-autoplay-true
				*/

				$slider_width_pre_pre = explode("friendly-slider-width-",$attributes[1]);
				$slider_width_pre = explode(" friendly-slider-height-",$slider_width_pre_pre[1]);

				$slider_height_pre_pre = explode("friendly-slider-height-",$attributes[1]);
				$slider_height_pre = explode(" friendly-slider-transition-",$slider_height_pre_pre[1]);

				$slider_transition_pre_pre = explode("friendly-slider-transition-",$attributes[1]);
				$slider_transition_pre = explode(" friendly-slider-controls-",$slider_transition_pre_pre[1]);

				$slider_controls_pre_pre = explode("friendly-slider-controls-",$attributes[1]);
				$slider_controls_pre = explode(" friendly-slider-autoplay-",$slider_controls_pre_pre[1]);

				$slider_autoplay_pre = explode("friendly-slider-autoplay-",$attributes[1]);

				$slider_width = $slider_width_pre[0];
				$slider_height = $slider_height_pre[0];
				$slider_transition = $slider_transition_pre[0];
				$slider_controls = $slider_controls_pre[0];
				$slider_autoplay = $slider_autoplay_pre[1];

				$atts_to_send = array(
					'width'=>$slider_width,
					'height'=>$slider_height,
					'transition'=>$slider_transition,
					'controls'=>$slider_controls,
					'autoplay'=>$slider_autoplay
				);

				return $atts_to_send;

			break;

			case 'friendly-accordion':

				if($post_id)
				{
					$subjectpre = get_post($post_id);
					$subject = $subjectpre->post_content;
				}
				else
				{
					global $post;
					$subject = $post->post_content;
				}
				$pattern = '/<div class="accordion ([^"]+)"/';
				preg_match($pattern,$subject, $attributes);

				//$attributes[1] now has all the classes for the accordion, similar to:
				/*
					friendly_accordion width-672 height-320 autoplay-false basic ('basic' gets appended by the js)
				*/

				$accordion_width_pre_pre = explode("friendly_accordion width-",$attributes[1]);
				$accordion_width_pre = explode(" height-",$accordion_width_pre_pre[1]);

				$accordion_height_pre_pre = explode(" height-",$attributes[1]);
				$accordion_height_pre = explode(" autoplay-",$accordion_height_pre_pre[1]);

				$accordion_autoplay_pre_pre = explode(" autoplay-",$attributes[1]);
				$accordion_autoplay_pre = explode(" basic",$accordion_autoplay_pre_pre[1]);

				$accordion_width = $accordion_width_pre[0];
				$accordion_height = $accordion_height_pre[0];
				$accordion_autoplay = $accordion_autoplay_pre[0];

				$atts_to_send = array(
					'width' => $accordion_width,
					'height' => $accordion_height,
					'autoplay' => $accordion_autoplay
				);

				return $atts_to_send;

			break;

			case 'friendly-tabs-vert':

				if($post_id)
				{
					$subjectpre = get_post($post_id);
					$subject = $subjectpre->post_content;
				}
				else
				{
					global $post;
					$subject = $post->post_content;
				}
				$pattern = '/<div id="tabs_vertical" class="([^"]+)">/';
				preg_match($pattern,$subject, $attributes);

				/* friendly_themes_tabs tabs-size-300 tabs-vertical */
				$tabs_vert_height_pre_pre = explode("friendly_themes_tabs tabs-size-",$attributes[1]);
				$tabs_vert_height_pre = explode(" tabs-vertical",$tabs_vert_height_pre_pre[1]);
				$tabs_vert_height = $tabs_vert_height_pre[0];

				$atts_to_send = array(
					'height' => $tabs_vert_height
				);

				return $atts_to_send;

			break;

			case 'friendly-tabs-horiz':

				if($post_id)
				{
					$subjectpre = get_post($post_id);
					$subject = $subjectpre->post_content;
				}
				else
				{
					global $post;
					$subject = $post->post_content;
				}
				$pattern = '/<div id="tabs_horizontal" class="([^"]+)">/';
				preg_match($pattern,$subject, $attributes);

				/* friendly_themes_tabs tabs-size-620 tabs-horizontal */
				$tabs_horiz_width_pre_pre = explode("friendly_themes_tabs tabs-size-",$attributes[1]);
				$tabs_horiz_width_pre = explode(" tabs-horizontal",$tabs_horiz_width_pre_pre[1]);
				$tabs_horiz_width = $tabs_horiz_width_pre[0];

				$atts_to_send = array(
					'width' => $tabs_horiz_width
				);

				return $atts_to_send;

			break;

		}

	}


	/* ========================================================================================================= */


	/*
		function to check for and load code and global variables, used on most templates
	*/

	function friendly_check_and_load_scripts_and_set_global_vars()
	{

		global $this_post_contains_friendly_slider, $this_post_contains_friendly_accordion, $this_post_contains_map, $this_post_contains_tabs_vert, $this_post_contains_tabs_horiz;
		$this_post_contains_friendly_slider = 		friendly_load_code_conditionally("FriendlySlider-container");
		$this_post_contains_friendly_accordion = 	friendly_load_code_conditionally("friendly_accordion");
		$this_post_contains_map = 					friendly_load_code_conditionally("[map ");
		$this_post_contains_tabs_vert = 			friendly_load_code_conditionally("tabs_vertical");
		$this_post_contains_tabs_horiz = 			friendly_load_code_conditionally("tabs_horizontal");

	}/* friendly_check_and_load_scripts_and_set_global_vars() */


	/* ========================================================================================================= */


	/*
		function to get a page ID from its slug
	*/

	function friendly_get_id_from_slug($slug, $post_type)
	{

    	if($post_type == "page")
    	{

    		$page = get_page_by_path($slug);

		    if($page)
		    {
		        return $page->ID;
		    }
		    else
		    {
		        return null;
		    }

    	}
    	else
    	{

    		$args = array(
				'name' => $slug,
				'post_type' => $post_type,
				'post_status' => 'publish',
				'showposts' => 1,
				'caller_get_posts'=> 1
			);

			$post_data = get_posts($args);

			if($post_data)
			{
				return $post_data[0]->ID;
			}
			else
			{
				return null;
			}

    	}


	}/* friendly_get_id_from_slug() */


	/* ========================================================================================================= */

	/*
		Function for next/previous items on the portfolio page
	*/

	function friendly_next_prev_portfolio_items()
	{

		global $wpdb, $post;

		$next_query = "SELECT ID FROM $wpdb->posts WHERE (post_type='portfolio' AND ID < $post->ID AND post_status = 'publish' ) ORDER BY ID DESC LIMIT 1";

		$prev_query = "SELECT ID FROM $wpdb->posts WHERE (post_type='portfolio' AND ID > $post->ID AND post_status = 'publish' ) ORDER BY ID ASC LIMIT 1";

		$next_id = $wpdb->get_var($next_query);
		$prev_id = $wpdb->get_var($prev_query);

		$next_permalink = false;
		$previous_permalink = false;

		if($next_id)
		{
			//There is a 'next' item, so get the permalink for it
			$next_permalink = get_permalink($next_id);

			echo '<div class="previous_link">';
				echo '<a href="'.$next_permalink.'" title="View previous portfolio item">&larr; Previous</a>';
			echo '</div>';

		}

		if($prev_id)
		{
			//There is a 'previous' item, so get it's permalink
			$previous_permalink = get_permalink($prev_id);

			echo '<div class="next_link">';
				echo '<a href="'.$previous_permalink.'" title="View next portfolio item">Next &rarr;</a>';
			echo '</div>';
		}


	}/* friendly_next_prev_portfolio_items() */

?>
