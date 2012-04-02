<article id="post_content">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<aside class="blog_home_cats"><span><?php the_category(', '); ?>, <?php the_tags(); ?></span><span class="post_date"> <?php the_time(get_option('date_format')); ?></span></aside>
		<h1><?php the_title(); ?></h1>

		<?php the_content(); ?>

		<?php friendly_check_and_load_scripts_and_set_global_vars(); ?>

		<nav id="nav-single">
			<span class="nav-previous"><?php previous_post_link( '%link', __( '&larr; Predošlý príspevok', 'friendly_mercury_theme' ) ); ?></span>
			<span class="nav-next"><?php next_post_link( '%link', __( 'Nasledujúci príspevok &rarr;', 'friendly_mercury_theme' ) ); ?></span>
		</nav><!-- #nav-single -->

		<div id="after_content_widget_area">

			<?php if ( is_active_sidebar( 'single_blog_post_after_content_before_comments' ) ) : ?>

				<?php dynamic_sidebar( 'single_blog_post_after_content_before_comments' ); ?>

			<?php endif; ?>

		</div><!-- #after_content_widget_area -->

		<div id="comments_and_pingbacks_container">

			<div id="comments_list_tab">

				<?php comments_template( '', true ); ?>

			</div><!-- #comments_list_tab -->

		</div><!-- #comments_and_pingbacks_container -->

	<?php endwhile; endif; ?>

</article><!-- #post_content -->

<?php get_template_part('section','sidebar'); ?>
