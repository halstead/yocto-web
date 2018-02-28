<?php
/*
Plugin Name: DJD Site Post
Plugin URI: http://www.djdesign.de/djd-site-post/
Description: Write, publish and edit posts on the front end
Version: 0.9.3
Author: Dirk Jarzyna
Author URI: http://www.djdesign.de
License: GPL2

  Copyright 2013 Dirk Jarzyna

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if (!class_exists("DjdSitePost")) {
	class DjdSitePost {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
        
		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( 'djd-site-post', 'uninstall' ) );

		//Hooking in to setup admin settings page and settings menu
		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_menu', array($this, 'add_menu'));

		/**
		 * Custom actions
		 */
		
		//Include Conversions scripts
		include( plugin_dir_path( __FILE__ ) . '/inc/conversions.php');
		
		// Static assets
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Unautop the shortcode
		//add_filter( 'the_content', 'shortcode_unautop', 100 );

		// Setup Ajax Support
		add_action('wp_ajax_process_site_post_form', array( $this, 'process_site_post_form' ) );
		add_action('wp_ajax_nopriv_process_site_post_form', array( $this, 'process_site_post_form' ) );

		// Hide Toolbar
		add_action('init', array($this, 'hide_toolbar'));

		// Redirect non admin users from dashboard
		//add_action( 'admin_init', array($this, 'redirect_nonadmin_fromdash'), 1);
	
		// Register a widget to show the post form in a sidebar
		add_action( 'widgets_init', array($this,'register_form_widget' ));
		 
		// Save an auto-draft to get a valid post-id
		add_action ('save_djd_auto_draft', array($this, 'save_djd_auto_draft'));

		/**
		 * Custom filter
		 */

		// Print an edit post on front end link whenever an edit post link is printed on front end.
		add_filter('edit_post_link', array($this, 'edit_post_link'), 10, 2);

		// Redirect non admin users from dashboard
		// add_filter('login_redirect', array($this, 'djd_login_redirect'), 10, 3);
		
		//Call our shortcode handlers
		add_shortcode('dynamic-site-post-form', array($this, 'handle_form_shortcode'));
		add_shortcode('dynamic-site-post-display', array($this, 'handle_display_shortcode'));
	} // end constructor

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */

	public function activate( $network_wide ) {
		$this->set_default_options();
	} // end activate

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
		$domain = 'djd-site-post';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
		wp_enqueue_style( 'djd-site-post-admin-styles', plugins_url( 'djd-site-post/css/admin.css' ) );
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
		wp_enqueue_script( 'djd-site-post-admin-script', plugins_url( 'djd-site-post/js/admin.js' ) );
	} // end register_admin_scripts

	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
		wp_enqueue_style( 'djd-site-post-styles', plugins_url( 'djd-site-post/css/display.css' ) );
	} // end register_plugin_styles

	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
		wp_enqueue_script( 'djd-site-post-script', plugins_url( 'djd-site-post/js/display.js' ) );
		wp_enqueue_script( 'djd-site-post-ajax-script', plugins_url( 'djd-site-post/js/script.js' ) );
	} // end register_plugin_scripts

	/**
	 * Registers our post form widget.
	 */
	public function register_form_widget() {
		require(sprintf("%s/inc/djdsp-widget.php", dirname(__FILE__)));
		register_widget( 'djd_site_post_widget' );
	} // end register_form_widget
	
	/*
	 * Hook into WP's admin_init action hook
	 */
	public function admin_init() {
		// Set up the settings for this plugin
		$this->init_settings();
	} // end public static function activate

	/*
	 * Redirect non admin users from dashboard
	 */
	public function redirect_nonadmin_fromdash() {
		$djd_options = get_option('djd_site_post_settings');

		if ( $_SERVER['PHP_SELF'] == '/wp-admin/async-upload.php' ) {
			/* allow users to upload files */
			return true;
		} else if ( $djd_options['djd-no-backend'] && ( current_user_can('contributor') ||  current_user_can('subscriber')) ) {
			/* custom function get_user_role() checks user role, 
			requires administrator, else redirects */
			wp_safe_redirect(site_url());
			exit;
		}
	}

	public function djd_login_redirect( $redirect_to, $request, $user  ) {
		return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : site_url();
	}

	/*
	 * Setting default values and store them in db    
	 */
	public function set_default_options() {
		$defaultAdminOptions = array(
			'djd-form-name' => '',
			'djd-publish-status' => 'publish',
			'djd-post-confirmation' => '',
			'djd-post-fail' => '',
			'djd-redirect' => '',
			'djd-mail' => 1,
			'djd-hide-toolbar' => 1,
			'djd-hide-edit' => 0,
			'djd-login-link' => 1,
			'djd-post-format' => 0,
			'djd-post-type' => 'post',
			'djd-allow-guest-posts' => 0,
			'djd-guest-account' => 1,
			'djd-guest-cat-select' => 0,
			'djd-guest-cat' => '',
			'djd-categories' => 'list',
			'djd-default-category' => 1,
			'djd-allow-new-category' => 0,
			'djd-category-order' => 'id',
			'djd-title-required' => 1,
			'djd-show-excerpt' => 1,
			'djd-allow-media-upload' => 1,
			'djd-upload-no-content' => 1,
			'djd-show-tags' => 0,
			'djd-guest-info' => 1,
			'djd-title' => '',
			'djd-excerpt' => '',
			'djd-content' => '',
			'djd-date' => '',
			'djd-editor-style' => 'rich',
			'djd-upload' => 1,
			'djd-tags' => '',
			'djd-categories-label' => '',
			'djd-create-category' => '',
			'djd-send-button' => ''
		);
		// Check for previous options that might be stored in db
		$dbOptions = get_option('djd_site_post_settings');
		if (!empty($dbOptions)) {
			foreach ($dbOptions as $key => $option)
				$defaultAdminOptions[$key] = $option;
		}
		update_option('djd_site_post_settings', $defaultAdminOptions);
	} // end set_default_options()

	/*
	 * Initialize some custom settings
	 */
	public function init_settings() {
		// Register the settings for this plugin
		register_setting('djd_site_post_template_group', 'djd_site_post_settings', array($this, 'djd_site_post_validate_input'));
	} // end public function init_custom_settings()

	/*
	 * Add a menu for our settings page
	 */
	public function add_menu() {
		add_options_page(__('DJD Site Post Settings', 'djd-site-post'), __('DJD Site Post', 'djd-site-post'), 'manage_options', 'djd-site-post-settings', array($this, 'plugin_settings_page'));
	} // end public function add_menu()

	/*
	 * Admin menu callback
	 */
	public function plugin_settings_page() {
		if(!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'djd-site-post'));
		}
		// Render the settings template
		include(sprintf("%s/views/admin.php", dirname(__FILE__)));
	} // end public function plugin_settings_page()

	//function plugin_options_tabs() {
	//	$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'djdspp_general_settings';
	//
	//	screen_icon();
	//	echo '<h2 class="nav-tab-wrapper">';
	//	foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
	//		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
	//		echo '<a class="nav-tab ' . $active . '" href="?page=' . 'djd-site-post-settings' . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
	//	}
	//	echo '</h2>';
	//}

	/*
	 * Validate input
	 */
	public function djd_site_post_validate_input($input) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {

		    // Check to see if the current option has a value. If so, process it.
		    if( isset( $input[$key] ) ) {
			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = esc_attr(strip_tags( stripslashes( $input[ $key ] ) ) );
		    }
		}
		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'djd_site_post_validate_input', $output, $input );
	}

	// Following two functions make sure that image attachment gets the right post-id
	public function djd_insert_media_fix( $post_id ) {
		global $djd_media_post_id;
		global $post_ID; 
	
		/* WordPress 3.4.2 fix */
		$post_ID = $post_id; 
	
		/* WordPress 3.5.1 fix */
		$djd_media_post_id = $post_id;
		add_filter( 'media_view_settings', array($this, 'djd_insert_media_fix_filter'), 10, 2 );
	}

	public function djd_insert_media_fix_filter( $settings, $post ) {
		global $djd_media_post_id;
	
		$settings['post']['id'] = $djd_media_post_id;
		$settings['post']['nonce'] = wp_create_nonce( 'update-post_' . $djd_media_post_id );
		return $settings;
	}
	
	/*---------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/

	/*
	 * Print a link to edit post on front end whenever an edit post link is printed on front end.
	 */
	function edit_post_link($link, $post_id) {
		if ( 'page' != get_post_type($post_id) ) {
			$djd_options = get_option('djd_site_post_settings');
			if ( $djd_options['djd-edit-page'] ) {
				if ( $djd_options['djd-hide-edit'] ) {
					$link = '<a class="post-edit-link" href="' . home_url('/') . '?page_id='.$djd_options['djd-edit-page'] . '&post_id='.$post_id . '" title="Frontend Edit">Frontend Edit</a>';
				} else {
					$link = $link . ' | <a class="post-edit-link" href="' . home_url('/') . '?page_id='.$djd_options['djd-edit-page'] . '&post_id='.$post_id . '" title="Frontend Edit">Frontend Edit</a>';
				}
			}
		}
		return $link;
	}
	
	


	/*
	 * Format error messages for output.
	 */
	function format_error_msg($message, $type = '',  $source = ''){
		$html = '<p style="color:red"><em>';
		if(!$type)
			$type = __("Error", 'djd-site-post');
		$html .= "<strong>" . htmlspecialchars($type) . "</strong>: ";
		$html .= $message;
		$html .= '</em></p>';
		if($source){
			$html .= '<pre style="margin-left:5px; border-left:solid 1px red; padding-left:5px;"><code class="xhtml malformed">';
			$html .= htmlspecialchars($source);
			$html .= '</code></pre>';
		}
		return $html;
	}

	/*
	 * Hide the WordPress Toolbar.
	 */
	function hide_toolbar() {
		$djd_options = get_option('djd_site_post_settings');
		if ( isset($djd_options['djd-hide-toolbar']) ) {
			add_filter('show_admin_bar', '__return_false');
		}
	}

	/*
	 * Get current user info. If user is not logged in we check if guest posts are permitted and set variables accordingly.
	 */
	function verify_user() {
		$djd_userinfo = array ();
		$djd_options = get_option('djd_site_post_settings');

		if (is_user_logged_in()) {
			global $current_user;
			wp_get_current_user();
			$djd_userinfo['djd_user_id'] = $current_user->ID;
			$djd_userinfo['djd_user_login'] = $current_user->user_login;
			if ( current_user_can('publish_posts') )
				$djd_userinfo['djd_can_publish_posts'] = true;
			if ( current_user_can('manage_categories') )
				$djd_userinfo['djd_can_manage_categories'] = true;
				
			if ( current_user_can('contributor') ) {
				$contributor = get_role('contributor');
				$contributor->add_cap('upload_files');
				$contributor->add_cap('read');
				$contributor->add_cap('edit_posts');
				$contributor->add_cap('edit_published_pages');
				$contributor->add_cap('edit_others_pages');
				$djd_userinfo['media_upload'] = true;
			}
			return $djd_userinfo;

		} elseif ( (!is_user_logged_in()) && ($djd_options['djd-allow-guest-posts']) ) {
			$user_query = get_userdata($djd_options['djd-guest-account']);
			$djd_userinfo['djd_user_id'] = $user_query->ID;
			$djd_userinfo['djd_user_login'] = $user_query->user_login;
			
			// We give guests rights as a subscriber. Very limited, no media uploads.
			$djd_userinfo['djd_can_manage_categories'] = false;
			$djd_userinfo['djd_can_publish_posts'] = true;
			$djd_userinfo['publish_status'] = 'pending';
			$djd_userinfo['media_upload'] = false;

			return $djd_userinfo;
		}
		return false;
	} // end verify_user()

	function djd_check_user_role( $role, $user_id = null ) {
	 
		if ( is_numeric( $user_id ) )
			$user = get_userdata( $user_id );
		else
			$user = wp_get_current_user();
	 
		if ( empty( $user ) )
			return false;
		return in_array( $role, (array) $user->roles );
	}

	function save_djd_auto_draft( $error_msg = false ) {

		global $djd_post_id;
	
		if (!function_exists('get_default_post_to_edit')){
			require_once(ABSPATH . "wp-admin" . '/includes/post.php');
		}
	
		/* Check if a new auto-draft (= no new post_ID) is needed or if the old can be used */
		$last_post_id = (int) get_user_option( 'dashboard_quick_press_last_post_id' ); // Get the last post_ID
		if ( $last_post_id ) {
			$post = get_post( $last_post_id );
			if ( empty( $post ) || $post->post_status != 'auto-draft' ) { // auto-draft doesn't exists anymore
				$post = get_default_post_to_edit( 'post', true );
				update_user_option( get_current_user_id(), 'dashboard_quick_press_last_post_id', (int) $post->ID ); // Save post_ID
			} else {
				$post->post_title = ''; // Remove the auto draft title
			}
		} else {
			$post = get_default_post_to_edit( 'post' , true);
			$user_id = get_current_user_id();
			// Don't create an option if this is a super admin who does not belong to this site.
			if ( ! ( is_super_admin( $user_id ) && ! in_array( get_current_blog_id(), array_keys( get_blogs_of_user( $user_id ) ) ) ) )
				update_user_option( $user_id, 'dashboard_quick_press_last_post_id', (int) $post->ID ); // Save post_ID
		}
	
		$djd_post_id = (int) $post->ID;
	
		// Getting the right post-id for media attachments
		$this->djd_insert_media_fix( $djd_post_id );
	}

	
	////// SHORTCODES //////
	
	public function get_custom_excerpt($limit, $source = null){ // Custom Excerpt function by character count

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
	
	
	function default_shortcode_job_block() {
		// Jobs Custom Options
		$post_type = 'jobs';
		$jobs_options = get_option('options_jobs_field_group');
		$jobs_expire_date = $jobs_options['jobs_expire_setting'];
		$jobs_minimum_blocks = $jobs_options['jobs_minimum_blocks'];
		$jobs_default_ID = $jobs_options['jobs_default_postID'];

		$args = '';
		$args=array(
	      'post_type' => $post_type,
	      'post_status' => 'publish',
	      'posts_per_page' => 1,
	      'order' => 'DESC',
	      'post__in' => array($jobs_default_ID), 
	    );	
		
		$output = '';
		$my_query = null;
	    $my_query = new WP_Query($args); 
		if( $my_query->have_posts() ) {
			while ($my_query->have_posts()) : $my_query->the_post(); 
				$cityField = get_field('my_meta_box_city_text');
				$stateField = get_field('my_meta_box_state_select');
				$counteryField = get_field('my_meta_box_country_select');
				$state = convertState($stateField, $strFormat='name');
				$country = convertCountry($counteryField);
				$output .= '<div class="half-block">';
				$output .= '	<div class="block-copy col-sm-12">';
				if( get_field('dsp_job_website') ):
					$output .= '		<h3 class="title" style="margin-bottom:5px;"><a href="' .  get_field('dsp_job_website') . '">' .  get_field('dsp_job_company_name') . '</a></h3>';
				else:
					$output .= '		<h3 class="title" style="margin-bottom:5px;">' .  get_field('dsp_job_company_name') . '</h3>';
				endif;
				
				if( get_field('dsp_job_posting_link') ):
					$output .= '		<h3 class="title" style="margin-bottom:5px !important;"><a href="' .  get_field('dsp_job_posting_link') . '">' .  get_the_title() . '</a></h3>';
				else:
					$output .= '		<h3 class="title" style="margin-bottom:5px !important;">' .  get_the_title() . '</h3>';
				endif;	
				$output .= '		<p>' . get_the_excerpt() . '</p>';
				
				if($cityField != "" && $state != ""){
					$output .= '		<p class="date">' . $cityField .  ', ' . $state . '</p>';
				}elseif($state != ""){
					$output .= '		<p class="date">' . $state . '</p>';
				}
				$output .= '		<p class="date" style="margin-bottom:10px;">' . $country .  '</p>';
				$output .= '		<div class="row">';
				$output .= '			<div class="col-xs-12 col-sm-8"><p class="details">';  
				$output .= '			</div>';
				$output .= '			<div class="col-xs-12 col-sm-4">';	
				$output .= '				<div class="pull-right"><a href="' .  get_field('dsp_job_posting_link') . '" class="btn btn-blue" target="_blank">View Job Details</a></div>';
				$output .= '			</div>';
				$output .= '		</div>';
				$output .= '	</div>';
				$output .= '</div>';	
			endwhile;
		}
		wp_reset_postdata();
		wp_reset_query();
		return $output;
	}


	function handle_display_shortcode($atts, $content = null){
		
		if (!function_exists('default_shortcode_job_block'))   {
			function default_shortcode_job_block() {
				// Jobs Custom Options
				$post_type = 'jobs';
				$jobs_options = get_option('options_jobs_field_group');
				$jobs_expire_date = $jobs_options['jobs_expire_setting'];
				$jobs_minimum_blocks = $jobs_options['jobs_minimum_blocks'];
				$jobs_default_ID = $jobs_options['jobs_default_postID'];
		
				$args = '';
				$args=array(
			      'post_type' => $post_type,
			      'post_status' => 'publish',
			      'posts_per_page' => 1,
			      'order' => 'DESC',
			      'post__in' => array($jobs_default_ID), 
			    );	
				
				$output = '';
				$my_query = null;
			    $my_query = new WP_Query($args); 
				if( $my_query->have_posts() ) {
					while ($my_query->have_posts()) : $my_query->the_post(); 
						$cityField = get_field('my_meta_box_city_text');
						$stateField = get_field('my_meta_box_state_select');
						$counteryField = get_field('my_meta_box_country_select');
						$state = convertState($stateField, $strFormat='name');
						$country = convertCountry($counteryField);
						$output .= '<div class="half-block">';
						$output .= '	<div class="block-copy col-sm-12">';
						if( get_field('dsp_job_website') ):
							$output .= '		<h3 class="title" style="margin-bottom:5px;"><a href="' .  get_field('dsp_job_website') . '">' .  get_field('dsp_job_company_name') . '</a></h3>';
						else:
							$output .= '		<h3 class="title" style="margin-bottom:5px;">' .  get_field('dsp_job_company_name') . '</h3>';
						endif;
						
						if( get_field('dsp_job_posting_link') ):
							$output .= '		<h3 class="title" style="margin-bottom:5px !important;"><a href="' .  get_field('dsp_job_posting_link') . '">' .  get_the_title() . '</a></h3>';
						else:
							$output .= '		<h3 class="title" style="margin-bottom:5px !important;">' .  get_the_title() . '</h3>';
						endif;	
						$output .= '		<p>' . get_the_excerpt() . '</p>';
						
						if($cityField != "" && $state != ""){
							$output .= '		<p class="date">' . $cityField .  ', ' . $state . '</p>';
						}elseif($state != ""){
							$output .= '		<p class="date">' . $state . '</p>';
						}
						$output .= '		<p class="date" style="margin-bottom:10px;">' . $country .  '</p>';
						$output .= '		<div class="row">';
						$output .= '			<div class="col-xs-12 col-sm-8"><p class="details">';  
						$output .= '			</div>';
						$output .= '			<div class="col-xs-12 col-sm-4">';	
						$output .= '				<div class="pull-right"><a href="' .  get_field('dsp_job_posting_link') . '" class="btn btn-blue" target="_blank">View Job Details</a></div>';
						$output .= '			</div>';
						$output .= '		</div>';
						$output .= '	</div>';
						$output .= '</div>';	
					endwhile;
				}
				wp_reset_postdata();
				wp_reset_query();
				return $output;
			}
		}

		$local_atts = shortcode_atts( array(
			'post_count' => '-1',
			'show_thumbnail' => '1',
			'show_dates' => 'true',
			'link_to_page' => 'false',
			'widget' => 'false',
			'class' => '',
			'dynamic_post_type' => 'post',
			'dynamic_post_taxonomy' => '',
			'dynamic_post_taxonomy_terms' => ''
	    ), $atts );
		
		$post_count = $local_atts[ 'post_count' ];
		$show_thumbnail = $local_atts[ 'show_thumbnail' ];
		$dynamic_post_show_dates = $local_atts[ 'show_dates' ];
		$dynamic_post_link_to_page = $local_atts[ 'link_to_page' ];
		$dynamic_post_class = $local_atts[ 'class' ];
		$dynamic_post_widget = $local_atts[ 'widget' ];
		$dynamic_post_type = $local_atts[ 'dynamic_post_type' ];
		$dynamic_post_taxonomy = $local_atts[ 'dynamic_post_taxonomy' ];
		$dynamic_post_taxonomy_terms = $local_atts[ 'dynamic_post_taxonomy_terms' ];
		
		// Jobs Custom Options
		$jobs_options = get_option('options_jobs_field_group');
		$jobs_expire_date = $jobs_options['jobs_expire_setting'];
		$jobs_minimum_blocks = $jobs_options['jobs_minimum_blocks'];
		$jobs_default_ID = $jobs_options['jobs_default_postID'];

		// $link = $_POST["djd_site_post_link"];
		// $city = $_POST["djd_site_post_city"];
		// $state = $_POST["djd_site_post_state"];
		// $country = $_POST["djd_site_post_country"];
		
		if($dynamic_post_taxonomy_terms == 'all'){	
			
			global $wp_version;
			if ( $wp_version < 4.5 ) {
				$terms = get_terms( $dynamic_post_taxonomy, array(
				    'hide_empty' => false,
				    'fields' => 'id=>slug'
				    )
				);
			}else {
				$terms = get_terms( array(
				    'taxonomy' => $dynamic_post_taxonomy,
				    'hide_empty' => false,
				    'fields' => 'id=>slug'
				    )
				);
			}
		} else {  // if terms not 'all' then show only defined terms in the taxonomy (passed in atts)
			$terms = $dynamic_post_taxonomy_terms;
		}
		
		if($dynamic_post_taxonomy != ''){
		    $args=array(
		      'post_type' => $dynamic_post_type,
		      'post_status' => 'publish',
		      'posts_per_page' => $post_count,
		      'orderby' => 'title',
		      'posts_per_page' => -1,
		      'order' => 'ASC',
		      'tax_query' => array(
					array(
						'taxonomy' => $dynamic_post_taxonomy,
						 'field' => 'slug',
						 'terms' => $terms
					),
				   )
			   //'meta_query' => array($meta_array)
		    );
		}elseif($dynamic_post_type == 'jobs'){
			$args=array(
		      'post_type' => $dynamic_post_type,
		      'post_status' => 'publish',
		      'posts_per_page' => $post_count,
		      'orderby' => 'title',
		      'posts_per_page' => -1,
		      'post__not_in' => array($jobs_default_ID),
		      'order' => 'ASC'
		    );
		}else{
			$args=array(
		      'post_type' => $dynamic_post_type,
		      'post_status' => 'publish',
		      'posts_per_page' => $post_count,
		      'orderby' => 'title',
		      'posts_per_page' => -1,
		      'order' => 'ASC'
		    );
		}
		
		$output = '';
		$postCount = 0;
		//$output .= 'Tax: ' . $dynamic_post_taxonomy;
		//$output .= 'Term: ' . $dynamic_post_taxonomy_terms;

		$my_query = null;
	    $my_query = new WP_Query($args);
	    if( $my_query->have_posts() ) {
			$output .= '<div class="' . $dynamic_post_class . '">';
			
			if($dynamic_post_type == 'events' && $dynamic_post_widget == 'true'){
				$output .= '<div class="float-container"><h5 class="pull-left">Events</h5><a href="/community/events/" class="pull-right" style="margin:10px 0px;">All Events</a></div>';
			}
			$output .= '<div class="float-container">';
			
			while ($my_query->have_posts()) : $my_query->the_post();
				
				// Global Variables //
				$cityField = get_field('my_meta_box_city_text');
				$stateField = get_field('my_meta_box_state_select');
				$counteryField = get_field('my_meta_box_country_select');
				$state = convertState($stateField, $strFormat='name');
				$country = convertCountry($counteryField);
				
				if($dynamic_post_type == 'jobs'){
					$status;
					$post_expire_date = ( isset($jobs_expire_date) ) ? $jobs_expire_date : '12';
					$expires_date = '-' . $post_expire_date  .' month';
					$datePublished = get_the_date( 'Y-m-d' );
					
					if(strtotime($datePublished) > strtotime($expires_date)) {
					    $status = "published";
						// djd_site_company_name
						// djd_site_company_contact
						// djd_site_phone
						// djd_site_website
						// djd_site_email
						// djd_site_posting_link
						//$output .= $expires_date;
						$output .= '<div class="half-block">';
						$output .= '	<div class="block-copy col-sm-12">';
						
						if( get_field('dsp_job_website') ):
							$output .= '		<h3 class="title" style="margin-bottom:5px;"><a href="' .  get_field('dsp_job_website') . '" target="_blank">' .  get_field('dsp_job_company_name') . '</a></h3>';
						else:
							$output .= '		<h3 class="title" style="margin-bottom:5px;">' .  get_field('dsp_job_company_name') . '</h3>';
						endif;
						
						if( get_field('dsp_job_posting_link') ):
							$output .= '		<h3 class="title" style="margin-bottom:5px !important;"><a href="' .  get_field('dsp_job_posting_link') . '" target="_blank">' .  get_the_title() . '</a></h3>';
						else:
							$output .= '		<h3 class="title" style="margin-bottom:5px !important;">' .  get_the_title() . '</h3>';
						endif;	
						// $output .= '		<p>Published Date: ' . $datePublished . '</p>';
						// $output .= '		<p>Current Date: ' . $dateCurrent . '</p>';
						// $output .= '		<p>Status: ' . $status . '</p>';
						$output .= '		<p>' . get_the_excerpt() . '</p>';
						
						if($cityField != "" && $state != ""){
							$output .= '		<p class="adress">' . $cityField .  ', ' . $state . '</p>';
						}elseif($state != ""){
							$output .= '		<p class="address state">' . $state . '</p>';
						}
						
						$output .= '		<p class="address country" style="margin-bottom:10px;">' . $country .  ' ' . $counteryField . '</p>';
						
						if($dynamic_post_widget == 'false'){
							$output .= '		<div class="row">';
							$output .= '			<div class="col-xs-12 col-sm-8"><p class="details">';  
							//$output .= '				<div><strong>Contact Name: </strong>' .  get_field('dsp_job_company_contact') . '</div>';
							//$output .= '				<div><strong>Contact Phone: </strong>' .  get_field('dsp_job_phone') . '</div>';
							//$output .= '				<div><strong>Contact Email: </strong>' .  get_field('dsp_job_email') . '</div>';
							$output .= '			</div>';
							$output .= '			<div class="col-xs-12 col-sm-4">';	
							$output .= '				<div class="pull-right"><a href="' .  get_field('dsp_job_posting_link') . '" class="btn btn-blue" target="_blank">View Job Details</a></div>';
							$output .= '			</div>';
							$output .= '		</div>';
						}
						$output .= '	</div>';
						$output .= '</div>';
					}else{
						$status = "draft";
						$postID = get_the_ID();
						$post = array( 'ID' => $postID, 'post_status' => $status );
						wp_update_post($post);
					}
					
				}else if($dynamic_post_type == 'events'){
					
					$status;
					$datePublished = get_the_date( 'Y-m-d' );
					$dateCurrent = date("Y-m-d");
					
					if(strtotime(get_field('dsp_event_end_date', get_the_ID())) > strtotime('-2 day')) {
					    $status = "keep";

						$output .= '<div class="half-block">';
						if( get_field('dsp_event_link') && $dynamic_post_widget == 'true' ):
							$output .= '<a href="' .  get_field('dsp_event_link') . '" class="inline-block width:100%;" target="_blank">';
						endif;
						$output .= '	<div class="block-copy col-sm-12">';
						
						if( get_field('dsp_event_thumb') ){  // currently does not exist
							$output .= '	<div class="half-block-img-container col-sm-3"><img src="/wp-content/uploads/2017/06/icon-social-stack.png"></div>';
							$output .= '	<div class="half-block-copy col-sm-9">';
						}else{
							$output .= '	<div class="half-block-copy col-sm-12">';
						}
						$output .= '			<h3 class="title" style="margin-top:0px; margin-bottom:5px !important;">' .  get_the_title() . '</h3>';
						$output .= '			<p>' . get_the_excerpt() . '</p>';
						
						
						if($dynamic_post_widget == 'false'){
							$output .= '		<div class="row">';
							$output .= '			<div class="col-xs-12 col-sm-8">';
							if( get_field('dsp_event_venue') ):
								$output .= '			<p class="date">Venue: <span>' . get_field('dsp_event_venue', get_the_ID()) . '</span></p>';
							endif;
							$output .= '				<p class="date">' . get_field('my_meta_box_city_text') .  ', ' . $state .  '</p>';
							$output .= '				<p class="date" style="margin-bottom:10px !important;">' . $country .  '</p>';
							
							$output .= '			</div>';
							$output .= '			<div class="col-xs-12 col-sm-8">';
							if( get_field('dsp_event_start_date', get_the_ID()) ):
								$output .= '			<p class="date">Start date: <span>' . get_field('dsp_event_start_date', get_the_ID()) . '</span></p>';
							endif;
							if( get_field('dsp_event_end_date') ):
								$output .= '			<p class="date">End date: <span>' . get_field('dsp_event_end_date', get_the_ID()) . '</span></p>';
							endif;
							$output .= '			</div>';
							if( get_field('dsp_event_link') ):
								$output .= '			<div class="col-xs-12 col-sm-4">';	
								$output .= '				<div class="pull-right"><a href="' .  get_field('dsp_event_link') . '" class="btn btn-blue">View Event Details</a></div>';
								$output .= '			</div>';
							endif;
							$output .= '		</div>';
						}
						
						$output .= '		</div>';
						$output .= '	</div>';
						
						if( get_field('event_djd_site_post_link') && $dynamic_post_widget == 'true' ):
							$output .= '</a>';
						endif;
						$output .= '</div>';

						// event_djd_site_post_name
						// event_djd_site_post_email
						// event_djd_site_post_link
						// djd_site_post_start_date
						// djd_site_post_end_date
						// event_djd_site_post_venue
					}
				} else if($dynamic_post_type == 'members'){ 
					if($dynamic_post_taxonomy == 'organization-type' && $dynamic_post_taxonomy_terms == 'consultants'){
						//dsp_consultant_contact_name
						//dsp_consultant_contact_phone
						//dsp_consultant_website
						//dsp_consultant_contact_email
						//dsp_consultant_services_offered
						
						//professional-services
						//board-support
						//training
						//other
						$status;
						
						$consultants_options = get_option('options_organizations_field_group');
						$consultants_expire_setting = $consultants_options['consultants_expire_setting'];
						$consultants_notification_setting = $consultants_options['consultants_notification_setting'];
						$consultants_email_copy = $consultants_options['consultants_email_copy'];
				
						$post_consultant_expire_date = ( isset($consultants_expire_setting) ) ? $consultants_expire_setting : '12';
						$consultant_expires_date = '-' . $post_consultant_expire_date  .' month';
						
						$post_consultant_notification_date = ( isset($consultants_expire_setting) ) ? $consultants_expire_setting : '12';
						$consultant_notification_date = '-' . $post_consultant_notification_date  .' month';
						
						$datePostPublished = get_the_date( 'Y-m-d' );
			
						
						if(strtotime($datePostPublished) > strtotime($consultant_expires_date)) { // show them
						    $status = "published";

							$optionsArray = get_field('dsp_consultant_services_offered');
							 
							$professionalServices = (is_array($optionsArray) && in_array( 'professional-services', $optionsArray)) ? 'X' : ' ';
							$training = (is_array($optionsArray) && in_array( 'training', $optionsArray)) ? 'X' : ' ';
							$boardSupport = (is_array($optionsArray) && in_array( 'board-support', $optionsArray)) ? 'X' : ' ';
							$other = (is_array($optionsArray) && in_array( 'other', $optionsArray)) ? 'X' : ' ';
							
							$website_url = get_field('dsp_consultant_website');
							if (!preg_match("~^(?:f|ht)tps?://~i", $website_url)) {
						        $website_url = "http://" . $website_url;
						    }
						    //return $url;
							
							
							if($postCount == 0){
								$output .= '<div class="mobile-hide-992">';
								$output .= '	<div class="table-row seven-cols">';
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Consultant</h5></div>';
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Country</h5></div>';		
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Company</h5></div>';
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Professional Services</h5></div>';
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Training Services</h5></div>';
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Board Support Package Services</h5></div>';
								$output .= '		<div class="col-xs-12 col-sm-1"><h5>Other Services</h5></div>';
								$output .= '	</div>';
								$output .= '</div>';
							}
							
							$output .= '<div class="table-row seven-cols mobile-table-row">';
							$output .= '	<div class="col-xs-12 col-sm-1 mobile-show-992"><h5>Consultant</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1">';
							$output .= '	<p>';
							$output .= '		<strong>' . get_field('dsp_consultant_contact_name') . '</strong><br>';
							// if($cityField != "" && $state != ""){
								// $output .= '		<strong>' . $cityField . ', ' . $state . '</strong><br>';
							// }elseif($state != ""){
								// $output .= '		<strong>' . $state . '</strong><br>';
							// }
							// $output .= '		<strong>' . get_field('dsp_consultant_contact_phone') . '</strong><br>';
							// $output .= '		<a href="mailto:' . get_field('dsp_consultant_contact_email') . '" target="_blank">' . get_field('dsp_consultant_contact_email') . '</a>';
							$output .= '	</p>';
							$output .= '	</div>';
							$output .= '	<div class="col-xs-12 mobile-show-992"><h5>Country</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1"><p>' . $counteryField . '</p></div>'; //$country
							$output .= '	<div class="col-xs-12 col-sm-1 mobile-show-992"><h5>Company</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1"><p><a href="' . $website_url . '" target="_blank">' .  get_the_title() . '</a></p></div>';
							$output .= '	<div class="col-xs-12 col-sm-1 mobile-show-992"><h5>Pofessional Services</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1"><p>' . $professionalServices . '</p></div>';
							$output .= '	<div class="col-xs-12 col-sm-1 mobile-show-992"><h5>Training Services</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1"><p>' . $training . '</p></div>';
							$output .= '	<div class="col-xs-12 col-sm-1 mobile-show-992"><h5>Board Support Package Services</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1"><p>' . $boardSupport . '</p></div>';
							$output .= '	<div class="col-xs-12 col-sm-1 mobile-show-992"><h5>Other Services</h5></div>';
							$output .= '	<div class="col-xs-12 col-sm-1"><p>' . $other . '</p></div>';
							$output .= '</div>';

						}elseif(strtotime($datePostPublished) < strtotime($consultant_notification_date)){ //if is under send message setting
							$consultant_name = get_field('dsp_consultant_contact_name');
							$consultant_email = get_field('dsp_consultant_contact_email');
							$this->send_consultant_email($consultant_name, $consultant_email, $consultants_email_copy);
						}elseif(strtotime($datePostPublished) < strtotime($consultant_expires_date)){ //if is under set to draft
							$status = "draft";
							$postID = get_the_ID();
							$post = array( 'ID' => $postID, 'post_status' => $status );
							wp_update_post($post);
						}
					}else {
						// $output .= '<div class="half-block">';
						// $output .= '	<div class="block-copy col-sm-12">';
						// $output .= '		<p>Organization block</p>';
						// $output .= '	</div>';
						// $output .= '</div>';
					}

				} else {
					$output .= '<div class="half-block">';
					$output .= '	<div class="block-copy col-sm-12">';
					$output .= '		<p>Something has gone terribly wrong! Move to Alaska immediately.</p>';
					$output .= '	</div>';
					$output .= '</div>';
				}
			$postCount ++;
			endwhile;
			
			if($dynamic_post_type == 'jobs' && $postCount < ( $jobs_minimum_blocks + 1 )) {	
				$output .= default_shortcode_job_block();
			}
			
			wp_reset_postdata();
			wp_reset_query();
			$output .= '	</div>';
			$output .= '</div>';
			return $output;
		} else {
			if($dynamic_post_type == 'jobs'){
				$output .= default_shortcode_job_block($atts);
				wp_reset_postdata();
				wp_reset_query();
				return $output;
			}else{
				$output .= '<div class="half-block">';
				$output .= '	<div class="block-copy col-sm-12">';
				$output .= '		<p>There are no results that match your input.</p>';
				$output .= '	</div>';
				$output .= '</div>';
				wp_reset_postdata();
				wp_reset_query();
				return $output;
			}
		}
	}

		
	/*
	 * Registers the shortcode that has a required @name param indicating the function which returns the HTML code for the shortcode.
	 *
	 * Shortcode: [djd-site-post] With parameters: [djd-site-post success_url="url" success_page_id="id"]
	 * Parameters:
	 * 	success_url: URL of the page to redirect to after the post.
	 * 	success_page_id: ID of the page to redirect to after the post. Overwrites success_url if set.
	 */
	function handle_form_shortcode($atts, $content = null){
		
		//if ( !empty ($_POST["djd-our-id"])) $djd_post_id = $_POST["djd-our-id"];

		global $shortcode_cache, $post, $djd_post_id;
		
		extract(shortcode_atts(array(
			'success_url' => '',
			'success_page_id' => 0,
			'called_from_widget' => '0',
			'dynamic_post_type' => 'post',
			'dynamic_post_title' => 'Upload Post',
			'dynamic_post_taxonomy' => '',
			'dynamic_post_term' => ''
		), $atts));
		$form_name = 'site_post_form';
		$djd_options = get_option('djd_site_post_settings');
		
		//echo 'dpt: ' . $dynamic_post_type;
		
		$GLOBALS['djd_post_type'] = $dynamic_post_type;
		$GLOBALS['dynamic_post_title'] = $dynamic_post_title;
		$GLOBALS['djd_post_type_taxonomy'] = $dynamic_post_taxonomy;
		$GLOBALS['djd_post_type_term'] = $dynamic_post_term;
		
		
		//$_POST["djd_post_type"] = $dynamic_post_type;
		
		//print_r($atts);
		//echo "dp type: " . $GLOBALS['djd_post_type']; //$dynamic_post_type;
		//echo "dp title: " . $GLOBALS['dynamic_post_title'];
		
		// Check for user logged in or guest posts permitted.
		if(!$user_verified = $this->verify_user())
			return $this->format_error_msg(__("Please login or register to use this function.", 'djd-site-post'),__("Notice", 'djd-site-post'));

		do_action ('save_djd_auto_draft');
			
		// success_page_id overwrites success_url.
		if($success_page_id)
			$success_url = get_permalink($success_page_id);

		// Shortcode 'success_url' attribute. This has priority over redirect set in admin panel.
		if(!$success_url) {
			if(isset($djd_options['djd-redirect'])){
				$success_url = $djd_options['djd-redirect'];
			}
			
			if (empty($success_url)) $success_url = home_url() . "/";
		}
		//
		
		//$newOptions = array('djd-post-type' => $dynamic_post_type);
		//update_option('djd_site_post_settings', $newOptions);
		//'djd-post-type' => 'post',
		

		// Call the function and grab the results (if nothing, output a comment noting that it was empty).
		// This one calls the form presented to the user.
		return call_user_func_array(array($this, $form_name), array($atts, $content, $user_verified, $djd_post_id, $called_from_widget));

	}


	function process_site_post_form() {
		if( isset($_POST) ){

			$djd_options = get_option('djd_site_post_settings');
				
			if ( !empty ($_POST["djd-our-post-type"])) $djd_post_type = $_POST["djd-our-post-type"];
			if ( !empty ($_POST["djd-our-id"])) $djd_post_id = $_POST["djd-our-id"];
			
			$dynamic_post_taxonomy = ( !empty ($_POST["djd-our-post-taxonomy"])) ? $_POST["djd-our-post-taxonomy"] : 'category';
			$terms = ( !empty ($_POST["djd-our-post-term"])) ? $_POST["djd-our-post-term"] : 'uncategorized';
			
			//if ( !empty ($_POST["djd-our-post-taxonomy"])) $dynamic_post_taxonomy = $_POST["djd-our-post-taxonomy"];
			//if ( !empty ($_POST["djd-our-post-term"])) $terms = $_POST["djd-our-post-term"];
				// Create post object with defaults
			
			// Check if the city exists
			//$city_term = term_exists( $city, 'location', $state_term['term_taxonomy_id'] );
			
			$org_term = term_exists( $terms, $dynamic_post_taxonomy, 0 );
			// Create city if it doesn't exist
			if ( !$org_term ) {
			    //$org_term = wp_insert_term( $terms, $dynamic_post_taxonomy, array( 'parent' => $state_term['term_taxonomy_id'] ) );
				$org_term = wp_insert_term( $terms, $dynamic_post_taxonomy, array( 'parent' => 0 ) );
			}
			
			$custom_tax = array(
			    $dynamic_post_taxonomy => array(
			        $org_term['term_taxonomy_id']
			    )
			);
			//if($dynamic_post_taxonomy != ''){
				$my_post = array(
					'ID' => $djd_post_id,
					'post_title' => '',
					'post_status' => 'publish',
					'post_author' => '',
					'post_category' => '',
					'comment_status' => 'open',
					'ping_status' => 'open',
					'post_content' => '',
					'post_excerpt' => '',
					'post_type' => $djd_post_type, 
					'tags_input' => '',
					'to_ping' =>  '',
				    'tax_input' => $custom_tax
				);
			// }else{
				// $my_post = array(
					// 'ID' => $djd_post_id,
					// 'post_title' => '',
					// 'post_status' => 'publish',
					// 'post_author' => '',
					// 'post_category' => '',
					// 'comment_status' => 'open',
					// 'ping_status' => 'open',
					// 'post_content' => '',
					// 'post_excerpt' => '',
					// 'post_type' => $djd_post_type, 
					// 'tags_input' => '',
					// 'to_ping' =>  ''
				// );
			// }
				
				
				//print_r($my_post);
				
				$date_stamp = strtotime($date_string);
				// returns: int(1000166400)

				$postdate = date("Y-m-d H:i:s", $date_stamp);
				
	
				//Fill our $my_post array
				$my_post['post_title'] = wp_strip_all_tags($_POST['djd_site_post_title']);

				if( array_key_exists('djdsitepostcontent', $_POST)) {
					$my_post['post_content'] = $_POST['djdsitepostcontent'];
				}
				if( array_key_exists('djd_site_post_excerpt', $_POST)) {
					$my_post['post_excerpt'] = wp_strip_all_tags($_POST['djd_site_post_excerpt']);
				}
				if( array_key_exists('djd_site_post_select_category', $_POST)) {
					$ourCategory = 	array($_POST['djd_site_post_select_category']);
				}
				if( array_key_exists('djd_site_post_checklist_category', $_POST)) {
					$ourCategory = 	$_POST['djd_site_post_checklist_category'];
				}
				if( array_key_exists('djd_site_post_new_category', $_POST)) {
					if (!empty( $_POST['djd_site_post_new_category']) ) {
						require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/taxonomy.php');
						if ($newCatId = wp_create_category(wp_strip_all_tags($_POST['djd_site_post_new_category']))) {
							$ourCategory = 	array($newCatId);
						} else {
							throw new Exception(__('Unable to create new category. Please try again or select an existing category.', 'djd-site-post'));
						}
					}
				}
				
				if ( ! is_user_logged_in() && ! $djd_options['djd-guest-cat-select'] ) {
					$ourCategory = array( $djd_options['djd-guest-cat'] );
				}
				
				$my_post['post_category'] = $ourCategory;


				if( array_key_exists('djd_site_post_start_date', $_POST)) {
					//$my_post['post_start_date'] = date("Y-m-d H:i:s", strtotime($_POST['djd_site_post_start_date']));
				}
				
				if( array_key_exists('djd_site_post_end_date', $_POST)) {
					//$my_post['post_end_date'] = date("Y-m-d H:i:s", strtotime($_POST['djd_site_post_end_date']));
				}


				if ( !empty ($_POST["djd-our-author"])) {
					$my_post['post_author'] =  $_POST["djd-our-author"];
				} else {
					$my_post['post_author'] = $user_verified['djd_user_id'];
				}
	
				if( array_key_exists('djd_site_post_tags', $_POST)) {
					$my_post['tags_input'] = wp_strip_all_tags($_POST['djd_site_post_tags']);
				}
	
				if( $djd_options['djd-publish-status']) {
					$my_post['post_status'] = $djd_options['djd-publish-status'];
				}
				if( array_key_exists('djd-priv-publish-status', $_POST)) {
					$my_post['post_status'] = wp_strip_all_tags($_POST['djd-priv-publish-status']);
				}

				// Insert the post into the database
				$post_success = wp_update_post( $my_post );
				
				
				// Insert Meta Data
				if($post_success != 0)
				{
					if($djd_post_type == 'events') {
						$eventlink = $_POST["dsp_event_link"];
						$eventVenue = $_POST["dsp_event_venue"];
						$startDate = $_POST["dsp_event_start_date"];
						$endDate = $_POST["dsp_event_end_date"];
						$contactName = $_POST["dsp_event_name"];
						$contactEmail = $_POST["dsp_event_email"];
						
						update_post_meta ($post_success, 'dsp_event_link', $eventlink);
						update_post_meta ($post_success, 'dsp_event_venue', $eventVenue);
						update_post_meta ($post_success, 'dsp_event_start_date', $startDate);
				    	update_post_meta ($post_success, 'dsp_event_end_date', $endDate);
						update_post_meta ($post_success, 'dsp_event_name', $contactName);
				    	update_post_meta ($post_success, 'dsp_event_email', $contactEmail);
				    }
					if($djd_post_type == 'jobs') {
						$companyName = $_POST["dsp_job_company_name"];
						$companyContact = $_POST["dsp_job_company_contact"];
						$companyPhone = $_POST["dsp_job_phone"];
						$companyEmail = $_POST["dsp_job_email"];
						$companyWebsite = $_POST["dsp_job_website"];
						$companyPostingLink = $_POST["dsp_job_posting_link"];
						
						update_post_meta ($post_success, 'dsp_job_company_name', $companyName);
						update_post_meta ($post_success, 'dsp_job_company_contact', $companyContact);
						update_post_meta ($post_success, 'dsp_job_phone', $companyPhone);	
						update_post_meta ($post_success, 'dsp_job_email', $companyEmail);
						update_post_meta ($post_success, 'dsp_job_website', $companyWebsite);
						update_post_meta ($post_success, 'dsp_job_posting_link', $companyPostingLink);
					}
					if($djd_post_type == 'members') {
						if($terms == "participants"){
							// Text Fields
							$participantsContactName = $_POST["dsp_participant_contact_name"];
							$participantscontactEmail = $_POST["dsp_participant_contact_email"];
							$participantsOrgUrl = $_POST["dsp_participant_organization_url"];
							// Radio Fields
							$radioParticipantsVisiblyParticipating = $_POST["dsp_participant_visibly_participating"];
							$radioParticipantsSupportingObjectives = $_POST["dsp_participant_supporting_objectives"];
							$radioParticipantsCommitedPromoting = $_POST["dsp_participant_commited_to_promoting"];
							$radioParticipantsPubliclyAccessible = $_POST["dsp_participant_publicly_accessible"];
							$radioParticipantsCommittedSending = $_POST["dsp_participant_committed_to_sending"];
							$radioParticipantsAimingCompatibility = $_POST["dsp_participant_aiming_for_compatibility"];
							$radioParticipantsNonProfit = $_POST["dsp_participant_non_profit"];
							// Textarea Fields
							$textAreaParticipantsExplanation = $_POST["dsp_participant_explanation"];
							
							// Update  Postmeta Fields
							update_post_meta ($post_success, 'dsp_participant_contact_name', $participantsContactName);
							update_post_meta ($post_success, 'dsp_participant_contact_email', $participantscontactEmail);
							update_post_meta ($post_success, 'dsp_participant_organization_url', $participantsOrgUrl);
							update_post_meta ($post_success, 'dsp_participant_visibly_participating', $radioParticipantsVisiblyParticipating);
							update_post_meta ($post_success, 'dsp_participant_supporting_objectives', $radioParticipantsSupportingObjectives);
							update_post_meta ($post_success, 'dsp_participant_commited_to_promoting', $radioParticipantsCommitedPromoting);
							update_post_meta ($post_success, 'dsp_participant_publicly_accessible', $radioParticipantsPubliclyAccessible);
							update_post_meta ($post_success, 'dsp_participant_committed_to_sending', $radioParticipantsCommittedSending);
							update_post_meta ($post_success, 'dsp_participant_aiming_for_compatibility', $radioParticipantsAimingCompatibility);
							update_post_meta ($post_success, 'dsp_participant_non_profit', $radioParticipantsNonProfit);
							update_post_meta ($post_success, 'dsp_participant_explanation', $textAreaParticipantsExplanation);
							

						}elseif($terms == "compatible"){
							// Text Fields
							$compatibleContactName = $_POST["dsp_ypcompatible_contact_name"];
							$compatibleContactEmail = $_POST["dsp_ypcompatible_contact_email"];
							$compatibleOrgUrl = $_POST["dsp_ypcompatible_org_url"];
							$compatibleLayerName = $_POST["dsp_ypcompatible_product_layer_name"];
							$compatibleLayerUrl = $_POST["dsp_ypcompatible_product_layer_url"];
							// Radio Fields
							$radioCompatibleEligible = $_POST["dsp_ypcompatible_org_is_eligble"];
							$radioCompatibleTowardGoals = $_POST["dsp_ypcompatible_working_toward_goals"];
							$radioCompatiblePromoting = $_POST["dsp_ypcompatible_promoting"];
							$radioCompatibleContributes = $_POST["dsp_ypcompatible_contributes"];
							$radioCompatiblePubliclyListed = $_POST["dsp_ypcompatible_publicly_listed"];
							$radioCompatibleBuildSystemsIncluded = $_POST["dsp_ypcompatible_included_build_systems"];
							$radioCompatibleBuildSystemsCompliant = $_POST["dsp_ypcompatible_build_systems_compliant"];
							$radioCompatiblePatchesApplied = $_POST["dsp_ypcompatible_build_patches_applied"];
							$radioCompatibleHaveReadme = $_POST["dsp_ypcompatible_have_readme"];
							$radioCompatibleListedReadme = $_POST["dsp_ypcompatible_listed_in_readme"];
							$radioCompatibleSuccessfullPassed = $_POST["dsp_ypcompatible_successfully_passed"];
							$radioCompatibleBspFormat = $_POST["dsp_ypcompatible_bsp_format"];
							$radioCompatibleHardwareSupport = $_POST["dsp_ypcompatible_hardware_support"];
							$radioCompatibleTestSupport = $_POST["dsp_ypcompatible_test_support"];
							$radioLinuxKernels = $_POST["dsp_ypcompatible_linux_kernels"];
							$radioCompatibleBuildToolchain = $_POST["dsp_ypcompatible_builds_with_toolchain"];
							$radioCompatibleBuildsDiscrepancies = $_POST["dsp_ypcompatible_builds_discrepancies"];
							// Textarea Fields
							$textareaCompatibleExplanation = $_POST["dsp_ypcompatible_builds_comments"];
							
							// Update  Postmeta Fields
							update_post_meta ($post_success, 'dsp_ypcompatible_contact_name', $compatibleContactName);
							update_post_meta ($post_success, 'dsp_ypcompatible_contact_email', $compatibleContactEmail);
							update_post_meta ($post_success, 'dsp_ypcompatible_org_url', $compatibleOrgUrl);
							update_post_meta ($post_success, 'dsp_ypcompatible_product_layer_name', $compatibleLayerName);
							update_post_meta ($post_success, 'dsp_ypcompatible_product_layer_url', $compatibleLayerUrl);
							
							update_post_meta ($post_success, 'dsp_ypcompatible_org_is_eligble', $radioCompatibleEligible);
							update_post_meta ($post_success, 'dsp_ypcompatible_working_toward_goals', $radioCompatibleTowardGoals);
							update_post_meta ($post_success, 'dsp_ypcompatible_promoting', $radioCompatiblePromoting);
							update_post_meta ($post_success, 'dsp_ypcompatible_contributes', $radioCompatibleContributes);
							update_post_meta ($post_success, 'dsp_ypcompatible_publicly_listed', $radioCompatiblePubliclyListed);
							update_post_meta ($post_success, 'dsp_ypcompatible_included_build_systems', $radioCompatibleBuildSystemsIncluded);
							update_post_meta ($post_success, 'dsp_ypcompatible_build_systems_compliant', $radioCompatibleBuildSystemsCompliant);
							update_post_meta ($post_success, 'dsp_ypcompatible_build_patches_applied', $radioCompatiblePatchesApplied);
							update_post_meta ($post_success, 'dsp_ypcompatible_have_readme', $radioCompatibleHaveReadme);
							update_post_meta ($post_success, 'dsp_ypcompatible_listed_in_readme', $radioCompatibleListedReadme);
							update_post_meta ($post_success, 'dsp_ypcompatible_successfully_passed', $radioCompatibleSuccessfullPassed);
							update_post_meta ($post_success, 'dsp_ypcompatible_bsp_format', $radioCompatibleBspFormat);
							update_post_meta ($post_success, 'dsp_ypcompatible_hardware_support', $radioCompatibleHardwareSupport);
							update_post_meta ($post_success, 'dsp_ypcompatible_test_support', $radioCompatibleTestSupport);
							update_post_meta ($post_success, 'dsp_ypcompatible_linux_kernels', $radioLinuxKernels);
							update_post_meta ($post_success, 'dsp_ypcompatible_builds_with_toolchain', $radioCompatibleBuildToolchain);
							update_post_meta ($post_success, 'dsp_ypcompatible_builds_discrepancies', $radioCompatibleBuildsDiscrepancies);
							
							update_post_meta ($post_success, 'dsp_ypcompatible_builds_comments', $textareaCompatibleExplanation);
						
						}elseif($terms == "consultants"){
							// Text Fields
							$consultantsContactName = $_POST["dsp_consultant_contact_name"];
							$consultantsContactEmail = $_POST["dsp_consultant_contact_email"];
							$consultantsContactPhone = $_POST["dsp_consultant_contact_phone"];
							$consultantsWebsite = $_POST["dsp_consultant_website"];
							// Address
							$city = $_POST["djd_site_post_city"];
							$state = $_POST["djd_site_post_state"];
							$country = $_POST["djd_site_post_country"];
							// Checkbox
							$consultantsServicesOffered = $_POST["dsp_consultant_services_offered"];
							
							// Update  Postmeta Fields
							update_post_meta ($post_success, 'dsp_consultant_contact_name', $consultantsContactName);
							update_post_meta ($post_success, 'dsp_consultant_contact_email', $consultantsContactEmail);
							update_post_meta ($post_success, 'dsp_consultant_contact_phone', $consultantsContactPhone);	
							update_post_meta ($post_success, 'dsp_consultant_website', $consultantsWebsite);
							
							update_post_meta ($post_success, 'my_meta_box_city_text', $city);
					    	update_post_meta ($post_success, 'my_meta_box_state_select', $state);
					    	update_post_meta ($post_success, 'my_meta_box_country_select', $country);
					    
							update_post_meta ($post_success, 'dsp_consultant_services_offered', $consultantsServicesOffered);
						
						}elseif($terms == "members"){
							// Text Fields
							$membersContactName = $_POST["dsp_member_company_contact"];
							$membersContactEmail = $_POST["dsp_member_company_email"];
							$membersContactEmail = $_POST["dsp_member_company_phone"];
							$membersWebsite = $_POST["dsp_member_company_website"];
							// Address
							$city = $_POST["djd_site_post_city"];
							$state = $_POST["djd_site_post_state"];
							$country = $_POST["djd_site_post_country"];
							// Radio
							$radioMembersCompanySize = $_POST["dsp_member_company_size"];
							$radioMembersCorporateMember = $_POST["dsp_member_corporate_member"];
							// Text Areas
							$textAreaMembersUseProject = $_POST["dsp_member_use_project"];
							$textAreaMembersMembershipHelp = $_POST["dsp_member_membership_help"];
							
							
							update_post_meta ($post_success, 'dsp_member_company_contact', $membersContactName);
							update_post_meta ($post_success, 'dsp_member_company_email', $membersContactEmail);
							update_post_meta ($post_success, 'dsp_member_company_phone', $membersContactEmail);
							update_post_meta ($post_success, 'dsp_member_company_website', $membersWebsite);
							
							update_post_meta ($post_success, 'dsp_member_company_size', $radioMembersCompanySize);
							update_post_meta ($post_success, 'dsp_member_corporate_member', $radioMembersCorporateMember);
							
							update_post_meta ($post_success, 'dsp_member_use_project', $textAreaMembersUseProject);
							update_post_meta ($post_success, 'dsp_member_membership_help', $textAreaMembersMembershipHelp);
							
							update_post_meta ($post_success, 'my_meta_box_city_text', $city);
					    	update_post_meta ($post_success, 'my_meta_box_state_select', $state);
					    	update_post_meta ($post_success, 'my_meta_box_country_select', $country);
						}
					}
					
					
					if($djd_post_type == 'jobs' || $djd_post_type == 'events'){
						
						$city = $_POST["djd_site_post_city"];
						$state = $_POST["djd_site_post_state"];
						$country = $_POST["djd_site_post_country"];
						update_post_meta ($post_success, 'djd_site_post_link', $link);
						update_post_meta ($post_success, 'my_meta_box_city_text', $city);
					    update_post_meta ($post_success, 'my_meta_box_state_select', $state);
					    update_post_meta ($post_success, 'my_meta_box_country_select', $country);
					}
					

				}

				if($post_success === false) {
					$result = "error";
				}
				else {
					$result = "success";
					//if ( 'publish' == $my_post['post_status'] ) do_action('publish_post');
					if (isset($_POST['djd-post-format'])) {
						set_post_format( $post_success, wp_strip_all_tags($_POST['djd-post-format']));
					} else {
						set_post_format( $post_success, wp_strip_all_tags($djd_options['djd-post-format-default']));
					}
				}				

				if( array_key_exists('djd_site_post_guest_name', $_POST)) {
					add_post_meta( $post_success, 'guest_name', wp_strip_all_tags($_POST['djd_site_post_guest_name']), true ) || update_post_meta( $post_success, 'guest_name', wp_strip_all_tags($_POST['djd_site_post_guest_name']) );
				}
				if( array_key_exists('djd_site_post_guest_email', $_POST)) {
					add_post_meta( $post_success, 'guest_email', wp_strip_all_tags($_POST['djd_site_post_guest_email']), true ) || update_post_meta( $post_success, 'guest_name', wp_strip_all_tags($_POST['djd_site_post_guest_name']) );
				}
				
				if(apply_filters('form_abort_on_failure', true, $form_name))
					$success = $post_success;
				if($success){
					if($djd_options['djd-mail']) {
						$this->djd_sendmail($post_success, wp_strip_all_tags($_POST['djd_site_post_title']));
					}
					if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
						echo $result;
					} else {
						setcookie('form_ok', 1,  time() + 10, '/');
						header("Location: ".$_SERVER["HTTP_REFERER"]);
						die();
					}
				}
				else {
					throw new Exception( $djd_options['djd-post-fail'] ? $djd_options['djd-post-fail'] : __('We were unable to accept your post at this time. Please try again. If the problem persists tell the site owner.', 'djd-site-post'));
				}
		} // isset($_POST)
		die();
	} //function process_site_post_form
	
	/**
	 * Notify admin about new post via email
	 */
	 
	function send_consultant_email($name, $email, $emailCopy){
		$blogname = get_option('blogname');
		$headers = "MIME-Version: 1.0\r\n" . "From: ".$blogname." "."<".$email.">\n" . "Content-Type: text/HTML; charset=\"" . get_option('blog_charset') . "\"\r\n";
		//$content = '<p>'.__('Your anual Yocto Consultant status has expired.', 'djd-site-post') . '<br/>' .__('To resubcribe please fill out the form here:', 'djd-site-post') . ' '.'<a href="https://caffelli-staging.yoctoproject.org/community/consultants/consultant-registration/"><strong>Yocto Consultant Regitration</strong></a><br<br>Thanks, The Yocto Team</p>';
		$content = '<p>' .  $emailCopy . '</p>';
		wp_mail($email, __('Yocto Consultant Expiration Notice', 'djd-site-post'), $content, $headers);
	}
	
	function djd_sendmail ($post_id, $post_title) {
		$djd_options = get_option('djd_site_post_settings');
		if ( isset($djd_options['djd-guest-account']) ) {
			$user_query = get_userdata($djd_options['djd-guest-account']);
			$email = $user_query->user_email;
		}else{
			$email = get_option('admin_email');
		}
		$blogname = get_option('blogname');
		
		$headers = "MIME-Version: 1.0\r\n" . "From: ".$blogname." "."<".$email.">\n" . "Content-Type: text/HTML; charset=\"" . get_option('blog_charset') . "\"\r\n";
		$content = '<p>'.__('New Website Form Submission to', 'djd-site-post').' '.$blogname.'.'.'<br/>' .__('To view the entry click here:', 'djd-site-post') . ' '.'<a href="'.get_permalink($post_id).'"><strong>'.$post_title.'</strong></a></p>';
		wp_mail($email, __('New Yocto Form Submission', 'djd-site-post') . ' ' . $blogname . ': ' . $post_title, $content, $headers);
	}
	
	/**
	 * Print the post form at the front end
	 */
	function site_post_form($attrs, $content = null, $verified_user, $djd_post_id, $called_from_widget){
		ob_start();
		global $current_user; //Global WordPress variable that stores what wp_get_current_user() returns.
		wp_get_current_user();
		$djd_options = get_option('djd_site_post_settings'); //Read the plugin's settings out of wpdb table wp_options.

		// Render the form html

		if ( !empty ($attrs['dynamic_post_type']) ){
			$dynamic_post_type = $attrs['dynamic_post_type'];
			$checkFilePath = sprintf('%s/views/display-' . $dynamic_post_type . '.php', dirname(__FILE__));
			if (file_exists($checkFilePath)) {
			    // File exists";
				include_once ($checkFilePath);
			 } else {
			    // File does not exist";
			    include_once (sprintf("%s/views/display.php", dirname(__FILE__)));
			 }
		}else{
			include_once (sprintf("%s/views/display.php", dirname(__FILE__)));
		} 
		

		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	/**
	* Send debug code to the Javascript console
	*/
	function dtc($data) {
		if(is_array($data) || is_object($data))
		{
			echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
		} else {
			echo("<script>console.log('PHP: ".$data."');</script>");
		}
	}

    } // end class
} // end if (!class_exists)

$djd_site_post = new DjdSitePost();
