<?php

	get_template_part('section','header-setup');
	global $data, $colour_scheme;

?>
<!DOCTYPE html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<head>

<?php if(FRIENDLY_THEMES_SITE_USES_AN_SEO_PLUGIN == 'YES') : ?>

	<title><?php wp_title(''); ?></title>

	<meta name="Description" content="">

<?php else : ?>

	<?php if(is_front_page() && $data['home_page_title'] != "") : ?>
	<title><?php echo $data['home_page_title']; ?></title>
	<?php else : ?>
	<title><?php bloginfo('name'); ?><?php wp_title('|'); ?></title>
	<?php endif; ?>

	<?php if(is_front_page() && $data['home_page_description_metatag'] != "") : ?>
	<meta name="Description" content="<?php echo $data['home_page_description_metatag']; ?>">
	<?php else : ?>
	<meta name="Description" content="<?php bloginfo('description'); ?>">
	<?php endif; ?>

<?php endif; ?>

	<meta charset="<?php bloginfo('charset'); ?>">

	<meta name="viewport" content="width=device-width, initial-scale=1"/>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->


	<!-- |||||||||||||||||||||||||||||||||||||||| Stylesheet Info, XFN, icons ||||||||||||||||||||||||||||||||||||||| -->

	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<?php global $style_dir; $style_dir = get_stylesheet_directory_uri(); //Cache the stylesheet_directory call ?>

	<!--[if !IE 6]><!-->
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php echo $style_dir; ?>/style.css<?php echo '?' . filemtime(get_stylesheet_directory() . '/style.css'); ?>" />
	<!--<![endif]-->

	<!--[if gte IE 7]>
		<link rel="stylesheet" href="<?php echo $style_dir; ?>/ie7.css" type="text/css" media="screen, projection" />
	<![endif]-->

	<!--[if lte IE 6]>
		<link rel="stylesheet" href="http://universal-ie6-css.googlecode.com/files/ie6.1.0.css" media="screen, projection">
	<![endif]-->

	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $style_dir; ?>/css/nivo-slider.css<?php echo '?' . filemtime(get_stylesheet_directory() . '/css/nivo-slider.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $style_dir; ?>/css/default.css<?php echo '?' . filemtime(get_stylesheet_directory() . '/css/default.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $style_dir; ?>/css/anythingslider.css<?php echo '?' . filemtime(get_stylesheet_directory() . '/css/anythingslider.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $style_dir; ?>/css/colorbox.css<?php echo '?' . filemtime(get_stylesheet_directory() . '/css/colorbox.css'); ?>" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $style_dir; ?>/css/facebook.css<?php echo '?' . filemtime(get_stylesheet_directory() . '/css/facebook.css'); ?>" />

	<?php if(array_key_exists('idevice_icon',$data)) : ?>

		<link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo friendly_change_image_url_on_multisite($data['idevice_icon']); ?>&h=129&w=129&zc=1" />

	<?php else : ?>

		<link rel="apple-touch-icon" href="<?php echo $style_dir; ?>/images/apple-touch-icon.png" />

	<?php endif; ?>

	<?php if(array_key_exists('custom_favicon',$data)) : ?>

		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo friendly_change_image_url_on_multisite($data['custom_favicon']); ?>&h=16&w=16&zc=1" />

	<?php else : ?>

		<link rel="shortcut icon" href="<?php echo $style_dir; ?>/images/favicon.png" type="image/png" />

	<?php endif; get_template_part('section','custom-cssfonts'); ?>

	<link href="http://fonts.googleapis.com/css?family=Merriweather&v2" rel="stylesheet" type="text/css">
	<link href="http://fonts.googleapis.com/css?family=Ubuntu&subset=latin&v2" rel="stylesheet" type="text/css">


	<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->


 	<!-- ||||||||||||||||||||||||||||||||||||||||||||||||| Begin WP ||||||||||||||||||||||||||||||||||||||||||||||||| -->

	<?php wp_head(); ?>

	<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->


	<!-- |||||||||||||||||||||||||||||||||||||||||||||||| Javascript |||||||||||||||||||||||||||||||||||||||||||||||| -->

	<script src="<?php echo $style_dir; ?>/js/modernizr-1.6.min.js"></script>
	<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>

	<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

	<?php

		$colour_scheme = strtolower($colour_scheme);
		if($colour_scheme && $colour_scheme != "light" && $colour_scheme != "custom")
		{
			//Include the colour scheme CSS File
			echo '<link rel="stylesheet" type="text/css" media="screen, projection" href="'.$style_dir.'/css/style-'.$colour_scheme.'.css?' . filemtime(get_stylesheet_directory() . '/css/style-'.$colour_scheme.'.css').'" />';
		}

		if($colour_scheme == "custom")
		{
			get_template_part('section','custom-colours');
		}

		get_template_part('section','custom-cssfonts');

	?>

</head>

<body <?php body_class( 'style-'.$colour_scheme ); ?>>

	<div id="wrap" class="body_width">

		<header id="main_header" role="banner">

			<section id="logo">

			<?php if(array_key_exists('logo',$data)) : ?>

				<?php if($data['logo'] != "") : ?>

					<a href="<?php echo site_url(); ?>" title="<?php _e('Go Home', 'impresso'); ?>">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo friendly_change_image_url_on_multisite($data['logo']); ?>&w=139&zc=1" alt="" />
					</a>

				<?php else : ?>

					<a href="<?php echo site_url(); ?>" title="<?php _e('Go Home', 'impresso'); ?>">
						<img src="<?php echo $style_dir; ?>/images/impresso-logo-139.png" alt="" />
					</a>

				<?php endif; ?>

			<?php else : ?>

				<a href="<?php echo site_url(); ?>" title="<?php _e('Go Home', 'impresso'); ?>">
					<img src="<?php echo $style_dir; ?>/images/impresso-logo-139.png" alt="" />
				</a>

			<?php endif; ?>

			</section><!-- #logo -->

			<nav id="main_menu" role="navigation">

				<?php if ( has_nav_menu( 'main-menu' ) ) : ?>

					<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'depth' => 2 ) ); ?>

				<?php else : ?>

					<?php wp_list_pages('depth=2&title='); ?>

				<?php endif; ?>

			</nav>

		</header><!-- #main_header -->
