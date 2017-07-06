<?php get_template_part('templates/page', 'header'); ?>

<div class="container">
  <div class="content row page-content">
    <div class="col-xs-12">
      <div class="content-block">
		<div class="alert alert-warning">
		  <?php _e('Sorry, but the page you were trying to view does not exist.', 'sage'); ?>
		</div>
		<?php get_search_form(); ?>
	  </div>
	</div>
  </div>
</div>

