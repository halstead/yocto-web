<div class="header-position-container">
	<div class="pre-header">
		<div class="container"><?php dynamic_sidebar('sidebar-pre-header'); ?></div>
	</div>
	<header class="banner">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
	        <span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
		<?php if ( get_theme_mod( 'baseKit_logo' ) ) : ?>
		  <div class='site-logo'>
			<a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'>
				<img src='<?php echo esc_url( get_theme_mod( 'baseKit_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
			</a>
		  </div>
		<?php else : ?>
		  <hgroup>
			<h1 class='site-title'>
				<a href='<?php echo esc_url( home_url( '/' ) ); ?>' title='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>' rel='home'>
					<?php bloginfo( 'name' ); ?>
				</a>
			</h1>
		  </hgroup>
		<?php endif; ?>
		<nav class="collapse navbar-collapse" role="navigation">
		<?php
		 	if (has_nav_menu('primary_navigation')) :
		        wp_nav_menu(['theme_location' => 'primary_navigation', 'walker' => new wp_bootstrap_navwalker(), 'menu_class' => 'nav navbar-nav']);
		    endif;
		 ?>
		 </nav>
		  <div class="header-info">
		  	<?php dynamic_sidebar('header-info'); ?>
		  </div>
	    </div>
	  </div>
	</header>
</div>

