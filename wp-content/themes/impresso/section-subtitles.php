<?php

	global $post, $data;
	$subtitle = get_post_meta($post->ID, "_subtitle", true );

?>

<?php if(is_home()) : ?>

	<?php
		$id_of_blog_page = get_option('page_for_posts');
		$subtitle = get_post_meta($id_of_blog_page, "_subtitle", true );

		if( $subtitle && $subtitle != "" ) :
	?>

		<section id="slogan">
			<h2 class="subtitle"><?php echo $subtitle; ?></h2>
		</section><!-- #slogan -->

	<?php endif; ?>

<?php else : ?>

	<?php if(is_author()) : ?>

		<?php $author_name = get_query_var('author_name'); ?>

		<section id="slogan">
			<h2 class="subtitle"><?php __('Archív používateľa ', 'iamfriendly'); ?><?php echo $author_name; ?></h2>
		</section><!-- #slogan -->

	<?php endif; ?>

	<?php if(is_404()) : ?>

		<?php $subtitle = $data['four_oh_four_subtitle']; ?>

		<section id="slogan">
			<h2 class="subtitle"><?php echo $subtitle; ?></h2>
		</section><!-- #slogan -->

	<?php endif; ?>

	<?php if(is_date()) : ?>

		<section id="slogan">
		<h2 class="subtitle"><?php _e('Archív za ','impresso'); ?>
			<?php if ( is_day() ) : ?>
				<?php printf( __( '%s', 'iamfriendly' ), '<span class="highlight">' . get_the_date() . '</span>' ); ?>
			<?php elseif ( is_month() ) : ?>
				<?php printf( __( '%s', 'iamfriendly' ), '<span class="highlight">' . get_the_date( 'F Y' ) . '</span>' ); ?>
			<?php elseif ( is_year() ) : ?>
				<?php printf( __( '%s', 'iamfriendly' ), '<span class="highlight">' . get_the_date( 'Y' ) . '</span>' ); ?>
			<?php endif; ?>
		</h2>
	</section><!-- #slogan -->

	<?php endif; ?>

	<?php if( $subtitle && $subtitle != "" && !is_404() ) : ?>

	<section id="slogan">
		<h2 class="subtitle"><?php echo $subtitle; ?></h2>
	</section><!-- #slogan -->

	<?php endif; ?>

	<?php if(is_search()) : ?>

		<?php $subtitle = get_search_query(); ?>

		<section id="slogan">
			<h2 class="subtitle">Výsledky vyhľadávania: <span class="highlight"><?php echo $subtitle; ?></span></h2>
		</section><!-- #slogan -->

	<?php endif; ?>

<?php endif; ?>
