<?php

namespace Roots\Sage\Carousel;
use Roots\Sage\Assets;
use WP_Query;


/**
 * Add Carousel Functions
 */
 
 // Carousel Funcitons
 
function carousel_assets() {  // add js (ust also add in manifest.js so it get build in dist directory)
	wp_enqueue_script('carousel_js', Assets\asset_path('scripts/carousel.js'), ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\carousel_assets', 100);


function setup_init() {  // carousel image sizes
	custom_thumbnails();
}


add_action( 'init', __NAMESPACE__ . '\\setup_init', 0 );
 
function custom_thumbnails(){
	add_image_size('carousel-thumbnail', 371, 222, true);
	add_image_size('carousel-large', 1300, 731, true);
}


function show_app_carousel($postID){ // gets the custom field(checkbox) for whether to show the app carousel or not - used in content-single-app
	$values = get_post_custom( $postID );
    $output = isset( $values['basekit_use_carousel_check'] ) ? esc_attr( $values['basekit_use_carousel_check'][0] ) : '';
	return $output;
}

function custom_app_carousel_function($postID) { // gets carousel for app (media library) - used in content-single-app
	$output = '';
	$output .= '</div>'; // close out of container to go full width
	$output .= '<div class="carousel-container">';
	$output .= '	<div class="container">';
	$output .= '		<div class="row">';
	$output .= do_shortcode( '[get_post_media_carousel_images post-id="' .  $postID . '" custom-post-type="page" title="false" featured-image="true" content="false" block-class="col-xs-4 media-block"]' );
	$output .= '		</div>';
	$output .= '	</div>';
	$output .= '</div>';
	//$output .= '<div class="container">';  // reopen container 
	return $output;
}

function get_post_media_carousel_images_function($atts){ // Gets media items (images) from a post that have been selected to use in carousel - used on content-single-app
	$output = '';
	
	$local_atts = shortcode_atts( array(
        'custom-post-type' => '',
        'order' => 'DESC',
        'title' => 'true',
        'featured-image' => 'false',
        'content' => 'true',
        'block-class' => 'block',
        'image-class' => '',
        'post-id' => ''
    ), $atts );


	$post_type = $local_atts[ 'custom-post-type' ];
	$post_order = $local_atts[ 'order' ];
	$title = $local_atts[ 'title' ];
	$featured_img = $local_atts[ 'featured-image' ];
	$content = $local_atts[ 'content' ];
	$block_class = $local_atts[ 'block-class' ];
	$image_class = $local_atts[ 'image-class' ];
	$post_ID = $local_atts[ 'post-id' ];
	
	// get number of attachmets that are checked to be used in the carousel (used to properly close carousel slides holding less than three items)
	$args = array(
		'post_parent'     => $post_ID,
	    'post_type'   => 'attachment',
	    'post_status' => 'inherit',       //<<-- IMPORTANT
	    'meta_query'  => array(
	        array(
	            'key'     => '_use_as_project_image',
	            'value'   => true,
	            'compare' => '='
	        )
	    )
	);
	
	$attachmentQuery = new WP_Query($args);
	$carousel_item_count = $attachmentQuery->found_posts;
	
	$args=array(
      'post_id' => $post_ID,
      'post_type' => $post_type,
      //'category_name' => $tax,
      'post_status' => 'publish',
      'posts_per_page' => 1,
      'order' => $post_order,
    );
	
    $my_query = null;
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
        $output .= '	<div id="carousel-media" class="carousel slide" data-interval="false">';  //
	    $output .= '		<div class="carousel-inner" role="listbox">';
		while ($my_query->have_posts()) : $my_query->the_post();
			
			if ( isset( $attr['orderby'] ) ) {
				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
					unset( $attr['orderby'] );
			}
		
			extract(shortcode_atts(array(
				'orderby'    => 'menu_order DESC, ID DESC',
				'id'         =>  $post_ID,  
				'itemtag'    => 'dl',
				'icontag'    => 'dt',
				'captiontag' => 'dd',
				'exclude' => '',
				'adhoc' => ''
			), $atts));
		
			$id = intval($id);
					
		    $attachments = get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");
			$exclude = array_map('trim', explode(',', $exclude)); // removed array_flip()
			$itemtag = tag_escape($itemtag);
			$captiontag = tag_escape($captiontag);
		
			$columnCounter = 0; // three inside slide
			$slideCounter = 0;
			foreach ( $attachments as $id => $attachment ) {
				
				if (!get_post_meta($attachment->ID, '_use_as_project_image', true)) //if(isset($exclude[$c]))
					continue;
				 
					$gallery_thumbnail_image_href = wp_get_attachment_image_src($attachment->ID, 'carousel-thumbnail', false); //--> options are ( thumbnail, medium, large, full)
					$gallery_medium_image_href = wp_get_attachment_image_src($attachment->ID, 'medium', false);  //--> options are ( thumbnail, medium, large, full)
					$gallery_large_image_href = wp_get_attachment_image_src($attachment->ID, 'large', false);  //--> options are ( thumbnail, medium, large, full)
					$gallery_full_image_href = wp_get_attachment_image_src($attachment->ID, 'full', false);  //--> options are ( thumbnail, medium, large, full)
					$link_url = get_post_meta($attachment->ID, '_url_link', true);  //-->  wp_get_attachment_url($id);   <--// for using real link url
		
					$img_title = trim(htmlspecialchars($attachment->post_title, ENT_QUOTES ));
					$img_caption = trim(htmlspecialchars($attachment->post_excerpt, ENT_QUOTES));
					$img_description = trim(htmlspecialchars($attachment->post_content, ENT_QUOTES));
					
					//$output .= '<a href="' . $gallery_large_image_href[0] .'" class="project-image" title="' . $img_title . '"><img src="' . $gallery_thumbnail_image_href[0] . '" title="' . $img_title . '" alt="' . $img_caption . '" width="' . $gallery_thumbnail_image_href[1] . '" height="' . $gallery_thumbnail_image_href[2] . '" class="attachment-full" /></a>';
			
				if ($columnCounter % 3 == 0) {
					if($columnCounter == 0){
						$output .= '<div class="item active">';
					}else{
						$output .= '<div class="item">';
					}
				}
				$output .= '	<div class="' . $block_class . '">';
				$output .= '		<div class="col-inner">';
				if(get_post_meta($attachment->ID, '_project_slide_video_path', true) != "") {
					$video_path = get_post_meta($attachment->ID, '_project_slide_video_path', true);
					$output .= '			<a href="' . $video_path .'" rel="carousel-image" class="cboxElement media-item-video" title="' . $img_title . '">';
					$output .= '				<img src="' . $gallery_thumbnail_image_href[0] . '" title="' . $img_title . '" alt="' . $img_caption . '" width="' . $gallery_thumbnail_image_href[1] . '" height="' . $gallery_thumbnail_image_href[2] . '" class="attachment-full img-responsive" />';
					$output .= '				<span class="play-btn"></span>';
					$output .= '			</a>';
				}elseif(get_post_meta($attachment->ID, '_project_slide_embed_code', true) != ""){
					$embed_code = get_post_meta($attachment->ID, '_project_slide_embed_code', true);
					$mediaShortCode = do_shortcode($embed_code);
					$output .= '			<div class="hide"><div id="app-embed-code-' . $slideCounter . '">' . $mediaShortCode . '</div></div>';
					$output .= '			<a href="#app-embed-code-' . $slideCounter . '" rel="carousel-image" class="cboxElement media-item-embed" title="' . $img_title . '">';
					$output .= '				<img src="' . $gallery_thumbnail_image_href[0] . '" title="' . $img_title . '" alt="' . $img_caption . '" width="' . $gallery_thumbnail_image_href[1] . '" height="' . $gallery_thumbnail_image_href[2] . '" class="attachment-full img-responsive" />';
					$output .= '				<span class="play-btn"></span>';
					$output .= '			</a>';
				}else{
					$output .= '			<a href="' . $gallery_large_image_href[0] .'" rel="carousel-image" class="cboxElement media-item-image" title="' . $img_title . '"><img src="' . $gallery_thumbnail_image_href[0] . '" title="' . $img_title . '" alt="' . $img_caption . '" width="' . $gallery_thumbnail_image_href[1] . '" height="' . $gallery_thumbnail_image_href[2] . '" class="attachment-full img-responsive" /></a>';
				}
				$output .= '		</div>';
				$output .= '   </div>';
				
				$slideCounter++;
				$columnCounter++;
				if ($columnCounter % 3 == 0 || $slideCounter == $carousel_item_count) {
					$output .= '</div>';
				}
			}	
		endwhile;
		$output .= '		</div>';
		$output .= '		<a class="left carousel-control" href="#carousel-media" role="button" data-slide="prev">';
	    $output .= '		 <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
	    $output .= '		 <span class="sr-only">Previous</span>';
	    $output .= '		</a>';
	    $output .= '		<a class="right carousel-control" href="#carousel-media" role="button" data-slide="next">';
	    $output .= '		 <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
	    $output .= '		 <span class="sr-only">Next</span>';
	    $output .= '		</a>';
	  	$output .= '	</div>';
	}
	wp_reset_postdata();
	wp_reset_query();
	return $output;
}
add_shortcode('get_post_media_carousel_images', __NAMESPACE__ . '\\get_post_media_carousel_images_function');
//-->> USAGE EXAMPLE : [get_post_media_carousel_images custom-post-type="projects" title="true" featured-image="true" content="false" block-class="col-xs-3 Project-block" post-id="33"]




// Carousel Meta Fields

add_action( 'admin_menu', __NAMESPACE__ . '\\meta_field_meta_box_add' );

function meta_field_meta_box_add() {
	add_meta_box(
		'meta_detail_fields_div', // meta box ID
		'Carousel Settings', // meta box title
		 __NAMESPACE__ . '\\meta_carousel_create_fields', // callback function that prints the meta box HTML 
		array('page'), // post/page/cpt type where to add it
		'normal', // priority
		'high' 
	); // position
}


function meta_carousel_create_fields( $post ) {  // app custom fields
	global $post;
	
	$currentPostID =  $post->ID;
	$currentPostType = get_post_type($currentPostID);
	
	//if( $currentPostType == "apps"){
	wp_nonce_field( 'basekit_box_nonce', 'meta_box_nonce' );
	$values = get_post_custom( $post->ID );
	$useCarouselCheck = isset( $values['basekit_use_carousel_check'] ) ? esc_attr( $values['basekit_use_carousel_check'][0] ) : '';
	?>
	<p>
		<input type="checkbox" id="basekit_use_carousel_check_<?php echo $useCarouselCheck; ?>" name="basekit_use_carousel_check" <?php echo checked( $useCarouselCheck, 'on' ); ?> />
		<label for="basekit_use_carousel_check">Check to show Carousel Slider for this App.</label>
	</p> 
	<?php
}


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

	if(isset( $_POST['basekit_use_carousel_check'] ) )
		$app_show_carousel_chk = isset( $_POST['basekit_use_carousel_check'] ) && $_POST['basekit_use_carousel_check'] ? 'on' : 'off';
    	update_post_meta( $post_id, 'basekit_use_carousel_check', $app_show_carousel_chk );	
	
	return $post_id;
}

