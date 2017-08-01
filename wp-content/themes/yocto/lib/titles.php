<?php

namespace Roots\Sage\Titles;

/**
 * Page titles
 */
function title() {
	global $post;
  if (is_home()) {
    if (get_option('page_for_posts', true)) {
      return get_the_title(get_option('page_for_posts', true));
    } else {
      return __('Latest Posts', 'sage');
    }
  } elseif (is_archive()) {
    //return get_the_archive_title();
	return post_type_archive_title( '', false );
  } elseif (is_search()) {
    return sprintf(__('Search Results for %s', 'sage'), get_search_query());
  } elseif (is_404()) {
    return __('Not Found', 'sage');
  } elseif( is_page() && $post->post_parent ) {
  	$new_title = '<a href="<?php echo get_permalink( $post->post_parent ); ?>" class="blue-link">' . get_the_title( $post->post_parent ) . '</a> : ' . get_the_title(); 
  	//return get_the_title( $post->post_parent ) . ': ' . get_the_title();
  	return $new_title;
  } else {  
    return get_the_title();
  }
}
