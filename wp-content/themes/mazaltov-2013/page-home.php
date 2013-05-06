<?php
/*
Template Name: Home with Highlights
*/
?>
<?php get_header(); ?>

<div id="container" class="span9">

    <div id="content" class="<?php echo $pagename; ?>">

<?php $prev_subtitle = ''; ?>
<?php while ( have_posts() ) : the_post() ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <a class="subtitle-link" href="<?php bloginfo( 'url' ) ?>/"><?php _e('Jewish Culture Festival', 'mazaltov'); ?></a>
<?php
    $subtitle = __(get_post_meta($post->ID, 'subtitle', $single = true));
    if ($subtitle !== '' && $subtitle !== $prev_subtitle) { ?>
                    <h3 class="entry-subtitle"><?php echo $subtitle; ?></h3>
<?php
        $prev_subtitle = $subtitle;
    }
?>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'mazaltov'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

<?php /* The entry content */ ?>
                    <div class="entry-content">
<?php
    $mazaltov_options = get_option( 'mazaltov_theme_options' );

    $highlighted = array_map( function ( $slug ) {
        return get_category_by_slug( $slug )->term_id;
    }, explode( ',', $mazaltov_options['highlighted_category'] ) );

    $highlighted = get_categories( array( 'include' => implode( ',', $highlighted ), 'hide_empty' => $$mazaltov_options['highlighted_hide_empty'] ) );
?>
<?php if ( count( $highlighted ) == 0 ) { ?>
    <?php the_content(); ?>
<?php } else { ?>
                        <div class="span4">
    <?php the_content(); ?>
                        </div>

                        <div class="span8">
    <?php foreach ( $highlighted as $highlighted_category ) { ?>
                            <div class="content-highlights <?php echo $highlighted_category->slug; ?>">
                                <h4> <?php _e( $highlighted_category->name ); ?> </h4>

        <?php
            $higlighted_posts = get_posts( array( 'category' => $highlighted_category->term_id ) );
            $higlighted_posts = array_slice( $higlighted_posts, 0, $mazaltov_options['highlighted_count'] );
            foreach ( $higlighted_posts as $higlighted_post ) { ?>
                                <div class="content-highlighted">
            <?php if ( has_post_thumbnail( $higlighted_post->ID ) ) { ?>
                <?php echo get_the_post_thumbnail( $higlighted_post->ID, array( 180, 133 ) ); ?>
            <?php } ?>
                                    <h5> <?php _e( $higlighted_post->post_title ); ?> </h5>
                                    <strong> /<?php echo get_the_time( 'd.m.Y', $higlighted_post ); ?>/ </strong>

                                    <p> <?php echo wp_trim_words(
                                        __( $higlighted_post->post_content ),
                                        $num_words = 21,
                                        $more = '... <a href="' . get_permalink( $higlighted_post->ID ) . '">&raquo; ' . __( 'read more', 'mazaltov' ) . '</a>'
                                    ); ?> </p>
                                </div><!-- .content-highlighted (post wrap) -->
        <?php } ?>
                            </div><!-- .content-highlights (category wrap) -->
    <?php } ?>
                        </div><!-- .span8 (right column) -->
<?php } ?>
                    </div><!-- .entry-content -->

                </div><!-- #post-<?php the_ID(); ?> -->

<?php endwhile; ?>


    </div><!-- #content -->

</div><!-- #container -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>

