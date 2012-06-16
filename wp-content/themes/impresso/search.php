<?php get_header(); global $style_dir; ?>

<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

<?php get_template_part('section','subtitles'); ?>

<!-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| -->

	<div id="search_container" class="body_width">

		<div id="search_results_container">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post();  ?>

				<div class="search_result">

					<?php if(has_post_thumbnail()) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_post_thumbnail('thumbnail'); ?>
						</a>
					<?php endif; ?>
					<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
					<p class="read_more"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Čítať ďalej</a></p>

				</div><!-- .search_result -->

			<?php endwhile; else : ?>

				<div id="post-0" class="post no-results not-found">
					<div class="entry-content">
						<p><?php _e( 'Bohužiaľ, nič sa nenašlo...', 'iamfriendly' ); ?></p>

					</div><!-- .entry-content -->
				</div><!-- #post-0 -->

			<?php endif; ?>

		</div><!-- #search_results_container -->

	</div><!-- #search_container -->

<?php get_footer(); ?>
