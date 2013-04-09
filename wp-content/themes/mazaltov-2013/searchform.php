
<form role="search" method="get" id="searchform" action="<?php bloginfo( 'url' ) ?>/">
    <div>
        <label class="screen-reader-text"> <?php _e('Search for:'); ?>
            <input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
        </label>
        <button type="submit" id="searchsubmit" class="btn"><i class="icon-search"></i> <?php esc_attr_e('Search') ?></button>
    </div>
</form>

