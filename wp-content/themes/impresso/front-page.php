<?php get_header(); ?>


<?php get_template_part('section', 'home-slider'); ?>

<?php get_template_part('section','subtitles'); ?>

<?php if ( is_active_sidebar( 'client_slider_widget_area' ) ) : ?>
    <section id="home_client_slider">
    <?php dynamic_sidebar( 'client_slider_widget_area' ); ?>
    </section><!-- #home_client_slider -->
<?php endif; ?>

<?php get_template_part('category-content'); ?>


<?php get_footer(); ?>

