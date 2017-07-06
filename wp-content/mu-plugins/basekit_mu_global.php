<?php
/**
 * Must-Use Functions
 * 
 * A class filled with functions that will never go away upon theme deactivation.
 * 
 * @package WordPress
 * @subpackage BASEKIT
 */
class BASEKIT_Function_Global {
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'define_constants' ), 1 );
		add_action( 'init', array( $this, 'add_default_media_view' ) );
		//add_action( 'init', array( $this, 'add_slider_options' ) ); add slider options tab (coming later *to-do*)
		//add_action( 'init', array( $this, 'add_slider_meta_fileds' ) );
		
	}
	
	
	public function define_constants() {
		
	}
	
	
	public function add_default_media_view() {
		add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );
	}
	
	/**
	 * Enqueues scripts in the backend.
	 *
	 * @since 1.0
	 *
	 */
    function admin_enqueue_scripts(){  

		wp_enqueue_script( 'default-media-uploader-view', plugins_url( '/default-media-uploader-view.js', __FILE__ ), array( 'jquery', 'media-editor' ), false, true );
		
    }
		

}

$BASEKIT_Function_Global = new BASEKIT_Function_Global();