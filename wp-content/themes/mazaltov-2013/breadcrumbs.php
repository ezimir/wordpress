
<div class="breadcrumbs">
    <a class="subtitle-link" href="<?php bloginfo( 'url' ) ?>/"><?php _e( is_front_page() ? 'Jewish Culture Festival' : 'Mazal Tov', 'mazaltov' ); ?></a>

<?php
    $separator = ' <span class="separator">/</span> ';

    if ( is_page() ) {
        $post = $wp_query->get_queried_object();
        echo $separator . the_title( '', '', false );
    } elseif ( is_category() || is_single() ) {
        class Only_Active_Walker_Nav_Menu extends Walker_Nav_Menu {
            function start_el( &$output, $item, $depth, $args ) {
                foreach ( $item->classes as $class ) {
                    if ( strpos( $class, 'current' ) !== false) {
                        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
                        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

                        $output .= sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
                            $args->before,
                            $attributes,
                            $args->link_before,
                            apply_filters( 'the_title', $item->title, $item->ID ),
                            $args->link_after,
                            $args->after
                        );

                        break;
                    }
                }
            }
        }

        wp_nav_menu( array(
            'theme_location' => 'main',
            'sort_column' => 'menu_order',
            'container' => false,
            'items_wrap' => '%3$s',
            'before' => $separator,
            'walker' => new Only_Active_Walker_Nav_Menu()
        ) );
    } elseif ( is_search() ) {
        echo $separator . __('Search', 'mazaltov');
    }
?>
</div>

