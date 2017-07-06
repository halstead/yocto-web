<?php 

namespace Roots\Sage\BlockBuilder;

use Roots\Sage\Setup;
use WP_Query;


//////------>> START - PROJECT BLOCK FUNCTIONS <<------//////

function custom_project_blocks_function($atts) {

	//-->> Shortcode way<<--//

	// $local_atts = shortcode_atts( array(
        // 'custom-post-type' => '',
        // 'order' => 'ASC',
        // 'title' => 'true',
        // 'featured-image' => 'false',
        // 'content' => 'true',
        // 'block-class' => 'block',
        // 'image-class' => ''
    // ), $atts );

    //$block_class = $local_atts[ 'block-class' ];

	//-->> Non-shortcode way<<--//

    //$customPostType = "custom-post-type";
	//$featuredImage = "featured-image";
	//$content = "content";
	$blockClass = "block-class";
	$block_class = $atts[$blockClass];
	$block_counter = 1;
	
	$output = '';
	

    global $post;
	$repeatable_project_fields = get_post_meta($post->ID, 'project-builder-meta-box-blocks', true);

	if ( $repeatable_project_fields ) :
		foreach ( $repeatable_project_fields as $key => $field ) {
			
			// Block Fields
			$blockType = '';
			$blockState = '';
			$blockWidthType = '';
			$blockPadding = '';
			$blockBackgroundColor = '';
			$blockContentContainerClass= '';
			
			$blockType = $field['blockType'];
			$blockState = $field['blockState'];
			$blockWidthType = $field['blockContentWidth'];
			$blockPadding = $field['blockContentPadding'];
			$blockBackgroundColor = $field['blockContentBackgroundColor'];
			$blockContentContainerClass = $field['blockContentContainerClass'];
			
			// Clear values so they doesn't get reused on other images
			
			// Content Fields
			$contentBlockTitle = '';
			$contentBlockCopy = '';
			
			// Image Fields
			$image = '';
			$imageOverlay = '';
			$imageLinkTarget = '';
			
			// Video Fields
			$video = '';
			$videoOverlay = '';
			$videoLinkTarget = '';
			$videoParameters = '';
			
			// Custom Blocks Fields
			$customBlocksType = '';
			$customBlocksTitle = '';
			$customBlocksCopy = '';
			$customBlocksCategory = '';
			$customBlocksCategory = '';
			$customBlocksShowFeaturedImage = '';
			$customBlocksShowTitle = '';
			$customBlocksShowDescription = '';
			$customBlocksCharacterCount = '';
			$customBlocksBlockCount = '';
			$customblocksLink = '';
				
			// $field['customblocksShowFeaturedImage'];
			// $field['customblocksShowTitle'];
			// $field['customblocksShowDescription'];
			// $field['customblocksCharacterCount'];
			// $field['customblocksCategory'];
			// $field['customblocksBlockCount'];
			// $field['customblocksLink'];
			
			
			
			//Test
			//$output .= "BlockBackgoundColor: " . $field['blockContentBackgroundColor'];
			
			
			// CONTENT BLOCK // 
			if($blockType == 'content' && $blockState == 'blockActive'){ 
				$contentBlockTitle = $field['contentBlockTitle'];
				$contentBlockCopy = $field['contentBlockCopy'];
				
				$contentEmbedCode = $field['contentShortcode'];
				//$mediaShortCode = do_shortcode($contentEmbedCode);
				//$output .= '<div>' . $mediaShortCode . '</div>';
				
				if($blockWidthType == 'fullwidth'){
					$output .= '<div class="' . $blockContentContainerClass . '" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">';
					if($contentBlockTitle != 'undefined' && $contentBlockTitle != ''){
						$output .= '<h2>' . $contentBlockTitle . '</h2>';
					}
					if($contentEmbedCode != 'undefined' && $contentEmbedCode != ''){
						$mediaShortCode = do_shortcode($contentEmbedCode);
						$output .= '<div>' . do_shortcode($contentEmbedCode) . '</div>';
					}
					if($contentBlockCopy != 'undefined' && $contentBlockCopy != ''){
						$output .= '<div>' . $contentBlockCopy . '</div>';
					}
			 		$output .= '</div>';
				}elseif($blockWidthType == 'contain'){
					$output .= '<div class="' . $blockContentContainerClass . '" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">';
						$output .= '<div class="container"><div class="inner">';
						if($contentBlockTitle != 'undefined' && $contentBlockTitle != ''){
							$output .= '<h2>' . $contentBlockTitle . '</h2>';
						}
						if($contentEmbedCode != 'undefined' && $contentEmbedCode != ''){
							$mediaShortCode = do_shortcode($contentEmbedCode);
							$output .= '<div>' . do_shortcode($contentEmbedCode) . '</div>';
						}
						if($contentBlockCopy != 'undefined' && $contentBlockCopy != ''){
							$output .= '<div>' . $contentBlockCopy . '</div>';
						}
						$output .= '</div></div>';
					$output .= '</div>';
				}else {
					$output .= '<div class="container"><div class="inner"><h5 class="alert">You must define a block width for this block.</h5></div></div>';
				}
			
			// IMAGE BLOCK // 	 
			} elseif($blockType == 'image' && $blockState == 'blockActive'){ 
				
				if (array_key_exists('imageSlide', $field) && $field['imageSlide'] != undefined) {
					$attachmentID = esc_attr( $field['imageSlide'] ); 
					if( $image_attributes = wp_get_attachment_image_src( $attachmentID, $image_size ) ) {
						$image = ' <img src="' . $image_attributes[0] . '" style="width:100%; height auto;" />';
					}
					
					$imageOverlay .= '<div class="relative-container"><div class="overlay-container">';
					$imageOverlay .= '	<div class="overlay">';
					if (array_key_exists('imageSlideOverlayTitle', $field) && $field['imageSlideOverlayTitle'] != ''){
						 $imageOverlay .= '<h5>' . $field['imageSlideOverlayTitle'] . '</h5>';
				    }
					if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != ''){
						 $imageOverlay .= '<p>' . $field['imageSlideOverlayCopy'] . '</p>';
				    }
					$imageOverlay .= '	</div>';
					$imageOverlay .= '</div></div>';
				}
				$imageLinkTarget = (array_key_exists('imageLinkTarget', $field) && $field['imageLinkTarget'] == 'newWindow' ? '_blank' : '_self'); 
			 	if($blockWidthType == 'fullwidth'){
			 		$output .= (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != '' ? '<a href="' . $field['imageSlideLink']  . '" target="' . $imageLinkTarget . '">' : ''); 		
			 		$output .= '<div class="' . $blockContentContainerClass . ' relative-container" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">' . $image . $imageOverlay . '</div>';
					$output .= (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != '' ? '</a>' : ''); 
				}elseif($blockWidthType == 'contain'){
					$output .= (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != '' ? '<a href="' . $field['imageSlideLink']  . '" target="' . $imageLinkTarget . '">' : ''); 	
					$output .= '<div class="' . $blockContentContainerClass . ' relative-container" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">';
					$output .= '	<div class="container relative-container">' . $image . $imageOverlay . '</div>';
					$output .= '</div>';
					$output .= (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != '' ? '</a>' : ''); 
				}else{
					$output .= '<div class="container"><div class="inner"><h5 class="alert">You must define a block width for this block.</h5></div></div>';
				}
				
			
			// VIDEO BLOCK //	
			} elseif($blockType == 'video' && $blockState == 'blockActive'){
			
				// $videoControls = ($field['videoControls'] == 'on') ? 'controls' : '';
				// $videoAutoplay = ($field['videoAutoplay'] == 'on') ? 'autoplay' : '';
				// $videoSound = ($field['videoSound'] == 'off') ? 'muted' : '';
				// $videoLoop = ($field['videoLoop'] == 'on') ? 'loop' : ''; 
				// $videoParameters = $videoControls . ' ' . $videoAutoplay . " " . $videoSound . " " . $videoLoop;
								
				if (array_key_exists('videoSlidePosterImage', $field) && $field['videoSlidePosterImage'] != '') {
					$videoPosterImageID = esc_attr( $field['videoSlidePosterImage'] ); 
					if( $image_poster_attributes = wp_get_attachment_image_src( $videoPosterImageID, $image_size ) ) {
						$videoPosterImagePath = $image_poster_attributes[0];
					}
				}
				if (array_key_exists('videoSlideSourceLink', $field) && $field['videoSlideSourceLink'] != '') {
					$video .= '<div class="video-container">';
				    $video .= '  	<video  controls ' . $videoParameters . '  preload="auto" width="100%" height="auto" class="vid" poster="' . $videoPosterImagePath . '">';
			        $video .= '  		<source src="' . $field['videoSlideSourceLink'] . '" type="video/mp4">';
			        $video .= '	</video>';
		            $video .= '</div>';
				}

				$videoOverlay .= '<div class="relative-container"><div class="overlay-container">';
				$videoOverlay .= '	<div class="overlay">';
				if (array_key_exists('videoSlideOverlayTitle', $field) && $field['videoSlideOverlayTitle'] != ''){
					 $videoOverlay .= '<h5>' . $field['videoSlideOverlayTitle'] . '</h5>';
			    }
				if (array_key_exists('videoSlideOverlayCopy', $field) && $field['videoSlideOverlayCopy'] != ''){
					 $videoOverlay .= '<p>' . $field['videoSlideOverlayCopy'] . '</p>';
			    }
				$videoOverlay .= '	</div>';
				$videoOverlay .= '</div></div>';
				$videoLinkTarget = (array_key_exists('videoLinkTarget', $field) && $field['videoLinkTarget'] == 'newWindow' ? '_blank' : '_self'); 
				if($blockWidthType == 'fullwidth'){
			 		$output .= '<div class="' . $blockContentContainerClass . ' relative-container" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">' . $video . $videoOverlay;
					$output .= (array_key_exists('videoSlideLink', $field) && $field['videoSlideLink'] != '' ? '<a href="' . $field['videoSlideLink']  . '" style="position:absolute; top:0px; left:0px; width:100%; min-height:80%;" target="' . $videoLinkTarget . '"></a>' : '');
					$output .= '</div>';
				}elseif($blockWidthType == 'contain'){
					$output .= '<div class="' . $blockContentContainerClass . ' relative-container" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">';
					$output .= '	<div class="container relative-container">';
					$output .= '		<div>' . $video . $videoOverlay . '</div>';
					$output .= (array_key_exists('videoSlideLink', $field) && $field['videoSlideLink'] != '' ? '<a href="' . $field['videoSlideLink']  . '" style="position:absolute; top:0px; left:0px; width:100%; min-height:80%;" target="' . $videoLinkTarget . '"></a>' : '');
					$output .= '	</div>';
					$output .= '</div>';
				}else{
					$output .= '<div class="container"><div class="inner"><h5 class="alert">You must define a block width for this block.</h5></div></div>';
				}
			
			
			// CUSTOM BLOCKS //	
			} elseif($field['blockType'] == 'customblocks' && $blockState == 'blockActive') {
				$customBlocksType = $field['customblocksType'];	
				$customBlocksTitle = $field['customblocksTitle'];
				$customBlocksCopy = $field['customblocksCopy'];
				$customBlocksCategory = $field['customblocksCategory'];
				$customBlocksShowFeaturedImage = $field['customblocksShowFeaturedImage'];
				$customBlocksShowTitle = $field['customblocksShowTitle'];
				$customBlocksShowDescription = $field['customblocksShowDescription'];
				$customBlocksCharacterCount = $field['customblocksCharacterCount'];
				$customBlocksBlockCount = $field['customblocksBlockCount'];
				$customblocksLink = $field['customblocksLink'];
				
				$attsArray = array(
				    "custom-post-type" => $customBlocksType, 
				    "featured-image" => $customBlocksShowFeaturedImage,
				    "title" => $customBlocksShowTitle,
				    "content" => $customBlocksShowDescription,
				    "block-class" => "col-xs-12 col-sm-4 section-block",
				    "block-number" => $customBlocksBlockCount,
				    "character-count" => $customBlocksCharacterCount,
				    "taxonomy" => 'category',
				    "terms" => $customBlocksCategory,
				    "block-link" => $customblocksLink
				    //"excluded-post-array"
				    // "meta_query_array" => array(
				      // 'key'   => 'basekit_featured_app_check',
				      // 'value' => 'on',
				      // 'compare' => '='
				    //)
				);
				
				if($blockWidthType == 'fullwidth'){
					$output .= '<div class="' . $blockContentContainerClass . '" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">';
				}elseif($blockWidthType == 'contain'){
					$output .= '<div class="' . $blockContentContainerClass . '" style="padding-top:' . $blockPadding . '; padding-bottom:' . $blockPadding . '; background-color:' . $blockBackgroundColor . ';">';
					$output .= '	<div class="container">';
				}else{
					$output .= '<div class="container"><div class="content-block"><h5 class="alert">You must define a block width for this block.</h5>';
				}
				if($blockWidthType === 'fullwidth' || $blockWidthType === 'contain'){
					if($customBlocksTitle != 'undefined' && $customBlocksTitle != ''){
						$output .= '<h2>' . $customBlocksTitle . '</h2>';
					}
					if($customBlocksCopy != 'undefined' && $customBlocksCopy != ''){
						$output .= '<div>' . $customBlocksCopy . '</div>';
					}
					if($customBlocksType != 'undefined' && $customBlocksType != ''){
						$output .= '<div class="row">';
							$output .=  custom_blocks($attsArray); // call to custom blocks function
							$output .= '</div>';
					}
				}
				if($blockWidthType == 'fullwidth'){
					$output .= '</div>';
				}else{
					$output .= '</div></div>';			
				}
			}
			$block_counter++;
		}
	else:
		$output .= '<div class="' . $block_class  . '" id="section-' . $block_counter . '" style="background-color:#db4103;">';
		$output .= '	<div class="container" role="document">';
	    $output .= '		<div class="content">';
		$output .= '			<h1 class="block-title">' . get_the_title() . '</h1>';
		$output .= '			<p>This Project has no content blocks.</p>';
		$output .= '		</div>';
		$output .= '	</div>';
		$output .= '</div>';
	endif;



	wp_reset_postdata();
	wp_reset_query();
	return $output;
}

