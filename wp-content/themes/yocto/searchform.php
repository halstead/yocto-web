<?php
/**
 * custom search form
 */
?>
<div class="header-info">
<form role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-wrap">
    	<label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'baseKit' ); ?></label>
        <input type="search" placeholder="<?php echo esc_attr( 'Search Site...', 'baseKit' ); ?>" name="s" id="search-input" value="<?php echo esc_attr( get_search_query() ); ?>" />
        <button class="icon-search icon-large" aria-hidden="true" type="submit" id="search-submit" value="" /><i class="fa fa-search" aria-hidden="true"></i>
         
    </div>
</form>	
</div>