// Carousel Attachement Meta Fields (in media gallery)

$use_as_project_image = false;
$project_slide_video_path = "";
$project_slide_embed_code = "";

function add_attachment_fields_edit($form_fields, $post) {
	
	$currentPostID = $post->post_parent;
	$currentPostType = get_post_type($currentPostID);
	//if( $currentPostType == "apps"){ 						// only show these media optios for the 'apps' custom post type 

		$use_as_project_image =  (bool) get_post_meta($post->ID, '_use_as_project_image', true);
		$project_slide_video_path = (string) get_post_meta($post->ID, '_project_slide_video_path', true);
		$project_slide_embed_code = (string) get_post_meta($post->ID, '_project_slide_embed_code', true);
		
		$form_fields['use_as_project_image'] = array(
	    'label' => 'Use as Project Image', //->post_type
	    'input' => 'html',
	    'html' => '<label for="attachments-'.$post->ID.'-use_as_project_image"> '.
	        '<input type="checkbox" id="attachments-'.$post->ID.'-use_as_project_image" name="attachments['.$post->ID.'][use_as_project_image]" value="1"'.($use_as_project_image ? ' checked="checked"' : '').' /></label>  ',
	    'value' => $use_as_project_image,
	    'helps' => 'Check this if you would like to use this as a project Image (these will show in the carousel slider).'
	    );
		
		$form_fields['project_slide_video_path'] = array(
	    'label' => 'Project Slide Video Path',
	    'input' => 'text',
	    'value' => $project_slide_video_path,
	    'helps' => 'Add the video path here if image links to a video.(Youtube Video)'
	    );
		
		$form_fields['project_slide_embed_code'] = array(
	    'label' => 'Project Slide Shortcode/Embed Code',
	    'input' => 'textarea',
	    'value' => $project_slide_embed_code,
	    'helps' => 'Add shortcode or embed code here if image links to this dynamic content.'
	    );
	    return $form_fields;
    //}
}