//add_shortcode('custom_project_blocks', __NAMESPACE__ . '\\custom_project_blocks_function');
//-->> USAGE EXAMPLE : [custom_project_blocks custom-post-type="projects" title="true" featured-image="true" content="true" block-class="col-xs-12 section-block"]

//////------>> END - PROJECT BLOCK FUNCTIONS <<------//////



//////------>> START - GLOBAL FUNCTIONS <<------//////

function list_post_types($selectedOption = 0) {
	$output = '';
	$args=array(
     	'public'   => true//,
     	//'_builtin' => true
    ); 
    $content = 'names';
    $operator = 'and';
    $post_types=get_post_types($args, $content, $operator); 

    $output .= '<select name="customblocksType[]">';
    foreach ($post_types  as $post_type ) {
    	//$output .=  '<option value="'. $post_type.'">'. $post_type. '</option>';
		$output .=  '<option value="' . $post_type .'" ' . selected( $selectedOption, $post_type, false )  . ' >'. $post_type. '</option>';
	}
	$output .=  '</select>';
	return $output;
}

function custom_blocks ($atts) { // 
	$post_type = $atts["custom-post-type"];
	$featured_img = $atts["featured-image"];	
	$title = $atts["title"];	    
	$content = $atts["content"];
	$block_num = $atts["block-number"];
	$character_count = $atts["character-count"];
	$taxonomy = $atts["taxonomy"];
	$taxonomy_terms = $atts["terms"];
	$block_link = $atts["block-link"];
	$args = '';
	
	$character_count = ($character_count == '') ? 120 : $character_count; 
	
	if($taxonomy_terms == '' || $taxonomy_terms == undefined){
		// $taxonomy_terms = get_terms( $taxonomy, array(
		    // 'hide_empty' => 0,
		    // 'fields' => 'slugs'
		// ) );
		$args=array(
	      'post_type' => $post_type,
	      'post_status' => 'publish',
	      'posts_per_page' => $block_num,
	      'order' => 'DESC',
	    );
	}else{
		$args=array(
	      'post_type' => $post_type,
	      'post_status' => 'publish',
	      'posts_per_page' => $block_num,
	      'order' => 'DESC',
	      'tax_query' => array(
	        array(
	            'taxonomy' => 'category',
	            'field' => 'slug',
	            'terms' => $taxonomy_terms
	        	)
	    	)
	    );
	}

	$output = '';
	$my_query = null;
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
		while ($my_query->have_posts()) : $my_query->the_post();
			$output .= '<div class="col-xs-12 col-sm-6 col-md-4 ' . $post_type . '">';
	      	$output .= ($block_link == 'yes') ? '<a href="' . get_permalink(get_the_ID())  . '" class="inline-block">' : '';
	      	$output .= '		<div class="grid-block">';
		    $output .=  ($featured_img == 'checked' ? '<div class="grid-featured-image-container">' .	get_the_post_thumbnail(get_the_ID(), 'medium', array( 'class' => 'img-responsive' )) . '</div>' : ''); 
		    $output .= '			<div class="grid-block-copy">';
		    $output .=  ($title == 'checked' ? '<h6>' . get_the_title() . '</h6>' : '');
		    $output .=  ($content == 'checked' ? '<p>' . get_custom_excerpt( $character_count, 'excerpt' ) . '</p>' : ''); 
		    $output .= '			</div>';
		    $output .= '		</div>';
		    $output .= ($block_link == 'yes') ? '</a>' : '';
		    $output .= '	</a>';
	      	$output .= '</div>';
		endwhile;
	}
	wp_reset_postdata();
	wp_reset_query();
	return $output;
}


function get_custom_excerpt($limit, $source = null){ // Custom Excerpt function by character count

    if($source == "content" ? ($excerpt = get_the_content()) : ($excerpt = get_the_excerpt()));
	    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	    $excerpt = strip_shortcodes($excerpt);
	    $excerpt = strip_tags($excerpt);
	    $excerpt = substr($excerpt, 0, $limit);
	    //$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	    //$excerpt = $excerpt.'... <a href="'.get_permalink($post->ID).'">more</a>';
		//$excerpt = $excerpt . '...';
    return $excerpt;
}



