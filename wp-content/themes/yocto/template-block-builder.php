<?php
/**
 * Template Name: Block Builder Template
 */
?>
<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'blockBuilder'); ?>
<?php endwhile; ?>


