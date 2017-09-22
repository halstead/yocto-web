<?php while (have_posts()) : the_post(); ?>
<div class="container">
  <div class="content row page-content">
    <div class="col-xs-12">
      <div class="content-block">
		  <article <?php post_class(); ?>>
		    <header>
		      <h1 class="entry-title"><?php the_title(); ?></h1>
		      <?php get_template_part('templates/entry-meta'); ?>
		    </header>
		    <div class="entry-content">
		      <?php the_content(); ?>
		    </div>
		    <footer>
		      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
		    </footer>
		    <?php //comments_template('/templates/comments.php'); ?>
		  </article>
		</div>
	</div>
  </div>
</div>
<?php endwhile; ?>