//////------>> START - ADD CUSTOM LOCATION META BOXES TO PROJECT BUILDER FIELD <<------//////


add_action( 'admin_head', __NAMESPACE__ . '\\check_page_template' );
function check_page_template() {
    global $post;
    if ( 'template-block-builder.php' == get_post_meta( $post->ID, '_wp_page_template', true ) ) {
        project_builder_meta_box_add();
    }
}


function project_builder_meta_box_add() {
	add_meta_box( 'project-builder-meta-box-blocks', 'Project Block Builder', __NAMESPACE__ . '\\project_builder_meta_box', 'page', 'normal', 'high' );
	//debug_to_console('add meta box');
}


function project_builder_meta_box() {
	//debug_to_console('meta box callback');
    // $post is already set, and contains an object: the WordPress post
    global $post;
	
	//slider-builder-meta-box-blocks

	$repeatable_project_fields = get_post_meta(get_the_ID(), 'project-builder-meta-box-blocks', true);
    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'repeatable_meta_box_nonce', 'repeatable_meta_box_nonce' );
	
		
	if( is_admin() ) { 
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' );  
        // Include our custom jQuery file with WordPress Color Picker dependency
        //wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    	wp_enqueue_script( 'wp-color-picker');
	}
	?>

	<style>
		.toggle-indicator:before {
			content: "\f140";
		}

		button.custom-accordian {
			float: right;
			width: 36px;
			height: 36px;
			padding: 0;
			border:1px solid #e9e9e9 !important;
			margin:4px !important;
		}

		button.closed .toggle-indicator:before  {
			content: "\f140" !important;
		}

		button.custom-accordian.open .toggle-indicator:before  {
			content: "\f142"  !important;
		}

		div.project-block-field-containter {
			padding:6px;
			box-sizing:border-box;
		}
		
		.block-inner-container {
			width:100%;
			padding:20px 0px;
		}
		
		#slider-builder-meta-box-blocks td h4.project-block-type-title {
			text-align:center;
		}

		.block-type-fields {
			display:none;
		}

		.blockTypeRadioContainer {
			padding-top:14px;
			float:left;
		}
		
		#wp-content-editor-container, #post-status-info {  /* Hides Main Content Text Area*/
			display:none;
		}
		
		.block-builder-block {
			background-color: #f1f1f1;
		 	padding:5px; 
		 	box-sizing:border-box;
		}
		tr.blockActive {
			background-color:#eff9eb;
		}
		tr.blockInactive {
			background-color:#f9eceb;
		}
		
		.dashicons-info {
			margin-top:3px;
		}
		
		.dashicons-info:before {
		 	color:#0085ba;
		 	cursor:pointer;
		}
	</style>
	<script type="text/javascript">
	jQuery(document).ready(function($) {

		var sortableLenth = $('.blockTypeRadioContainer').length;

		$('.metabox_submit').click(function(e) {
			e.preventDefault();
			$('#publish').click();
		});

		$('#add-block').on('click', function(e) {
			e.preventDefault();
			var row = $('.empty-row.screen-reader-text').clone(true);
			row.removeClass('empty-row screen-reader-text');
			row.insertBefore('#repeatable-fieldset-one tbody:first>tr:last');
			var radioCount = $('input[name*="counter"]').length - 2; //--> There are two hidden ones we don't want to count
			var radioBlockTypeName = 'blockType' + radioCount + '[]';
			var radioBlockTypeState = 'blockState' + radioCount + '[]';
			var radioBlockWidthName = 'blockContentWidth' + radioCount + '[]'; 
			var radioImageLinkTarget = 'imageLinkTarget' + radioCount + '[]'; 
			var radioVideoLinkTarget = 'videoLinkTarget' + radioCount + '[]';
			var radioCustomblocksLink = 'customblocksLink' + radioCount + '[]';
			
			
			row.find('.block-type-radio').attr('name', radioBlockTypeName);
			row.find('.block-state-radio').attr('name', radioBlockTypeState);
			row.find('.block-width-radio').attr('name', radioBlockWidthName);
			row.find('.image-link-target-radio').attr('name', radioImageLinkTarget);
			row.find('.video-link-target-radio').attr('name', radioVideoLinkTarget);
			row.find('.custom-blocks-link-radio').attr('name', radioCustomblocksLink);
			
			row.find('.block-order').attr('value', radioCount);
			row.find('.color-field-clone').wpColorPicker();
			return false;
		});

		$('.remove-row').on('click', function() {
			$(this).parents('tr').remove();
			return false;
		});

		$('#repeatable-fieldset-one tbody').sortable({
			opacity: 0.6,
			revert: true,
			cursor: 'move',
			handle: '.sort',
			update: function( event, ui ) {
			}
		});

		/*--> NEW <--*/
		
		
        $('.color-field').wpColorPicker();


		$('.block-type-radio').on('click', function(e) {
			$this = $(this);
			var currentContainer = $this.closest('table');
			var currentOptionField = $this.closest('table').find('.block-type-fields');
			$(currentOptionField).hide();
			var currentid = $(this).attr('id');
			var parts = currentid.split('-');
			var type = parts.pop();
		 	var blockID = parts.join('-');
			//alert(blockID);
			currentContainer.find('#' + blockID).fadeIn(400);
			currentContainer.find('.block-settings-fields').fadeIn(400);
			var currentButton = currentContainer.find('.button-link');

			if(currentButton.hasClass('closed')){
				currentButton.removeClass('closed');
				currentButton.addClass('open');
				currentContainer.find('.project-block-field-containter').slideDown(400);
			    currentContainer.find('.block-settings-fields').slideDown(400);
			}
			$('.color-field').wpColorPicker();
			$this.prevAll(".block-counter").val('block-active'); //--> used to count active blocks for processing
		});
		

		$('button.custom-accordian').on('click', function(e) {
			$this = $(this);
			var currentContainer = $this.closest('table');
			if($this.hasClass('closed')) {
				$this.removeClass('closed');
				$this.addClass('open');
				$this[0].setAttribute('aria-expanded', 'true');
				var selectedSection = currentContainer.find('.blockTypeRadioContainer').find('input:checked').val();
				//alert(selectedSection);
				currentContainer.find('#block-type-' + selectedSection).fadeIn();
				currentContainer.find('.project-block-field-containter').slideDown(400);
				currentContainer.find('.block-settings-fields').slideDown(400);
			}else if($this.hasClass('open')){
				$this.removeClass('open');
				$this.addClass('closed');
				$this[0].setAttribute('aria-expanded', 'false');
				currentContainer.find('.project-block-field-containter').slideUp(400, function() {
				currentContainer.find('.block-type-fields').hide();
				});
			}else{

			}
		});
		
		
		$('.checkbox-trigger').change(function() {
			var targetID = $(this).attr("data-class-target");
	        if($(this).is(":checked")) {
	            $(this).siblings('.' + targetID).val("checked");
	        }else {
	        	$(this).siblings('.' + targetID).val("unchecked");
	        }       
	    });
	    
	    $('.dashicons-info').hover(
		  function() {
		    //$( this ).text( "hey" );
		  }, function() {
		    //$( this ).text( "ho" );
		  }
		);
	    
		
		//console.log('custom admin script added');
		
		/*
		 * Select/Upload image(s) event
		 */
		$('body').on('click', '.meta_field_upload_image_button', function(e){
			e.preventDefault();
 			console.log('block builder media event 3');
 			// add media filter
			var MediaLibraryUploadedFilter = wp.media.view.AttachmentFilters.extend({
				id: 'media-attachment-uploaded-filter',
		
				createFilters: function() {
		
					var filters = {};
		
					filters.all = {
						// Todo: String not strictly correct.
						//text:  wp.media.view.l10n.allMediaItems,
						text:  wp.media.view.l10n.uploadedToThisPost,
						props: {
							status:  null,
							type:    'image',
							//uploadedTo: null,
							uploadedTo: wp.media.view.settings.post.id,
							orderby: 'menuOrder',
							order:   'ASC'
						},
						priority: 10
					};
		
					filters.uploaded = {
						//text:  wp.media.view.l10n.uploadedToThisPost,
						text:  wp.media.view.l10n.allMediaItems,
						props: {
							status:  null,
							type:    null,
							//uploadedTo: wp.media.view.settings.post.id,
							uploadedTo: null,
							orderby: 'date',
							order:   'DESC'
						},
						priority: 20
					};
		
					filters.unattached = {
						text:  wp.media.view.l10n.unattached,
						props: {
							status:     null,
							uploadedTo: 0,
							type:       'image',
							orderby:    'menuOrder',
							order:      'ASC'
						},
						priority: 30
					};
		
					this.filters = filters;
				}
			});
		
			/**
			 * Extend and override wp.media.view.AttachmentsBrowser
			 * to include our new filter
			 */
			var AttachmentsBrowser = wp.media.view.AttachmentsBrowser;
			wp.media.view.AttachmentsBrowser = wp.media.view.AttachmentsBrowser.extend({
				createToolbar: function() {
		
					// Make sure to load the original toolbar
					AttachmentsBrowser.prototype.createToolbar.call( this );
		
					this.toolbar.set(
						'MediaLibraryUploadedFilter',
						new MediaLibraryUploadedFilter({
							controller: this.controller,
							model:      this.collection.props,
							priority:   -100
						})
						.render()
					);
				}
			});
			// custom media uploader
    	    custom_uploader = wp.media({
				title: 'Insert image',
				library : {
					// uncomment the next line if you want to attach image to the current post
					uploadedTo : wp.media.view.settings.post.id, 
					type : 'image',
					orderby: 'menuOrder', 
					order: 'ASC' 
				},
				button: {
					text: 'Use this image' // button label text
				},
				state: 'library',
				filters: 'all',
				multiple: false, // for multiple image selection set to true
				// frame: "post",
				// state: "insert"
			}).on('select', function() { // it also has "open" and "close" events 
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
				/* if you sen multiple to true, here is some code for getting the image IDs
				var attachments = frame.state().get('selection'),
				    attachment_ids = new Array(),
				    i = 0;
				attachments.each(function(attachment) {
	 				attachment_ids[i] = attachment['id'];
					console.log( attachment );
					i++;
				});
				*/
			})
				.close();
			});
 
				/*
		 * Remove image event   button
		 */
		$('body').on('click', '.meta_field_remove_image_button', function(){
			$(this).hide().prev().val('').prev().addClass('button').html('Upload image');
			return false;
		});

	});
	</script>

	<table id="repeatable-fieldset-one" width="100%">
	<thead>
		<tr>
			<th width="2%"></th>
			<th width="90%"></th>
			<th width="2%"></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$counter = 0;
	if ( $repeatable_project_fields ) :

		//var_dump($_POST);
		
			
		// $field['videoControls']
		// $field['videoAutoplay']
		// $field['videoSound']
		// $field['videoLoop']
				
				

	foreach ( $repeatable_project_fields as $key => $field ) {
	?>
	<tr class="<?php echo $field['blockState']; ?> block-builder-block">   <!-- rowspan="2" -->
		<td><a class="button remove-row" href="#">-</a></td>
		<td>
			<table class="block-inner-container">
				<tr style="background:#fff;">
					<td style="width:24%;">
						<h4 class="project-block-name">Block Name:</h4>
						<h4 class="project-block-type-title">Block Type:</h4>
					</td>  <!--<input type="text" class="widefat" name="name[]" value="<?php //if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /> -->
					<td style="width:75%;">
						<div>
							<input type="text" class="widefat" name="blockName[]" value="<?php if (array_key_exists('blockName', $field) && $field['blockName'] != '') echo esc_attr( $field['blockName'] ); ?>" />
						</div>
						<div class="blockTypeRadioContainer">
							<?php
								// if(isset($field['splitBackgroundType'])){
									// echo "set" . $field['splitBackgroundType'];
								// }else{
									// echo "Not set";
								// }								
							?>
							<input type="hidden" name="counter[]" class="block-counter" value="<?php if ($field['counter'] != '') echo esc_attr( $field['counter'] ); ?>" />
							<input type="hidden" name="blockOrder[]" class="block-order" value="<?php echo $key; ?>" />
							<input type="radio" name="blockType<?php echo $key; ?>[]" class="block-type-radio block-type-content-radio" id="block-type-content-radio" style="margin-left:12px;" value="content" <?php checked( $field['blockType'], 'content' ); ?> > Content 
							<input type="radio" name="blockType<?php echo $key; ?>[]" class="block-type-radio block-type-image-radio" id="block-type-image-radio" style="margin-left:12px;" value="image" <?php checked( $field['blockType'], 'image' ); ?> > Image 
				  			<input type="radio" name="blockType<?php echo $key; ?>[]" class="block-type-radio block-type-video-radio" id="block-type-video-radio" style="margin-left:12px;" value="video" <?php checked( $field['blockType'], 'video' ); ?> > Video
				  			<input type="radio" name="blockType<?php echo $key; ?>[]" class="block-type-radio block-type-customblocks-radio" id="block-type-customblocks-radio" style="margin-left:12px;" value="customblocks" <?php checked( $field['blockType'], 'customblocks' ); ?> > Custom Blocks
						</div>
					</td>
					<td style="width:10%;">
						<button type="button" class="button-link custom-accordian closed" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Project Block Builder</span><span class="toggle-indicator"></span></button>
					</td>
				</tr>
				<tr class="block-settings-fields block-type-fields">
					<td colspan="2">
						<div class="project-block-field-containter">
							<h4>Block Settings:</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block State:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="blockState<?php echo $key; ?>[]" class="block-state-radio block-state-active" id="block-state-active" value="blockActive" <?php checked( $field['blockState'], 'blockActive' ); ?> > Active 
									<input type="radio" name="blockState<?php echo $key; ?>[]" class="block-state-radio block-state-inactive" id="block-state-inactive" style="margin-left:12px;" value="blockInactive" <?php checked( $field['blockState'], 'blockInactive' ); ?> > Inactive 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Width:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="blockContentWidth<?php echo $key; ?>[]" class="block-width-radio block-type-fullwidth-radio" id="block-type-fullwidth-radio" value="fullwidth" <?php checked( $field['blockContentWidth'], 'fullwidth' ); ?> > Full Width 
									<input type="radio" name="blockContentWidth<?php echo $key; ?>[]" class="block-width-radio block-type-contain-radio" id="block-type-contain-radio" style="margin-left:12px;" value="contain" <?php checked( $field['blockContentWidth'], 'contain' ); ?> > Contain 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Padding:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="blockContentPadding[]" value="<?php if (array_key_exists('blockContentPadding', $field) && $field['blockContentPadding'] != '') echo esc_attr( $field['blockContentPadding'] ); ?>" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Background Color:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat color-field" name="blockContentBackgroundColor[]" value="<?php if (array_key_exists('blockContentBackgroundColor', $field) && $field['blockContentBackgroundColor'] != '') echo esc_attr( $field['blockContentBackgroundColor'] ); ?>" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Container Class:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="blockContentContainerClass[]" value="<?php if (array_key_exists('blockContentContainerClass', $field) && $field['blockContentContainerClass'] != '') echo esc_attr( $field['blockContentContainerClass'] ); ?>" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>	
					<td colspan="2" class="block-type-fields" id="block-type-content">
						<div class="project-block-field-containter">
							<h4>Content Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Content Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="contentBlockTitle[]" value="<?php if (array_key_exists('contentBlockTitle', $field) && $field['contentBlockTitle'] != '') echo esc_attr( $field['contentBlockTitle'] ); ?>" />
								</div>
							</div>  
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Content Shortcode:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="contentShortcode[]" value="<?php if (array_key_exists('contentShortcode', $field) && $field['contentShortcode'] != '') echo esc_attr( $field['contentShortcode'] ); ?>" />
								</div>
							</div>
							<div>
								<div>Content:</div><br /><!-- Slide Overlay Copy -->
								<textarea style="width:100%;" rows="10" placeholder="" name="contentBlockCopy[]" value="<?php if (array_key_exists('contentBlockCopy', $field) && $field['contentBlockCopy'] != '') echo esc_attr( $field['contentBlockCopy'] ); ?>"><?php if (array_key_exists('contentBlockCopy', $field) && $field['contentBlockCopy'] != '') echo esc_attr( $field['contentBlockCopy'] ); ?></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-image">
						<div class="project-block-field-containter">
							<h4>Image Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image -->
								<div style="float:left; width:32%;">Image:</div>
								<div style="float:left; width:68%;">
									<?php
									$image_size = 'full';
									$display = 'none';
									$content = '<a href="#" class="meta_field_upload_image_button button">Upload image</a>';
									if (array_key_exists('imageSlide', $field) && $field['imageSlide'] != '') {
										$attachmentID = esc_attr( $field['imageSlide'] ); 
										if( $image_attributes = wp_get_attachment_image_src( $attachmentID, $image_size ) ) {
											// $image_attributes[0] - image URL
											// $image_attributes[1] - image width
											// $image_attributes[2] - image height
											$content = '<a href="#" class="meta_field_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
											$display = 'inline-block';
										}
									}?>
									<div>
										<?php echo $content; ?>
										<input type="hidden" class="widefat" name="imageSlide[]" value="<?php if (array_key_exists('imageSlide', $field) && $field['imageSlide'] != '') echo esc_attr( $field['imageSlide'] ); ?>" />
										<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove Image</a> 
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Image Link:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="imageSlideLink[]" value="<?php if (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != '') echo esc_attr( $field['imageSlideLink'] ); ?>" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Target:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="imageLinkTarget<?php echo $key; ?>[]" class="image-link-target-radio image-link-target-same-window" id="image-link-target-same-window" value="sameWindow" <?php checked( $field['imageLinkTarget'], 'sameWindow' ); ?> > Same Window 
									<input type="radio" name="imageLinkTarget<?php echo $key; ?>[]" class="image-link-target-radio image-link-target-new-window" id="image-link-target-new-window" style="margin-left:12px;" value="newWindow" <?php checked( $field['imageLinkTarget'], 'newWindow' ); ?> > New Window 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Overlay Title -->
								<div style="float:left; width:32%;">Image Overlay Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="imageSlideOverlayTitle[]" value="<?php if (array_key_exists('imageSlideOverlayTitle', $field) && $field['imageSlideOverlayTitle'] != '') echo esc_attr( $field['imageSlideOverlayTitle'] ); ?>" />
								</div>
							</div>
							<div>
								<div>Image Overlay Copy:</div><br /><!-- Slide Overlay Copy -->
								<textarea style="width:100%;" rows="10" placeholder="" name="imageSlideOverlayCopy[]" value="<?php if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != '') echo esc_attr( $field['imageSlideOverlayCopy'] ); ?>"><?php if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != '') echo esc_attr( $field['imageSlideOverlayCopy'] ); ?></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-video">
						<div class="project-block-field-containter">
							<h4>Video Slide Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image (Poster)  -->
								<div style="float:left; width:32%;">Video Poster Image:</div>
								<div style="float:left; width:68%;">
									<?php
									$image_size = 'full';
									$display = 'none';
									$content = '<a href="#" class="meta_field_upload_image_button button">Upload Poster Image</a>';
									if (array_key_exists('videoSlidePosterImage', $field) && $field['videoSlidePosterImage'] != '') { 
										$attachmentID = esc_attr( $field['videoSlidePosterImage'] ); 
										if( $image_attributes = wp_get_attachment_image_src( $attachmentID, $image_size ) ) {
											// $image_attributes[0] - image URL
											// $image_attributes[1] - image width
											// $image_attributes[2] - image height
											$content = '<a href="#" class="meta_field_upload_image_button"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" /></a>';
											$display = 'inline-block';
										}
									}?>
									<div>
										<?php echo $content; ?>
										<input type="hidden" class="widefat" name="videoSlidePosterImage[]" value="<?php if (array_key_exists('videoSlidePosterImage', $field) && $field['videoSlidePosterImage'] != '') echo esc_attr( $field['videoSlidePosterImage'] ); ?>" />
										<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove image</a> 
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Video (mp4) Path - videoSlideSourceLink -->
								<div style="float:left; width:32%;">Video Path (mp4):</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideSourceLink[]" value="<?php if (array_key_exists('videoSlideSourceLink', $field) && $field['videoSlideSourceLink'] != '') echo esc_attr( $field['videoSlideSourceLink'] ); ?>" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link - videoSlideLink -->
								<div style="float:left; width:32%;">Video Link:</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideLink[]" value="<?php if (array_key_exists('videoSlideLink', $field) && $field['videoSlideLink'] != '') echo esc_attr( $field['videoSlideLink'] ); ?>" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Target:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="videoLinkTarget<?php echo $key; ?>[]" class="video-link-target-radio video-link-target-same-window" id="video-link-target-same-window" value="sameWindow" <?php checked( $field['videoLinkTarget'], 'sameWindow' ); ?> > Same Window 
									<input type="radio" name="videoLinkTarget<?php echo $key; ?>[]" class="video-link-target-radio video-link-target-new-window" id="video-link-target-new-window" style="margin-left:12px;" value="newWindow" <?php checked( $field['videoLinkTarget'], 'newWindow' ); ?> > New Window 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Overlay Title - videoOverlayTitle -->
								<div style="float:left; width:32%;">Video Overlay Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="videoSlideOverlayTitle[]" value="<?php if (array_key_exists('videoSlideOverlayTitle', $field) && $field['videoSlideOverlayTitle'] != '') echo esc_attr( $field['videoSlideOverlayTitle'] ); ?>" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Overlay Copy videoOverlayCopy -->
								<div>Video Overlay Copy:</div><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="videoSlideOverlayCopy[]" value="<?php if (array_key_exists('videoSlideOverlayCopy', $field) && $field['videoSlideOverlayCopy'] != '') echo esc_attr( $field['videoSlideOverlayCopy'] ); ?>"><?php if (array_key_exists('videoSlideOverlayCopy', $field) && $field['videoSlideOverlayCopy'] != '') echo esc_attr( $field['videoSlideOverlayCopy'] ); ?></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-customblocks">
						<div class="project-block-field-containter">
							<h4>Custom Blocks Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Post Types:</div>
								<div style="float:left; width:68%;">
									<div class="alignleft" style="margin-right:12px;">
									<?php
									$selectedPostType = '';
									$selectedPostType = (array_key_exists('customblocksType', $field) && $field['customblocksType'] != '') ? $field['customblocksType'] : 'Undefined Post Type';
									echo list_post_types($selectedPostType); 
									?>
									</div>
									<div class="alignleft">
										<label>Category</label> 
										<input type="text" name="customblocksCategory[]" style="width:148px; margin-left:6px" value="<?php if (array_key_exists('customblocksCategory', $field) && $field['customblocksCategory'] != '') echo esc_attr( $field['customblocksCategory'] ); ?>" />  
										<span class="dashicons dashicons-info" data-class-target="customblocksCategory"></span><div class="hidden">Use Category Slug. Leave blank to show all.</div>
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Display Options:</div>
								<div style="float:left; width:68%;">
									
									<?php //echo "FI :" . $field['customblocksShowFeaturedImage']; 
									if($field['customblocksShowFeaturedImage'] == "checked") {
										$featuredImageChecked = 'checked="checked"';
										$hiddenfeaturedImageChecked = 'checked';
									}else{
										$featuredImageChecked = '';
										$hiddenfeaturedImageChecked = 'unchecked';
									}
									if($field['customblocksShowTitle'] == "checked") {
										$titleChecked = 'checked="checked"';
										$hiddenTitleChecked = 'checked';
									}else{
										$titleChecked = '';
										$hiddenTitleChecked = 'unchecked';
									}
									if($field['customblocksShowDescription'] == "checked") {
										$descriptionChecked = 'checked="checked"';
										$hiddenDescriptionChecked = 'checked';
									}else{
										$descriptionChecked = '';
										$hiddenDescriptionChecked = 'unchecked';
									}
									?>
									<div class="alignleft" style="margin-right:10px; margin-bottom:5px;">
										<input type="hidden" value="<?php echo $hiddenfeaturedImageChecked; ?>" name='customblocksShowFeaturedImageHidden[]' class="customblocksShowFeaturedImageHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowFeaturedImageHidden" name="customblocksShowFeaturedImage[]" value="yes" <?php echo $featuredImageChecked; ?> />Featured Image
									</div>
									<div class="alignleft" style="margin-right:10px; margin-bottom:5px;">
										<input type="hidden" value="<?php echo $hiddenTitleChecked; ?>" name='customblocksShowTitleHidden[]' class="customblocksShowTitleHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowTitleHidden" name="customblocksShowTitle[]" value="yes" <?php echo $titleChecked; ?> />Show Title	
									</div>
									<div class="alignleft" style="margin-bottom:5px;">
										<input type="hidden" value="<?php echo $hiddenDescriptionChecked; ?>" name='customblocksShowDescriptionHidden[]' class="customblocksShowDescriptionHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowDescriptionHidden" name="customblocksShowDescription[]" value="yes" <?php echo $descriptionChecked; ?> />Description
									</div>
									<div style="margin:10px 0px; display:inline-block">
										<div class="alignleft" style="margin-right:10px;">
											<label>Character Count</label> 
											<input type="text" name="customblocksCharacterCount[]" style="width:48px; margin-left:6px" value="<?php if (array_key_exists('customblocksCharacterCount', $field) && $field['customblocksCharacterCount'] != '') echo esc_attr( $field['customblocksCharacterCount'] ); ?>" />  
											<span class="dashicons dashicons-info" data-class-target="customblocksCharacterCount"></span><div class="hidden">Leave blank to not limit.</div>
										</div>
										<div class="alignleft">
											<label>Number of Blocks</label> 
											<input type="text" name="customblocksBlockCount[]" style="width:48px; margin-left:6px" value="<?php if (array_key_exists('customblocksBlockCount', $field) && $field['customblocksBlockCount'] != '') echo esc_attr( $field['customblocksBlockCount'] ); ?>" />  
											<span class="dashicons dashicons-info" data-class-target="customblocksBlockCount"></span><div class="hidden">Leave blank to not limit.</div>
										</div>
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Blocks:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="customblocksLink<?php echo $key; ?>[]" class="custom-blocks-link-radio custom-blocks-link-yes" id="custom-blocks-link-yes" value="yes" <?php checked( $field['customblocksLink'], 'yes' ); ?> > Yes 
									<input type="radio" name="customblocksLink<?php echo $key; ?>[]" class="custom-blocks-link-radio custom-blocks-link-no" id="custom-blocks-link-no" style="margin-left:12px;" value="no" <?php checked( $field['customblocksLink'], 'no' ); ?> > No
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Custom Blocks Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="customblocksTitle[]" value="<?php if (array_key_exists('customblocksTitle', $field) && $field['customblocksTitle'] != '') echo esc_attr( $field['customblocksTitle'] ); ?>" />
								</div>
							</div>  
							<div>
								<div>Content:</div><br /><!-- Slide Overlay Copy -->
								<textarea style="width:100%;" rows="10" placeholder="" name="customblocksCopy[]" value="<?php if (array_key_exists('customblocksCopy', $field) && $field['contentBlockCopy'] != '') echo esc_attr( $field['customblocksCopy'] ); ?>"><?php if (array_key_exists('customblocksCopy', $field) && $field['customblocksCopy'] != '') echo esc_attr( $field['customblocksCopy'] ); ?></textarea>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td><a class="sort">|||</a></td>   <!-- rowspan="2" -->
	</tr>
	<?php
		}
	else :
		// show a blank one (on page load)
	?>
	<tr class="blockActive">
		<td><a class="button remove-row" href="#">-</a></td>
		<td>
			<table class="block-inner-container">
				<tr style="background:#fff;">
					<td style="width:24%;">
						<h4 class="project-block-name">Block Name:</h4>
						<h4 class="project-block-type-title">Block Type:</h4>
					</td>
					<td style="width:75%;">
						<div>
							<input type="text" class="widefat" name="blockName[]" />
						</div>
						<div class="blockTypeRadioContainer">
							<input type="hidden" name="counter[]" class="block-counter" value="inactive-block" />
				  			<input type="hidden" name="blockOrder[]" class="block-order" value="0" />
				  			<input type="radio" name="blockType0[]" class="block-type-radio block-type-content-radio" id="block-type-content-radio" value="content" style="margin-left:12px;"> Content
				  			<input type="radio" name="blockType0[]" class="block-type-radio block-type-image-radio" id="block-type-image-radio" value="image" style="margin-left:12px;"> Image
				  			<input type="radio" name="blockType0[]" class="block-type-radio block-type-video-radio" id="block-type-video-radio" value="video" style="margin-left:12px;"> Video
							<input type="radio" name="blockType0[]" class="block-type-radio block-type-customblocks-radio" id="block-type-customblocks-radio" value="customblocks" style="margin-left:12px;"> Custom Block
						</div>
					</td>
					<td style="width:10%;">
						<button type="button" class="button-link custom-accordian closed" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Project Block Builder</span><span class="toggle-indicator"></span></button>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="block-settings-fields block-type-fields">
						<div class="project-block-field-containter">
							<h4>Block Settings:</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block State:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="blockState0[]" class="block-state-radio block-state-active" id="block-state-active" value="blockActive" checked="checked"> Active
									<input type="radio" name="blockState0[]" class="block-state-radio block-state-inactive" id="block-state-inactive" value="blockInactive" style="margin-left:12px;"> Inactive 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Width:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="blockContentWidth0[]" class="block-width-radio block-type-fullwidth-radio" id="block-type-fullwidth-radio" value="fullwidth"> Full Width 
									<input type="radio" name="blockContentWidth0[]" class="block-width-radio block-type-contain-radio" id="block-type-contain-radio" style="margin-left:12px;" value="contain"> Contain 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Padding:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="blockContentPadding[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Background Color:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat color-field" name="blockContentBackgroundColor[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Container Class:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="blockContentContainerClass[]" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="block-type-fields" id="block-type-content">
						<div class="project-block-field-containter">
							<h4>Content Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Content Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="contentBlockTitle[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Content Shortcode:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="contentShortcode[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<span>Content:</span><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="contentBlockCopy[]"></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-image">
						<div class="project-block-field-containter">
							<h4>Image Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image -->
								<div style="float:left; width:32%;">Image:</div>
								<div style="float:left; width:68%;">
									<?php
									$content = '<a href="#" class="meta_field_upload_image_button button">Upload Image</a>';
									$display = 'none';
									?>
									<div>
										<?php echo $content; ?>
										<input type="hidden" class="widefat" name="imageSlide[]"  />
										<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove image</a> 
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Image Link:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="imageSlideLink[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Target:</div>
								<div style="float:left; width:68%;">
									<input type="radio" name="imageLinkTarget0[]" class="image-link-target-radio image-link-target-same-window" id="image-link-target-same-window" value="sameWindow" checked> Same Window 
									<input type="radio" name="imageLinkTarget0[]" class="image-link-target-radio image-link-target-new-window" id="image-link-target-new-window" style="margin-left:12px;" value="newWindow"> New Window 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Image Overlay Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="imageSlideOverlayTitle[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<span>Image Overlay Copy:</span><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="imageSlideOverlayCopy[]"></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-video">
						<div class="project-block-field-containter">
							<h4>Video Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image (Poster)  -->
								<div style="float:left; width:32%;">Video Poster Image:</div>
								<div style="float:left; width:68%;">
									<?php
									$content = '<a href="#" class="meta_field_upload_image_button button">Upload Poster Image</a>';
									$display = 'none';
									?>
									<div>
										<?php echo $content; ?>
										<input type="hidden" class="widefat" name="videoSlidePosterImage[]" value="" />
										<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove image</a> 
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Video Path (mp4):</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideSourceLink[]" value="" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Video Link:</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideLink[]" value="" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Target:</div>
								<div style="float:left; width:68%;">
									<input type="radio" name="videoLinkTarget0[]" class="video-link-target-radio video-link-target-same-window" id="video-link-target-same-window" value="sameWindow" checked> Same Window 
									<input type="radio" name="videoLinkTarget0[]" class="video-link-target-radio video-link-target-new-window" id="video-link-target-new-window" style="margin-left:12px;" value="newWindow"> New Window 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Video Overlay Title</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideOverlayTitle[]" value="" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div>Video Overlay Copy:</div><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="videoSlideOverlayCopy[]"></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-customblocks">
						<div class="project-block-field-containter">
							<h4>Custom Blocks Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Post Types:</div>
								<div style="float:left; width:68%;">
									<div class="alignleft" style="margin-right:12px;">
									<?php
									echo list_post_types(); 
									?>
									</div>
									<div class="alignleft">
										<label>Category</label> 
										<input type="text" name="customblocksCategory[]" style="width:148px; margin-left:6px" />  
										<span class="dashicons dashicons-info" data-class-target="customblocksCategory"></span><div class="hidden">Use Category Slug. Leave blank to show all.</div>
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Display Options:</div>
								<div style="float:left; width:68%;">
									<div class="alignleft" style="margin-right:10px; margin-bottom:5px;">
										<input type="hidden" value="checked" name='customblocksShowFeaturedImageHidden[]' class="customblocksShowFeaturedImageHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowFeaturedImageHidden" name="customblocksShowFeaturedImage[]" value="yes" checked="checked" />Featured Image
									</div>
									<div class="alignleft" style="margin-right:10px; margin-bottom:5px;">
										<input type="hidden" value="checked" name='customblocksShowTitleHidden[]' class="customblocksShowTitleHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowTitleHidden" name="customblocksShowTitle[]" value="yes" checked="checked" />Show Title	
									</div>
									<div class="alignleft" style="margin-bottom:5px;">
										<input type="hidden" value="checked" name='customblocksShowDescriptionHidden[]' class="customblocksShowDescriptionHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowDescriptionHidden" name="customblocksShowDescription[]" value="yes" checked="checked" />Description
									</div>
									<div style="margin:10px 0px; display:inline-block">
										<div class="alignleft" style="margin-right:10px;">
											<label>Character Count</label> 
											<input type="text" name="customblocksCharacterCount[]" value="120" style="width:48px; margin-left:6px" />  
											<span class="dashicons dashicons-info" data-class-target="customblocksCharacterCount"></span><div class="hidden">Leave blank to not limit.</div>
										</div>
										<div class="alignleft">
											<label>Number of Blocks</label> 
											<input type="text" name="customblocksBlockCount[]" value="6" style="width:48px; margin-left:6px" />  
											<span class="dashicons dashicons-info" data-class-target="customblocksBlockCount"></span><div class="hidden">Leave blank to not limit.</div>
										</div>
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Blocks:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="customblocksLink0[]" class="custom-blocks-link-radio custom-blocks-link-yes" id="custom-blocks-link-yes" value="yes" checked> Yes 
									<input type="radio" name="customblocksLink0[]" class="custom-blocks-link-radio custom-blocks-link-no" id="custom-blocks-link-no" style="margin-left:12px;" value="no"> No
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Custom Blocks Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="customblocksTitle[]" />
								</div>
							</div>  
							<div>
								<div>Content:</div><br /><!-- Slide Overlay Copy -->
								<textarea style="width:100%;" rows="10" placeholder="" name="customblocksCopy[]"></textarea>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td><a class="sort">|||</a></td>   <!-- rowspan="2" -->
	</tr>
	<?php endif; ?>

	<!-- empty hidden one for jQuery (for adding a new one) -->
	<tr class="blockActive empty-row screen-reader-text">
		<td><a class="button remove-row" href="#">-</a></td>
		<td>
			<table class="block-inner-container">
				<tr style="background:#fff;">
					<td style="width:24%;">
						<h4 class="project-block-name">Block Name:</h4>
						<h4 class="project-block-type-title">Block Type:</h4>
					</td>
					<td style="width:75%;">
						<div>
							<input type="text" class="widefat" name="blockName[]" />
						</div>
						<div class="blockTypeRadioContainer">
							<input type="hidden" name="counter[]" class="block-counter" value="inactive-block" />
				  			<input type="hidden" name="blockOrder[]" class="block-order" value="" />
				  			<input type="radio" name="blockType[]" class="block-type-radio block-type-content-radio" id="block-type-content-radio" value="content" style="margin-left:12px;"> Content
				  			<input type="radio" name="blockType[]" class="block-type-radio block-type-image-radio" id="block-type-image-radio" value="image" style="margin-left:12px;"> Image
				  			<input type="radio" name="blockType[]" class="block-type-radio block-type-video-radio" id="block-type-video-radio" value="video" style="margin-left:12px;"> Video
							<input type="radio" name="blockType[]" class="block-type-radio block-type-customblocks-radio" id="block-type-customblocks-radio" value="customblocks" style="margin-left:12px;"> Custom Blocks
						</div>
					</td>
					<td style="width:10%;">
						<button type="button" class="button-link custom-accordian closed" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Project Block Builder</span><span class="toggle-indicator"></span></button>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="block-settings-fields block-type-fields">
						<div class="project-block-field-containter">
							<h4>Block Settings:</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block State:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="blockState[]" class="block-state-radio block-state-active" id="block-state-active" value="blockActive" checked="checked"> Active
									<input type="radio" name="blockState[]" class="block-state-radio block-state-inactive" id="block-state-inactive" value="blockInactive" style="margin-left:12px;"> Inactive 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Width:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="blockContentWidth[]" class="block-width-radio block-type-fullwidth-radio" id="block-type-fullwidth-radio" value="fullwidth"> Full Width 
									<input type="radio" name="blockContentWidth[]" class="block-width-radio block-type-contain-radio" id="block-type-contain-radio" style="margin-left:12px;" value="contain"> Contain 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Padding:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="blockContentPadding[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Background Color:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat color-field-clone" name="blockContentBackgroundColor[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Block Container Class:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="blockContentContainerClass[]" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="block-type-fields" id="block-type-content">
						<div class="project-block-field-containter">
							<h4>Content Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Content Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="contentBlockTitle[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Content Shortcode:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="contentShortcode[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div>Content:</div><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="contentBlockCopy[]"></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-image">
						<div class="project-block-field-containter">
							<h4>Image Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image -->
								<div style="float:left; width:32%;">Image:</div>
								<div style="float:left; width:68%;">
									<?php
									$content = '<a href="#" class="meta_field_upload_image_button button">Upload image</a>';
									$display = 'none';
									?>
									<div>
										<?php echo $content; ?>
										<input type="hidden" class="widefat" name="imageSlide[]"  />
										<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove Image</a> 
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Image Link:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="imageSlideLink[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Target:</div>
								<div style="float:left; width:68%;">
									<input type="radio" name="imageLinkTarget[]" class="image-link-target-radio image-link-target-same-window" id="image-link-target-same-window" value="sameWindow" checked> Same Window 
									<input type="radio" name="imageLinkTarget[]" class="image-link-target-radio image-link-target-new-window" id="image-link-target-new-window" style="margin-left:12px;" value="newWindow"> New Window 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Image Overlay Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="imageSlideOverlayTitle[]" />
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div>Image Overlay Copy:</div><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="imageSlideOverlayCopy[]"></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-video">
						<div class="project-block-field-containter">
							<h4>Video Block Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image (Poster)  -->
								<div style="float:left; width:32%;">Video Poster Image:</div>
								<div style="float:left; width:68%;">
									<?php
									$content = '<a href="#" class="meta_field_upload_image_button button">Upload Poster Image</a>';
									$display = 'none';
									?>
									<div>
										<?php echo $content; ?>
										<input type="hidden" class="widefat" name="videoSlidePosterImage[]" />
										<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove image</a> 
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Video Path (mp4):</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideSourceLink[]" value="" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Video Link:</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideLink[]" value="" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Target:</div>
								<div style="float:left; width:68%;">
									<input type="radio" name="videoLinkTarget[]" class="video-link-target-radio video-link-target-same-window" id="video-link-target-same-window" value="sameWindow" checked> Same Window 
									<input type="radio" name="videoLinkTarget[]" class="video-link-target-radio video-link-target-new-window" id="video-link-target-new-window" style="margin-left:12px;" value="newWindow"> New Window 
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Video Overlay Title</div>
								<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideOverlayTitle[]" value="" /></div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div>Video Overlay Copy:</div><br />
								<textarea style="width:100%;" rows="10" placeholder="" name="videoSlideOverlayCopy[]"></textarea>
							</div>
						</div>
					</td>
					<td colspan="2" class="block-type-fields" id="block-type-customblocks">
						<div class="project-block-field-containter">
							<h4>Custom Blocks Fields</h4>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Post Types:</div>
								<div style="float:left; width:68%;">
									<div class="alignleft" style="margin-right:12px;">
									<?php
									echo list_post_types(); 
									?>
									</div>
									<div class="alignleft">
										<label>Category</label> 
										<input type="text" name="customblocksCategory[]" style="width:148px; margin-left:6px" />  
										<span class="dashicons dashicons-info" data-class-target="customblocksCategory"></span><div class="hidden">Use Category Slug. Leave blank to show all.</div>
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Display Options:</div>
								<div style="float:left; width:68%;">
									<div class="alignleft" style="margin-right:10px; margin-bottom:5px;">
										<input type="hidden" value="checked" name='customblocksShowFeaturedImageHidden[]' class="customblocksShowFeaturedImageHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowFeaturedImageHidden" name="customblocksShowFeaturedImage[]" value="yes" checked="checked" />Featured Image
									</div>
									<div class="alignleft" style="margin-right:10px; margin-bottom:5px;">
										<input type="hidden" value="checked" name='customblocksShowTitleHidden[]' class="customblocksShowTitleHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowTitleHidden" name="customblocksShowTitle[]" value="yes" checked="checked" />Show Title	
									</div>
									<div class="alignleft" style="margin-bottom:5px;">
										<input type="hidden" value="checked" name='customblocksShowDescriptionHidden[]' class="customblocksShowDescriptionHidden">
										<input type="checkbox" class="checkbox-trigger" data-class-target="customblocksShowDescriptionHidden" name="customblocksShowDescription[]" value="yes" checked="checked" />Description
									</div>
									<div style="margin:10px 0px; display:inline-block">
										<div class="alignleft" style="margin-right:10px;">
											<label>Character Count</label> 
											<input type="text" name="customblocksCharacterCount[]" value="120" style="width:48px; margin-left:6px" />  
											<span class="dashicons dashicons-info" data-class-target="customblocksCharacterCount"></span><div class="hidden">Leave blank to not limit.</div>
										</div>
										<div class="alignleft">
											<label>Number of Blocks</label> 
											<input type="text" name="customblocksBlockCount[]" value="6" style="width:48px; margin-left:6px" />  
											<span class="dashicons dashicons-info" data-class-target="customblocksBlockCount"></span><div class="hidden">Leave blank to not limit.</div>
										</div>
									</div>
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
								<div style="float:left; width:32%;">Link Blocks:</div>
								<div style="float:left; width:68%;">	<?php //echo $key; ?>
									<input type="radio" name="customblocksLink[]" class="custom-blocks-link-radio custom-blocks-link-yes" id="custom-blocks-link-yes" value="yes" checked> Yes 
									<input type="radio" name="customblocksLink[]" class="custom-blocks-link-radio custom-blocks-link-no" id="custom-blocks-link-no" style="margin-left:12px;" value="no"> No
								</div>
							</div>
							<div style="margin-bottom:12px; overflow:hidden;">
								<div style="float:left; width:32%;">Custom Blocks Title:</div>
								<div style="float:left; width:68%;">
									<input type="text" class="widefat" name="customblocksTitle[]" />
								</div>
							</div>  
							<div>
								<div>Content:</div><br /><!-- Slide Overlay Copy -->
								<textarea style="width:100%;" rows="10" placeholder="" name="customblocksCopy[]"></textarea>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td><a class="sort">|||</a></td>   <!-- rowspan="2" -->
	</tr>
	</tbody>
	</table>

	<p><a id="add-block" class="button" href="#">Add Another Block</a>
	<input type="submit" class="metabox_submit button button-primary button-large" value="Save" />
	</p>

	<?php
	 // $blockTypes = $field['blockName'];
	 // $ShowfeatureImage = $field['customblocksShowFeaturedImage'];
     // echo "Feature Image: " . $ShowfeatureImage;
	 // echo "Block Name: " . $blockTypes;
}



add_action('save_post', __NAMESPACE__ . '\\repeatable_meta_box_save');

function repeatable_meta_box_save($post_id) {
	//debug_to_console('SAVE meta box callback');
			
	if ( ! isset( $_POST['repeatable_meta_box_nonce'] ) ||
		! wp_verify_nonce( $_POST['repeatable_meta_box_nonce'], 'repeatable_meta_box_nonce' ) )
		return;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	if (!current_user_can('edit_post', $post_id))
		return;


	$old = get_post_meta($post_id, 'project-builder-meta-box-blocks', true);
	$new = array();


	//--> Block Types <--//
	$blocks = $_POST['counter'];
	$blocksOrder = $_POST['blockOrder'];
	$blockCount = count( $blocks );
	$activeBlockCount = 0;
	
	
	//--> Block Settings <--//
	$blockName = $_POST['blockName'];
	$blockNameCount = count($_POST['blockName']);
	$blockState = $_POST['blockState'];
	$contentSectionWidth = $_POST['blockContentWidth'];
	$blockContentPadding = $_POST['blockContentPadding'];
	$blockContentBackgroundColor = $_POST['blockContentBackgroundColor'];
	$blockContentContainerClass = $_POST['blockContentContainerClass'];
	
	//--> Content Section Fields <--//

	$contentBlockTitle = $_POST['contentBlockTitle'];
	$contentBlockCopy = $_POST['contentBlockCopy'];
	$contentShortcode = $_POST['contentShortcode'];
	// $field['contentBlockTitle'];
	// $field['contentBlockCopy'];
	
	//--> Image Section Fields <--//
	
	//  Slide Image (Poster) 
	
	$imageSlide = $_POST['imageSlide'];
	$imageSlideLink = $_POST['imageSlideLink'];
	$imageLinkTarget = $_POST['imageLinkTarget']; 
	$imageSlideOverlayTitle = $_POST['imageSlideOverlayTitle'];
	$imageSlideOverlayCopy = $_POST['imageSlideOverlayCopy'];
	
	// $field['imageSlide'];
	// $field['imageSlideLink'];
	// $field['imageSlideOverlayTitle'];
	// $field['imageSlideOverlayCopy'];

	//--> Video Section Fields <--//
	
	//  Slide Image (Poster)  
	$videoSlidePosterImage = $_POST['videoSlidePosterImage'];
	$videoSlideLink = $_POST['videoSlideLink'];
	$videoLinkTarget = $_POST['videoLinkTarget'];
	$videoSlideSourceLink = $_POST['videoSlideSourceLink'];
	$videoSlideOverlayTitle = $_POST['videoSlideOverlayTitle'];
	$videoSlideOverlayCopy = $_POST['videoSlideOverlayCopy'];
	// $field['videoSlidePosterImage'];
	// $field['videoSlideLink'];
	// $field['videoSlideSourceLink'];
	// $field['videoSlideOverlayTitle'];
	// $field['videoSlideOverlayCopy'];
	
	//--> Custom Block Section Fields <--//
	
	$customblocksType = $_POST['customblocksType'];
	$customblocksTitle = $_POST['customblocksTitle'];
	$customblocksCopy = $_POST['customblocksCopy'];
	
	//$customblocksShowFeaturedImage = print_r($_POST['customblocksShowFeaturedImage']);
	$customblocksShowFeaturedImage = $_POST['customblocksShowFeaturedImageHidden'];
	$customblocksShowTitle = $_POST['customblocksShowTitleHidden'];
	$customblocksShowDescription = $_POST['customblocksShowDescriptionHidden'];
	$customblocksCharacterCount = $_POST['customblocksCharacterCount'];
	$customblocksCategory = $_POST['customblocksCategory'];
	$customblocksBlockCount = $_POST['customblocksBlockCount'];
	$customblocksLink = $_POST['customblocksLink'];

	
	for ( $i = 0; $i < $blockCount; $i++ ) {
		if ( $blocks[$i] == 'block-active' ) {
			$activeBlockCount++;

			//--> Set Block Types <--//
			$new[$i]['counter'] = stripslashes( strip_tags( $blocks[$i] ) );
			$new[$i]['blockType'] = $_POST['blockType' . $blocksOrder[$i]][0]; //-->This fixed sortable issue

			
			//--> Set - Block Fields <--//
			if ( $_POST['blockContentWidth' . $blocksOrder[$i]][0] != '' )
				$new[$i]['blockContentWidth'] = $_POST['blockContentWidth' . $blocksOrder[$i]][0];
			
			if ( $_POST['blockState' . $blocksOrder[$i]][0] != '' )
				$new[$i]['blockState'] = $_POST['blockState' . $blocksOrder[$i]][0];
			
			//$blockName = $_POST['blockName'];
			if ( $blockName[$i] != '' )
				$new[$i]['blockName'] = stripslashes($blockName[$i] );
			
			if ( $blockContentBackgroundColor[$i] != '' )
				$new[$i]['blockContentBackgroundColor'] = stripslashes($blockContentBackgroundColor[$i] );
			
			if ( $blockContentPadding[$i] != '' )
				$new[$i]['blockContentPadding'] = stripslashes($blockContentPadding[$i] );
			
			if ( $blockContentContainerClass[$i] != '' )
				$new[$i]['blockContentContainerClass'] = stripslashes($blockContentContainerClass[$i] );


			//--> Set - Content Section Fields <--//
			if ( $contentBlockTitle[$i] != '' )
				$new[$i]['contentBlockTitle'] = stripslashes($contentBlockTitle[$i] );
			
			if ( $contentShortcode[$i] != '' )
				$new[$i]['contentShortcode'] = stripslashes($contentShortcode[$i] );
			
			if ( $contentBlockCopy[$i] != '' )
				$new[$i]['contentBlockCopy'] = stripslashes($contentBlockCopy[$i] );
				
				
			//--> Set - Image Section Fields <--//
			if ( $imageSlide[$i] != '' )
				$new[$i]['imageSlide'] = stripslashes($imageSlide[$i] );
			
			if ( $imageSlideLink[$i] != '' )
				$new[$i]['imageSlideLink'] = stripslashes($imageSlideLink[$i] );
			
			if ( $_POST['imageLinkTarget' . $blocksOrder[$i]][0] != '' )
				$new[$i]['imageLinkTarget'] = $_POST['imageLinkTarget' . $blocksOrder[$i]][0];
			
			if ( $imageSlideOverlayTitle[$i] != '' )
				$new[$i]['imageSlideOverlayTitle'] = stripslashes($imageSlideOverlayTitle[$i] );

			if ( $imageSlideOverlayCopy[$i] != '' )
				$new[$i]['imageSlideOverlayCopy'] = stripslashes( $imageSlideOverlayCopy[$i] );


			//--> Set - Video Section Fields <--//
			if ( $videoSlidePosterImage[$i] != '' )
				$new[$i]['videoSlidePosterImage'] = stripslashes($videoSlidePosterImage[$i] );

			if ( $videoSlideLink[$i] != '' )
				$new[$i]['videoSlideLink'] = stripslashes(  $videoSlideLink[$i] );
				
			if ( $_POST['videoLinkTarget' . $blocksOrder[$i]][0] != '' )
				$new[$i]['videoLinkTarget'] = $_POST['videoLinkTarget' . $blocksOrder[$i]][0];

			if ( $videoSlideSourceLink[$i] != '' )
				$new[$i]['videoSlideSourceLink'] = stripslashes( $videoSlideSourceLink[$i] );

			if ( $videoSlideOverlayTitle[$i] != '' )
				$new[$i]['videoSlideOverlayTitle'] = stripslashes( strip_tags( $videoSlideOverlayTitle[$i] ) );

			if ( $videoSlideOverlayCopy[$i] != '' )
				$new[$i]['videoSlideOverlayCopy'] = stripslashes( $videoSlideOverlayCopy[$i] );

			//--> Set - Custom Block Section Fields <--//
			if ( $customblocksType[$i] != '' )
				$new[$i]['customblocksType'] = stripslashes($customblocksType[$i] );

			if ( $customblocksTitle[$i] != '' )
				$new[$i]['customblocksTitle'] = stripslashes(  $customblocksTitle[$i] );
			
			if ( $customblocksCopy[$i] != '' )
				$new[$i]['customblocksCopy'] = stripslashes(  $customblocksCopy[$i] );
				
			if ( $customblocksShowFeaturedImage[$i] != '' )
			$new[$i]['customblocksShowFeaturedImage'] = stripslashes(  $customblocksShowFeaturedImage[$i] );
				
			if ($customblocksShowTitle[$i] != '') 
				$new[$i]['customblocksShowTitle'] = stripslashes( $customblocksShowTitle[$i] ); 
			
			if ($customblocksShowDescription[$i] != '') 
				$new[$i]['customblocksShowDescription'] = stripslashes( $customblocksShowDescription[$i] );
			
			if ($customblocksCharacterCount[$i] != '') 
				$new[$i]['customblocksCharacterCount'] = stripslashes( $customblocksCharacterCount[$i] );
			
			if ($customblocksCategory[$i] != '') 
				$new[$i]['customblocksCategory'] = stripslashes( $customblocksCategory[$i] );
			
			if ($customblocksBlockCount[$i] != '') 
				$new[$i]['customblocksBlockCount'] = stripslashes( $customblocksBlockCount[$i] );
			
			if ( $_POST['customblocksLink' . $blocksOrder[$i]][0] != '' )
				$new[$i]['customblocksLink'] = $_POST['customblocksLink' . $blocksOrder[$i]][0];
			
		}else {
			$blocks[$i] = 'inactive-block';
		}
	}

	for ( $i = 0; $i < $blockCount; $i++ ) {
		if (isset($blockTypes ) ? sanitize_html_class( $blockTypes ) : '') :
			// used for testing
		endif;
	}

	if ( !empty( $new ) && $new != $old )
		update_post_meta( $post_id, 'project-builder-meta-box-blocks', $new );
	elseif ( empty($new) && $old )
		delete_post_meta( $post_id, 'project-builder-meta-box-blocks', $old );
}


//////------>> END - ADD CUSTOM LOCATION META BOXES TO FOR PROJECT BUILDER CUSTOM POST TYPE <<------//////
