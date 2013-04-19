<?php
/*
Template Name: Home with Highlights
*/
?>
<?php get_header(); ?>

<div id="container" class="span9">

    <div id="content" class="<?php echo $pagename; ?><?php if (is_category()) { echo 'category'; } ?>">

<?php if (is_category()) { ?>
        <a class="subtitle-link" href="<?php bloginfo( 'url' ) ?>/"><?php _e('Jewish Culture Festival Mazal Tov', 'mazaltov'); ?></a>
        <h2 class="entry-title"> <a href="<?php echo get_category_link(get_the_category()->cat_ID); ?>"><?php single_cat_title(); ?></a> </h2>
<?php } ?>


<?php if (is_category()) { ?>
<?php   query_posts($query_string . '&orderby=menu_order&order=ASC'); ?>
<?php } ?>
<?php $prev_subtitle = ''; ?>
<?php while ( have_posts() ) : the_post() ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php if (!is_category()) { ?>
                    <a class="subtitle-link" href="<?php bloginfo( 'url' ) ?>/"><?php _e('Jewish Culture Festival', 'mazaltov'); ?> <?php if (!is_front_page()) { echo 'Mazal Tov'; } ?></a>
<?php } ?>
<?php
    $subtitle = __(get_post_meta($post->ID, 'subtitle', $single = true));
    if ($subtitle !== '' && $subtitle !== $prev_subtitle) { ?>
                    <h3 class="entry-subtitle"><?php echo $subtitle; ?></h3>
<?php
        $prev_subtitle = $subtitle;
    }
?>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'mazaltov'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>


<?php /* Microformatted, translatable post meta */ ?>
                    <div class="entry-meta">
                        <span class="meta-prep meta-prep-author"><?php _e('By ', 'mazaltov'); ?></span>
                        <span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s', 'mazaltov' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
                        <span class="meta-sep"> | </span>
                        <span class="meta-prep meta-prep-entry-date"><?php _e('Published ', 'mazaltov'); ?></span>
                        <span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time( get_option( 'date_format' ) ); ?></abbr></span>
                        <?php edit_post_link( __( 'Edit', 'mazaltov' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>
                    </div><!-- .entry-meta -->

<?php /* The entry content */ ?>
                    <div class="entry-content">
<?php
    $hide_empty = 0;
    $highlighted = get_categories( array( 'child_of' => get_category_by_slug( 'highlighted' )->term_id, 'hide_empty' => $hide_empty ) );
?>
<?php if ( count( $highlighted ) == 0 ) { ?>
    <?php the_content( __( '<span class="meta-nav">&raquo;</span> more info', 'mazaltov' )  ); ?>
<?php } else { ?>
                        <div class="span4">
    <?php the_content( __( '<span class="meta-nav">&raquo;</span> more info', 'mazaltov' )  ); ?>
                        </div>
                        <div class="span8">
    <?php foreach ( $highlighted as $highlighted_category ) { ?>
                            <div class="content-highlights <?php echo $highlighted_category->slug; ?>">
                                <h4> <?php _e( $highlighted_category->name ); ?> </h4>

        <?php
            $higlighted_posts = get_posts( array( 'category' => $highlighted_category->term_id ) );
            $higlighted_posts = array_slice( $higlighted_posts, 0, 2 );
            foreach ( $higlighted_posts as $higlighted_post ) { ?>
                                <div class="content-highlighted">
            <?php if ( has_post_thumbnail( $higlighted_post->ID ) ) { ?>
                <?php echo get_the_post_thumbnail( $higlighted_post->ID, array( 244, 180 ) ); ?>
            <?php } ?>
                                    <h5> <?php _e( $higlighted_post->post_title ); ?> </h5>
                                    <strong> /<?php echo get_the_time( 'd.m.Y', $higlighted_post ); ?>/ </strong>

                                    <p> <?php echo wp_trim_words(
                                        __( $higlighted_post->post_content ),
                                        $num_words = 21,
                                        $more = '<a href="' . get_permalink( $higlighted_post->ID ) . '">&raquo; ' . __( 'read more', 'mazaltov' ) . '</a>'
                                    ); ?> </p>
                                </div><!-- .content-highlighted (post wrap) -->
        <?php } ?>
                            </div><!-- .content-highlights (category wrap) -->
    <?php } ?>
                        </div><!-- .span8 (right column) -->
<?php } ?>
                    </div><!-- .entry-content -->

<?php /* Microformatted category and tag links along with a comments link */ ?>
                    <div class="entry-utility">
                        <span class="cat-links"><span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'mazaltov' ); ?></span><?php echo get_the_category_list(', '); ?></span>
                        <span class="meta-sep"> | </span>
                        <?php the_tags( '<span class="tag-links"><span class="entry-utility-prep entry-utility-prep-tag-links">' . __('Tagged ', 'mazaltov' ) . '</span>', ", ", "</span>\n\t\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ) ?>
                        <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'mazaltov' ), __( '1 Comment', 'mazaltov' ), __( '% Comments', 'mazaltov' ) ) ?></span>
                        <?php edit_post_link( __( 'Edit', 'mazaltov' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t\n" ) ?>
                    </div><!-- #entry-utility -->
                </div><!-- #post-<?php the_ID(); ?> -->

<?php endwhile; ?>


    </div><!-- #content -->

</div><!-- #container -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>

