<?php

namespace Roots\Sage\Setup;

use Roots\Sage\Assets;

/**
 * Theme setup
 */
function setup() {
  // Enable features from Soil when plugin is activated
  // https://roots.io/plugins/soil/
  add_theme_support('soil-clean-up');
  add_theme_support('soil-nav-walker');
  add_theme_support('soil-nice-search');
  add_theme_support('soil-jquery-cdn');
  add_theme_support('soil-relative-urls');

  // Make theme available for translation
  // Community translations can be found at https://github.com/roots/sage-translations
  load_theme_textdomain('sage', get_template_directory() . '/lang');

  // Enable plugins to manage the document title
  // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
  add_theme_support('title-tag');

  // Register wp_nav_menu() menus
  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus([
    'primary_navigation' => __('Primary Navigation', 'sage')
  ]);

  // Enable post thumbnails
  // http://codex.wordpress.org/Post_Thumbnails
  // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
  // http://codex.wordpress.org/Function_Reference/add_image_size
  add_theme_support('post-thumbnails');

  // Enable post formats
  // http://codex.wordpress.org/Post_Formats
  add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

  // Enable HTML5 markup support
  // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
  add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

  // Use main stylesheet for visual editor
  // To add custom styles edit /assets/styles/layouts/_tinymce.scss
  add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

/**
 * Register sidebars
 */
function widgets_init() {
  register_sidebar([
    'name'          => __('Primary', 'sage'),
    'id'            => 'sidebar-primary',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Footer', 'sage'),
    'id'            => 'sidebar-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);

  register_sidebar([
    'name'          => __('Pre Footer', 'sage'),
    'id'            => 'sidebar-pre-footer',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>'
  ]);
register_sidebar([
  'name'          => __('Pre Header', 'sage'),
  'id'            => 'sidebar-pre-header',
  'before_widget' => '<section class="widget %1$s %2$s">',
  'after_widget'  => '</section>',
  'before_title'  => '<h3>',
  'after_title'   => '</h3>'
]);

register_sidebar([
  'name'          => __('Header Info', 'sage'),
  'id'            => 'header-info',
  'before_widget' => '<section class="widget %1$s %2$s">',
  'after_widget'  => '</section>',
  'before_title'  => '<h3>',
  'after_title'   => '</h3>'
]);
 
register_sidebar([
    'name'          => __('Footer Block 1', 'sage'),
    'id'            => 'sidebar-footer-block-1',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ]);
  
  register_sidebar([
    'name'          => __('Footer Block 2', 'sage'),
    'id'            => 'sidebar-footer-block-2',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ]);
  
  register_sidebar([
    'name'          => __('Footer Block 3', 'sage'),
    'id'            => 'sidebar-footer-block-3',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ]);
  
  register_sidebar([
    'name'          => __('Footer Block 4', 'sage'),
    'id'            => 'sidebar-footer-block-4',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ]);  
  
  register_sidebar([
    'name'          => __('Footer Block 5', 'sage'),
    'id'            => 'sidebar-footer-block-5',
    'before_widget' => '<section class="widget %1$s %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h4>',
    'after_title'   => '</h4>'
  ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
  static $display;

  isset($display) || $display = !in_array(true, [
    // The sidebar will NOT be displayed if ANY of the following return true.
    // @link https://codex.wordpress.org/Conditional_Tags
    is_404(),
    is_front_page(),
    is_page_template('template-custom.php'),
  ]);

  return apply_filters('sage/display_sidebar', $display);
}

/**
 * Theme assets
 */
 
function assets() {
  wp_enqueue_style('sage/css', Assets\asset_path('styles/main.css'), false, null);
  //wp_enqueue_style('sage/fontawesome_css', Assets\asset_path('styles/font-awesome.css'), false, null);

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script('sage/js', Assets\asset_path('scripts/main.js'), ['jquery'], null, true);
  //wp_enqueue_script('sage/jquery_touchSwipe_min_js', Assets\asset_path('scripts/jquery.touchSwipe.js'), ['sage/js'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);


function admin_assets() { // Custom admin scripts used for uploading header images in admin and other functions
    wp_enqueue_script('admin_scripts_js', Assets\asset_path('scripts/custom-admin.js'), ['jquery'], null, true);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\admin_assets', 100);

/**
 * Theme customizer
 */
function baseKit_customize_register( $wp_customize ) {
   //All our sections, settings, and controls will be added here
   
   	////--------------------------->>>> SECTIONS <<<<---------------------------////
   	
   $wp_customize->add_section( 
		'baseKit_logo_section', 
		array(
			'title'       => __( 'Logo', 'baseKit' ),
    		'priority'    => 30,
    		'description' => 'Upload a logo to replace the default site name and description in the header', 
		) 
	);
	
	$wp_customize->add_section( 
		'baseKit_header_options', 
		array(
			'title'       => __( 'Header Settings', 'baseKit' ),
			'priority'    => 100,
			'capability'  => 'edit_theme_options',
			'description' => __('Change header options here.', 'baseKit'), 
		) 
	);
	
			
	////--------------------------->>>> SETTINGS <<<<---------------------------////

	//---->> HEADER SETTINGS <<----//
	$wp_customize->add_setting( 'baseKit_logo' );

	$wp_customize->add_setting( 'baseKit_header_position',
		array(
			'default' => 'relative',
			'type'    => 'theme_mod',
		)
	);		
	
	////--------------------------->>>> CONTROLS <<<<---------------------------////
	
	
	//---->> HEADER SECTION CONTROLS <<----//
	
	$wp_customize->add_control( new \WP_Customize_Image_Control( $wp_customize,
		'baseKit_logo', 
	 	array(
	    	'label'    => __( 'Logo', 'baseKit' ),
	    	'section'  => 'baseKit_logo_section',
	    	'settings' => 'baseKit_logo',
		)
	));
	
	$wp_customize->add_control( //new \WP_Customize_Color_Control(  $wp_customize,
		'baseKit_header_position',
		array(
			'type' => 'radio',
			'label'    => 'Header Position',
			'section'  => 'baseKit_header_options',
			//'settings' => 'baseKit_header_position',
			'choices'  => array(
				'relative'  => 'Relative',
				'fixed' => 'Fixed',
			),
			
		) 
	); 


	//---->> LOGO SECTION CONTROLS <<----//
	
	$wp_customize->add_setting(
	    'logo_placement',
	    array(
	        'default' => 'left',
	    )
	);
	 
	$wp_customize->add_control(
	    'logo_placement',
	    array(
	        'type' => 'radio',
	        'label' => 'Logo placement',
	        'section' => 'baseKit_header_options',
	        'choices' => array(
	            'left' => 'Left',
	            'right' => 'Right',
	            'center' => 'Center',
	        ),
	    )
	);
}


//add_action( 'customize_register', 'baseKit_customize_register' );
add_action( 'customize_register', __NAMESPACE__ . '\\baseKit_customize_register' );


/**
 * Additional body classes
 */

function custom_baseKit_body_classes(){
	$custom_header_position_class = get_theme_mod( 'baseKit_header_position', 'unset' ) . '-header';
	$custom_header_animation_class = get_theme_mod( 'baseKit_animation_header', 'unset' ) . '-animation';
	$custom_header_classes = array($custom_header_position_class, $custom_header_animation_class);
 	return $custom_header_classes; 
}	
