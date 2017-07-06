<?php
/**
 * Must-Use CTP & Taxonomy Plugin
 * 
 * A Custom Class for Custom Post Types and Taxonomies
 * 
 * @package WordPress
 * @subpackage BASEKIT
 */
class BASEKIT_Functions {
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'define_constants' ), 1 );
		add_action( 'init', array( $this, 'add_post_type' ) );
		//add_action( 'init', array( $this, 'add_taxonomy' ) );
	}
	
	public function define_constants() {
		
	}
	

	
	public function add_post_type() {
		//$this->setup_post_type( array( 'App', 'Apps', 'apps', 'apps' ), array( 'taxonomies' => array( 'app-category' ) ) );
		$this->setup_post_type( array( 'Featured Block', 'Featured Blocks', 'featured-blocks', 'featured-blocks' ), array() );
		$this->setup_post_type( array( 'Ambassador', 'Ambassadors', 'ambassadors', 'ambassadors' ), array() );
		$this->setup_post_type( array( 'Software Item', 'Software Items', 'software-item', 'software-item' ), array() );
		$this->setup_post_type( array( 'Document', 'Documents', 'documents', 'documents' ), array() );
		$this->setup_post_type( array( 'Learn Item', 'Learn Items', 'learn-items', 'learn-items' ), array() );
		$this->setup_post_type( array( 'Member', 'Members', 'members', 'members' ), array() );
	}
	
		public function setup_post_type( $type, $args = array() ) {
			if ( is_array( $type ) ) {
				$types = isset( $type[1] ) ? $type[1] : $type . 's';
				$key = isset( $type[2] ) ? $type[2] : strtolower( str_ireplace( ' ', '_', $type[1] ) );
				$slug = isset( $type[3] ) ? $type[3] : str_ireplace( '_', '-', $key );
				$type = $type[1];
			} else {
				$types = $type . 's';
				$key = strtolower( str_ireplace( ' ', '_', $type ) );
				$slug = str_ireplace( '_', '-', $key );
			}
			$labels = array(
				'name'                => _x( $type, 'post type general name' ),
				'singular_name'       => _x( $type, 'post type singular name' ),
				'add_new'             => _x( 'Add New', $type ),
				'add_new_item'        => __( 'Add New ' . $type ),
				'edit_item'           => __( 'Edit ' . $type ),
				'new_item'            => __( 'New ' . $type ),
				'view_item'           => __( 'View ' . $type ),
				'search_items'        => __( 'Search ' . $types ),
				'not_found'           => __( 'No ' . $types . ' found' ),
				'not_found_in_trash'  => __( 'No ' . $types . ' found in Trash' ),
				'menu_name'           => $types
			);
			$rewrite = array(
				'slug'                => $slug,
				'with_front'          => true,
				'pages'               => true,
				'feeds'               => true,
			);
			$args = wp_parse_args( $args, array(
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable' => true,
		      	'show_ui' => true,
		      	'show_in_menu' => true,
		      	'show_in_nav_menus' => true,
				'query_var'           => true,
				'rewrite'             => true,
				'capability_type'     => 'page',
				'hierarchical'        => false,
				'menu_position'       => null, //'5',
				'has_archive'         => true,
				'exclude_from_search' => false,
				'supports'			  => array( 'title','editor','thumbnail','excerpt','custom-fields', 'page-attributes', 'revisions' ),
				'taxonomies'          => array('category')
			));
			register_post_type( $key, $args );
		}
	public function add_taxonomy() {
		$this->setup_taxonomy( 'App Category', 'App Categories', 'app-category', 'app-category', array( 'apps' ), true, 'intelrealsense' );
		$this->setup_taxonomy( 'Payment Type', 'Payment Types', 'payment-type', 'payment-type', array( 'apps' ), true, 'intelrealsense' );
		$this->setup_taxonomy( 'Camera Support Option', 'Camera Support Options', 'camera-support-option', 'camera-support-option', array( 'apps' ), true, 'intelrealsense' );
		$this->setup_taxonomy( 'Windows Version', 'Windows Versions', 'window-version', 'window-version', array( 'apps' ), false, 'intelrealsense' );
		$this->setup_taxonomy( 'Language', 'Languages', 'language', 'language', array( 'apps' ), false, 'intelrealsense' );
	}
		public function setup_taxonomy( $type, $types, $key, $url_slug, $post_type_keys, $hierarchical, $theme) {
			$labels = array(
				'name'                       => _x( $types, 'taxonomy general name', $theme ),
				'singular_name'              => _x( $type, 'taxonomy singular name', $theme  ),
				'search_items'               => __( 'Search ' . $types, $theme ),
				'popular_items'              => __( 'Common ' . $types, $theme ),
				'all_items'                  => __( 'All ' . $types, $theme ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit ' . $type, $theme ),
				'update_item'                => __( 'Update ' . $type, $theme ),
				'add_new_item'               => __( 'Add New ' . $type, $theme ),
				'new_item_name'              => __( 'New ' . $type . ' Name', $theme ),
				'separate_items_with_commas' => __( 'Separate ' . $types . ' with commas', $theme ),
				'add_or_remove_items'        => __( 'Add or remove ' . $types, $theme ),
				'choose_from_most_used'      => __( 'Choose from the most used ' . $types, $theme )
			);
			$rewrite = array(
				'slug'                       => $url_slug,
				'with_front'                 => true,
				'hierarchical'               => true,
			);
			$args = array(
				'labels'                     => $labels,
				'hierarchical'               => $hierarchical,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
				'query_var'                  => true,
				'rewrite'                    => $rewrite
			);
			register_taxonomy( $key, $post_type_keys, $args );
		}
}
$BASEKIT_Functions = new BASEKIT_Functions();