<?php

namespace Roots\Sage\Header;

//use Roots\Sage\Assets;

/**
 * Add Theme Header Options
 */
 
 function get_custom_header($postID) { //gets header image (custom field) - used in content-single-app
	$headerImagevalue = get_post_meta( $postID, 'second_featured_img', true );
	$sliderValue = get_post_meta( $postID, 'basekit_header_slider_shortcode', true );
	$output = '';
	// Check if the custom field has a value.

	if ( ! empty( $headerImagevalue ) ) {
	    $image_size="full";
		//$output .=  '<div class="container"><div class="row">';  //use to fix width of header image
		if( $image_attributes = wp_get_attachment_image_src( $headerImagevalue, $image_size ) ) {
			// $image_attributes[0] - image URL
			// $image_attributes[1] - image width
			// $image_attributes[2] - image height
			$output .=  '<img src="' . $image_attributes[0] . '" class="header-image" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '" />';
		   
			if(get_post_meta($postID, 'basekit_header_gradient_check', true)) {  // IF checked in app show gradient overlay
				$output .=  '<img src="' . get_template_directory_uri() . '/dist/images/header-gradient-left.png" class="header-gradient-left"">';
				$output .=  '<img src="' . get_template_directory_uri() . '/dist/images/header-gradient-right.png" class="header-gradient-right"">';
			}
		}
		//$output .=  '</div></div>';
	}else if ( ! empty( $sliderValue ) ) {
		$output .= do_shortcode('[custom_slider id="' . $sliderValue . '" ]');
	}
	return $output;
}


add_action( 'admin_menu', __NAMESPACE__ . '\\meta_field_meta_box_add' );

function meta_field_meta_box_add() {
	add_meta_box('meta_field_div', // meta box ID
		'Header Options', // meta box title
		 __NAMESPACE__ . '\\meta_header_create_fields', // callback function that prints the meta box HTML 
		array('page'), // post type where to add it
		'normal', // priority
		'high' ); // position
}

function meta_header_create_fields( $post ) {
	global $post;
	
	// Featured Image
	$meta_key = 'second_featured_img';
	$value = get_post_meta($post->ID, $meta_key, true);
	$content = '<a href="#" class="meta_field_upload_image_button button">Upload image</a>';
	$image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
	$display = 'none'; // display state ot the "Remove image" button
 
 	// Use Gradient Overlay
 	$values = get_post_custom( $post->ID );
 	$checked = isset( $values['basekit_header_gradient_check'] ) ? esc_attr( $values['basekit_header_gradient_check'][0] ) : '';
	$headerSliderShortcode = isset( $values['basekit_header_slider_shortcode'] ) ? $values['basekit_header_slider_shortcode'] : '';
	
	if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
 
		// $image_attributes[0] - image URL
		// $image_attributes[1] - image width
		// $image_attributes[2] - image height
 
		$content = '<a href="#" class="meta_field_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
		$display = 'inline-block';
	} 
	
    // We'll use this nonce field later on when saving. - nonce is here because this feild is included on both taxonomies (app-category and case-studies)
    wp_nonce_field( 'basekit_box_nonce', 'meta_box_nonce' );
 	//return '
 	?>
	<div>
		<?php echo $content; ?>
		<input type="hidden" name="<?php echo $meta_key; ?>" id="<?php echo $meta_key; ?>" value="<?php echo $value; ?>" />
		<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove image</a> 
	</div>
	<div>
		<p>
	        <input type="checkbox" id="basekit_header_gradient_check_<?php echo $checked; ?>" name="basekit_header_gradient_check" <?php echo checked( $checked, 'on' ); ?> />
	        <label for="basekit_header_gradient_check">Check to add black gradient overlays to the sides of the header image.</label>
	    </p>
	    <p>
	        <div class="baseKit-label"><label for="basekit_header_slider_shortcode">Slider ID</label></div>
	        <div class="baseKit-input"><input style="width:100%;" type="text" name="basekit_header_slider_shortcode" id="basekit_header_slider_shortcode" value="<?php echo $headerSliderShortcode[0]; ?>" /></div>
	    </p>
	</div>
	<?php
}

/*
 * Save Meta Box data
 */
add_action('save_post', __NAMESPACE__ . '\\meta_field_save');

function meta_field_save( $post_id ) {
	// if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	// return $post_id;
		
	// Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'basekit_box_nonce' ) ) return;
 	
 	// if our current user can't edit this post, bail
 	if( !current_user_can( 'edit_post', $post_id ) ) return;
	
	// now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array(), // and those anchors can only have href attribute
        	'class' => array(),
        	'id' => array(),
        	'style' => array()
        ),
        'p' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'div' => array(
        	'class' => array(),
        	'id' => array(),
        	'style' => array()
        ),
        'span' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'img' => array(
        	'src' => array(), 
        	'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'h1' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'h2' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'h3' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'h4' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		),
        'h5' => array(
			'class' => array(),
        	'id' => array(),
        	'style' => array()
		)
    );
	
	$currentPostID =  $post_id;
	$currentPostType = get_post_type($currentPostID);
	$pageTemplate = get_post_meta($currentPostID, '_wp_page_template', true);
	
	// Header Slider
	if( isset( $_POST['basekit_header_slider_shortcode'] ) )
	    update_post_meta( $post_id, 'basekit_header_slider_shortcode', esc_attr( $_POST['basekit_header_slider_shortcode'] ) );
		
	// Header Image
	if( isset( $_POST['second_featured_img'] ) )
        update_post_meta( $post_id, 'second_featured_img', wp_kses( $_POST['second_featured_img'], $allowed ) );
	if(isset( $_POST['basekit_header_gradient_check'] ) )
		$header_gradient_check = isset( $_POST['basekit_header_gradient_check'] ) && $_POST['basekit_header_gradient_check'] ? 'on' : 'off';
    	update_post_meta( $post_id, 'basekit_header_gradient_check', $header_gradient_check );	
	return $post_id;
}