<?php get_header(); global $sidebar_choice, $post; ?>

<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

<?php get_template_part('section','subtitles'); ?>

<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

<section id="blog_home_container" class="no_image_slider">

	<div id="posts_container">

		<?php while ( have_posts() ) : the_post(); ?>

			<article>

				<div class="archive_image_slider">

					<?php

						//Test to see if there are image attachments, if there are more than 1, show a slider
						$all_attachment_args = array(
							'post_type' => 'attachment',
							'post_parent' => $post->ID
						);

						$all_attachements = get_posts($all_attachment_args);

						if( (is_array($all_attachements)) && (count($all_attachements)>1) ) :

							echo '<div class="slider-wrapper theme-default"><div class="ribbon"></div><ul class="blog_home_slider">';

							foreach($all_attachements as $attachment_info) : $full_image = wp_get_attachment_image_src($attachment_info->ID, 'full'); ?>

								<img class="" src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo friendly_change_image_url_on_multisite($full_image[0]); ?>&h=170&w=170&zc=1" alt="" />


							<?php endforeach; echo '</ul></div>'; ?>

						<?php else : ?>

							<a class="home_page_blog_no_slider" href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>">

							<?php

								//No slider as not enough images, look for a featured image
								if ( has_post_thumbnail() )
								{
									// the current post has a thumbnail
									$thumb = get_post_meta($post->ID,'_thumbnail_id',false);
									$thumb = wp_get_attachment_image_src($thumb[0], 'post-thumbnail', false);
									$thumb = $thumb[0];

								?>
									<img class="" src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo friendly_change_image_url_on_multisite($thumb); ?>&h=170&w=170&zc=1" alt="" />
								<?php
								}
								else
								{
								?>

									<img class="" src="<?php echo get_stylesheet_directory_uri(); ?>/inc/thumb.php?src=<?php echo friendly_change_image_url_on_multisite(friendly_get_the_image()); ?>&h=170&w=170&zc=1" alt="" />
								<?php
								}

							?>

							</a>

						<?php endif; ?>

				</div><!-- .archive_image_slider -->

				<div class="blog_post_info_and_excerpt">

					<aside class="blog_home_cats"><span><?php the_category(', '); ?></span><span class="post_date"> <?php the_time(get_option('date_format')); ?></span></aside>
					<h1><a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?></a>"><?php echo the_title(); ?></a></h1>

					<p><?php echo friendly_custom_excerpts($post->ID, 26); ?></p>

					<p class="read_more_link"><a href="<?php echo the_permalink(); ?>" title="<?php the_title(); ?>"><?php _e('Read More','impresso'); ?> &rarr;</a></p>

				</div><!-- .blog_post_info_and_excerpt -->

			</article>

		<?php endwhile; wp_reset_postdata(); ?>

	</div><!-- #posts_container -->

	<?php get_template_part('section','sidebar'); ?>

</section><!-- #blog_home_container -->

<?php get_footer(); ?>
