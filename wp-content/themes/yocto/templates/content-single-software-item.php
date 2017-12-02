<?php while (have_posts()) : the_post(); ?>
<div class="container">
  <div class="content row page-content">
    <div class="col-xs-12">
      <!-- 
		  <article <?php //post_class(); ?>> -->
		    <header> 
		      <div class="page-header">
		      	<h2 class="block-title"><a href="/software-overview/" class="blue-link">Software</a> : <a href="/software-overview/project-components/" class="blue-link">Project Components</a> : <?php the_title(); ?></h2>
		      <?php get_template_part('templates/entry-meta'); ?>
		      </div>
		    </header>
		    <div class="content-block">
			    <div class="entry-content">
			      <?php the_content(); ?>
			    </div>
			</div>
		    <footer>
		      <?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
		    </footer>
		    <?php //comments_template('/templates/comments.php'); ?>
		  <!-- </article> -->
		
	</div>
  </div>
</div>
<?php endwhile; ?>