function add_attachment_fields_save($post, $attachment) {
	if( $post->post_type == "apps"){
		
	}
	if ( isset($attachment['use_as_project_image']) ) //use_in_gallery
	update_post_meta($post['ID'], '_use_as_project_image', $attachment['use_as_project_image']);
	$use_as_project_image = update_post_meta($post['ID'], '_use_as_project_image', $attachment['use_as_project_image']);
	
	if ( isset($attachment['project_slide_video_path']) ) //use_in_gallery
	update_post_meta($post['ID'], '_project_slide_video_path', $attachment['project_slide_video_path']);
	$project_slide_video_path = update_post_meta($post['ID'], '_project_slide_video_path', $attachment['project_slide_video_path']);
	
	if ( isset($attachment['project_slide_embed_code']) ) //use_in_gallery
	update_post_meta($post['ID'], '_project_slide_embed_code', $attachment['project_slide_embed_code']);
	$project_slide_video_path = update_post_meta($post['ID'], '_project_slide_embed_code', $attachment['project_slide_embed_code']);
	
	return $post;
}


if(is_admin()){
	add_filter( 'attachment_fields_to_edit', __NAMESPACE__ . '\\add_attachment_fields_edit', 10, 2); //array(&$this, 'fb_attachment_fields_edit')
	add_filter( 'attachment_fields_to_save', __NAMESPACE__ . '\\add_attachment_fields_save', 10, 2); //array(&$this, 'fb_attachment_fields_save'),
}
