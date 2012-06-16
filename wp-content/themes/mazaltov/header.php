<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <meta charset="<?php bloginfo('charset'); ?>" />

    <title><?php
        if ( is_single() ) { single_post_title(); }
        elseif ( is_home() || is_front_page() ) { bloginfo('name'); print ' | '; bloginfo('description'); get_page_number(); }
        elseif ( is_page() ) { single_post_title(get_bloginfo('name') . ' | '); }
        elseif ( is_search() ) { bloginfo('name'); print ' | ' . __( 'Search Results for: ', 'mazaltov' ) . wp_specialchars($s); get_page_number(); }
        elseif ( is_404() ) { bloginfo('name'); print ' | ' . __( 'Not Found', 'mazaltov' ); }
        else { bloginfo('name'); wp_title('|'); get_page_number(); }
    ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo get_bloginfo('stylesheet_url') . '?v=' . filemtime( get_stylesheet_directory() . '/style.css' ); ?>" />

    <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

    <?php wp_head(); ?>

    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url'); ?>" title="<?php printf( __( '%s latest posts', 'mazaltov' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
    <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'mazaltov' ), wp_specialchars( get_bloginfo('name'), 1 ) ); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
</head>
<body>
<div id="shadow-top"><div id="shadow-bottom">
<div id="wrapper" class="hfeed container-fluid">
    <div id="header">
        <div id="masthead">

            <div id="branding">
                <a id="blog-title" href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home"><?php bloginfo( 'name' ) ?></a>
                <h1 id="blog-description"><?php echo str_replace('|', '<br />', get_bloginfo( 'description' )) ?></h1>
            </div><!-- #branding -->

            <img id="random_header_pic" src="<?php echo get_random_header_image_from_media('header') ?>" />

            <div id="access">
                <?php wp_nav_menu( array( 'theme_location' => 'main', 'sort_column' => 'menu_order', 'container' => false, 'menu_id' => 'menu-main', 'after' => '<i class="btn-after"></i>' ) ); ?>
            </div><!-- #access -->

        </div><!-- #masthead -->
    </div><!-- #header -->

    <div id="main" class="row-fluid">

