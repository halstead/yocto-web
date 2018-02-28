<?php
/**
 * Must-Use CTP Options Plugin
 * 
 * A Custom Class for adding Options/Settings to Custom Post Types
 * 
 * @package WordPress
 * @subpackage BASEKIT
 */
class BASEKIT_Options_Functions {
	
	//if ( is_admin() )
	    // $my_settings_page = new RushHourProjectArchivesAdminPage();
	
	public function __construct() {
	    add_action( 'admin_menu', array( $this, 'add_submenu_page_to_post_type' ) );
		add_action( 'admin_menu', array( $this, 'add_submenu_page_to_organization_post_type' ) );
	    add_action( 'admin_init', array( $this, 'sub_menu_page_init' ) );
	    //add_action( 'admin_init', array( $this, 'media_selector_scripts' ) );
	}
	
	/**
	 * Add sub menu page to the custom post type
	 */
	public function add_submenu_page_to_post_type() {
		add_submenu_page(
	        'edit.php?post_type=jobs',
	        __('Jobs Options', 'jobs'),
	        __('Jobs Options', 'jobs'),
	        'manage_options',
	        'jobs_section',
	        array($this, 'jobs_options_display'));
	}
	
	
	public function add_submenu_page_to_organization_post_type() {
	   add_submenu_page(
	        'edit.php?post_type=members',
	        __('Organization Options', 'members'),
	        __('Organization Options', 'members'),
	        'manage_options',
	        'members_section',
	        array($this, 'organization_options_display'));
	}
	
	/**
	 * Options page callback
	 */
	public function jobs_options_display() {
	    $this->options = get_option( 'options_jobs_field_group' );
	
	    wp_enqueue_media();
	
	    echo '<div class="wrap">';
	
	    printf( '<h1>%s</h1>', __('Jobs Options', 'jobs' ) ); 
	
	    echo '<form method="post" action="options.php">';
	
	    settings_fields( 'jobs_section' );
	
	    do_settings_sections( 'jobs-section-page' );
	
	    submit_button();
	
	    echo '</form></div>';
	}

	public function organization_options_display() {
		$this->options = get_option( 'options_organizations_field_group' );
	
	    wp_enqueue_media();
	
	    echo '<div class="wrap">';
	
	    printf( '<h1>%s</h1>', __('Organization Options', 'members' ) ); 
	
	    echo '<form method="post" action="options.php">';
	
	    settings_fields( 'organizations_section' );
	
	    do_settings_sections( 'organizations-section-page' );
	
	    submit_button();
	
	    echo '</form></div>';
	}

/**
 * Register and add settings
 */
	public function sub_menu_page_init() {
	    register_setting(
	        'jobs_section', // Option group
	        'options_jobs_field_group', // Option name
	        array( $this, 'sanitize' ) // Sanitize
	        );
	
	    add_settings_section(
	        'header_settings_section', // ID
	        __('Header Settings', 'jobs'), // Title
	        array( $this, 'print_section_info' ), // Callback
	        'jobs-section-page' // Page
	        );
	
	    add_settings_field(
	        'jobs_expire_setting', // ID
	        __('Jobs Expire Setting (Months)', 'jobs'), // Title
	        array( $this, 'jobs_expires_callback' ), // Callback
	        'jobs-section-page', // Page
	        'header_settings_section' // Section
	        );
			
		add_settings_field(
	        'jobs_minimum_blocks', // ID
	        __('Jobs Minumum Blocks Setting', 'jobs'), // Title
	        array( $this, 'jobs_minimum_block_callback' ), // Callback
	        'jobs-section-page', // Page
	        'header_settings_section' // Section
	        );
			
		add_settings_field(
	        'jobs_default_postID', // ID
	        __('Default Post ID', 'jobs'), // Title
	        array( $this, 'jobs_default_postID_callback' ), // Callback
	        'jobs-section-page', // Page
	        'header_settings_section' // Section
	        );


		///// organization settings
			
		register_setting(
	        'organizations_section', // Option group
	        'options_organizations_field_group', // Option name
	        array( $this, 'sanitize' ) // Sanitize
	        );
	
	    add_settings_section(
	        'header_settings_section', // ID
	        __('Header Settings', 'members'), // Title
	        array( $this, 'print_orgainzation_section_info' ), // Callback
	        'organizations-section-page' // Page
	        );
	
	    add_settings_field(
	        'consultants_expire_setting', // ID
	        __('Consultants Expire Setting (Months)', 'members'), // Title
	        array( $this, 'consultants_expire_block_callback' ), // Callback
	        'organizations-section-page', // Page
	        'header_settings_section' // Section
	        );
			
		add_settings_field(
	        'consultants_notification_setting', // ID
	        __('Consultants Notifications Setting (Months)', 'members'), // Title
	        array( $this, 'consultants_notification_setting_callback' ), // Callback
	        'organizations-section-page', // Page
	        'header_settings_section' // Section
	        );
			
		add_settings_field(
	        'consultants_email_copy', // ID
	        __('Consultants Email Text Setting', 'members'), // Title
	        array( $this, 'consultants_email_copy_callback' ), // Callback
	        'organizations-section-page', // Page
	        'header_settings_section' // Section
	        );
	
	    // add_settings_field(
	        // 'image_attachment_id',
	        // __('Header Background Image', 'jobs'),
	        // array( $this, 'header_bg_image_callback' ),
	        // 'projects-archive-page',
	        // 'header_settings_section'
	        // );
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
    	$new_input = array();

	    if( isset( $input['jobs_expire_setting'] ) )
	        $new_input['jobs_expire_setting'] = sanitize_text_field( $input['jobs_expire_setting'] );
		
		if( isset( $input['jobs_minimum_blocks'] ) )
	        $new_input['jobs_minimum_blocks'] = sanitize_text_field( $input['jobs_minimum_blocks'] );
		
		if( isset( $input['jobs_default_postID'] ) )
	        $new_input['jobs_default_postID'] = sanitize_text_field( $input['jobs_default_postID'] );
		
		// organization section
		if( isset( $input['consultants_expire_setting'] ) )
	        $new_input['consultants_expire_setting'] = sanitize_text_field( $input['consultants_expire_setting'] );
			
		if( isset( $input['consultants_notification_setting'] ) )
	        $new_input['consultants_notification_setting'] = sanitize_text_field( $input['consultants_notification_setting'] );
		
		if( isset( $input['consultants_email_copy'] ) )
	        $new_input['consultants_email_copy'] = ( $input['consultants_email_copy'] );
			
	    // if( isset( $input['image_attachment_id'] ) )
	        // $new_input['image_attachment_id'] = absint( $input['image_attachment_id'] );
	
	    return $new_input;
	}
	
