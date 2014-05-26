
<form role="search" method="get" id="searchform" action="<?php bloginfo( 'url' ) ?>/">
    <div>
        <label class="screen-reader-text">
            <div class="input-append">
                <input type="text" class="input-medium" value="<?php echo get_search_query(); ?>" name="s" id="s" />
                <button type="submit" id="searchsubmit" class="btn"><i class="icon-search"></i></button>
            </div>
        </label>
    </div>
</form>

