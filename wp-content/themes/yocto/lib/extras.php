<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;
use WP_Query;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
  if (Setup\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

function get_custom_excerpt($limit, $source = null){ // Custom Excerpt function by character count

    if($source == "content" ? ($excerpt = get_the_content()) : ($excerpt = get_the_excerpt()));
	    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	    $excerpt = strip_shortcodes($excerpt);
	    $excerpt = strip_tags($excerpt);
	    $excerpt = substr($excerpt, 0, $limit);
	    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	    //$excerpt = $excerpt.'... <a href="'.get_permalink($post->ID).'">more</a>';
		$excerpt = $excerpt . '...';
    return $excerpt;
}