	/**
	 * Print the Section text
	 */
	public function print_section_info()
	{
	    print 'Select options for Jobs.';
	}
	
	public function print_orgainzation_section_info()
	{
	    print 'Select options for Consultants.';
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function jobs_expires_callback() {
	    printf(
	        '<p>Enter number of months jobs will be visible on the website.</p><input type="text" id="jobs_expire_setting" name="options_jobs_field_group[jobs_expire_setting]" value="%s" />',
	        $expireMonth = isset( $this->options['jobs_expire_setting'] ) ? esc_attr( $this->options['jobs_expire_setting']) : '1'
		);
	}
	
	public function jobs_minimum_block_callback() {
		printf(
	        '<p>Enter mininum number of jobs before default job shows.</p><input type="text" id="jobs_minimum_blocks" name="options_jobs_field_group[jobs_minimum_blocks]" value="%s" />',
	        $minimumBlocks = isset( $this->options['jobs_minimum_blocks'] ) ? esc_attr( $this->options['jobs_minimum_blocks']) : '0'
		);
	}
	
	public function jobs_default_postID_callback() {
		printf(
	        '<p>Job post ID to be used as default. This job post will appear based off setting above.</p><input type="text" id="jobs_default_postID" name="options_jobs_field_group[jobs_default_postID]" value="%s" />',
	        $defaultID = isset( $this->options['jobs_default_postID'] ) ? esc_attr( $this->options['jobs_default_postID']) : ''
		);
	}

	/// organization callbacks
	
	public function consultants_expire_block_callback() {
		printf(
	        '<p>Enter number of months before consultants expire (Go into Draft Status).</p><input type="text" id="consultants_expire_setting" name="options_organizations_field_group[consultants_expire_setting]" value="%s" />',
	        $expirationMonths = isset( $this->options['consultants_expire_setting'] ) ? esc_attr( $this->options['consultants_expire_setting']) : '0'
		);
	}
	
	public function consultants_notification_setting_callback() {
		printf(
	        '<p>Enter number of months before consultants get sent reminder email.</p><input type="text" id="consultants_notification_setting" name="options_organizations_field_group[consultants_notification_setting]" value="%s" />',
	        $notificationMonths = isset( $this->options['consultants_notification_setting'] ) ? esc_attr( $this->options['consultants_notification_setting']) : '0'
		);
	}
	
	public function consultants_email_copy_callback() {
		printf(
	        '<p>Enter the copy for the consultant email informming them that they are about to expire.</p><textarea cols="80" rows="8" id="consultants_email_copy" name="options_organizations_field_group[consultants_email_copy]" value="" />%s</textarea>',
	        $emailCopy = isset( $this->options['consultants_email_copy'] ) ? esc_attr( $this->options['consultants_email_copy']) : ''
		);
	}
	
	/**
	 * Get the settings option array and print one of its values
	 */
	// public function header_bg_image_callback()
	// {
	    // $attachment_id = $this->options['image_attachment_id'];
// 	
	    // // Image Preview
	    // printf('<div class="image-preview-wrapper"><img id="image-preview" src="%s" ></div>', wp_get_attachment_url( $attachment_id ) );
// 	
	    // // Image Upload Button
	    // printf( '<input id="upload_image_button" type="button" class="button" value="%s" />',
	        // __( 'Upload image', 'rushhour' ) );
// 	
	    // // Hidden field containing the value of the image attachment id
	    // printf( '<input type="hidden" name="rushhour_projects_archive[image_attachment_id]" id="image_attachment_id" value="%s">',
	        // $attachment_id );
	// }
	
	// public function media_selector_scripts()
	// {
	    // $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
// 	
	    // wp_register_script( 'sub_menu_media_selector_scripts', get_template_directory_uri() . '/admin/js/media-selector.js', array('jquery'), false, true );
// 	
	    // $selector_data = array(
	        // 'attachment_id' => get_option( 'media_selector_attachment_id', 0 )
	        // );
// 	
	    // wp_localize_script( 'sub_menu_media_selector_scripts', 'selector_data', $selector_data );
// 	
	    // wp_enqueue_script( 'sub_menu_media_selector_scripts' );
	// }
}

$BASEKIT_Options_Functions = new BASEKIT_Options_Functions();