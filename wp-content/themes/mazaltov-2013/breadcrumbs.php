
<div class="breadcrumbs">
    <a class="subtitle-link" href="<?php bloginfo( 'url' ) ?>/"><?php _e( is_front_page() ? 'Jewish Culture Festival' : 'Mazal Tov', 'mazaltov' ); ?></a>

<?php
    $separator = ' <span class="separator">/</span> ';

    if ( is_page() ) {
        $post = $wp_query->get_queried_object();
        echo $separator . the_title( '', '', false );
    } else {
        if ( is_category() ) {
            $category_id = get_cat_ID( single_cat_title( '', false ) );
        } elseif ( is_single() ) {
            $category = get_the_category();
            $category_id = get_cat_ID( $category[0]->cat_name );
        }

        if ( isSet( $category_id ) ) {
            $parents = get_category_parents( $category_id, TRUE, $separator );

            // remove last separator
            $parents = substr( $parents, 0, strlen( $parents ) - strlen( $separator ) );

            echo $separator . $parents;
        }
    }
?>
</div>

