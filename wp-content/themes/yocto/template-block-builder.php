<?php
/**
 * Template Name: Block Builder Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php 
  global $post;
  
  $parent_title = get_the_title( $post->post_parent); 
  if( is_page() && $post->post_parent && !is_front_page() && !is_home()  && isset($parent_title) && $parent_title != ''){
  	   	get_template_part('templates/blockBuilder', 'header');
  }
  ?>
  <?php get_template_part('templates/content', 'blockBuilder'); ?>
<?php endwhile; ?>


