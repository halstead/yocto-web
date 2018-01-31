<?php
/**
 * Must-Use Slider Plugin
 * 
 * A Custom Class createing a Bootrap Touch Slider for Wordpress
 * 
 * @package WordPress
 * @subpackage BASEKIT
 */
class BASEKIT_Function_Slider {
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'define_constants' ), 1 );
		add_action( 'init', array( $this, 'add_slider_post_type' ) );
		//add_action( 'init', array( $this, 'add_slider_options' ) ); add slider options tab (coming later *to-do*)
		add_action( 'init', array( $this, 'add_slider_filters' ) );
		
		add_action( 'init', array( $this, 'add_slider_meta_fileds' ) );
		add_action( 'init', array( $this, 'display_slider_fucntions' ) );

	}
	
	
	public function define_constants() {
		
	}
	

	public function add_slider_post_type() {
		$this->setup_post_type( array( 'Slider', 'Sliders', 'slider', 'sliders' ), array() );
		//$this->setup_post_type(array-of-post-type-info, array-of-items-that-overwrite-default-args)
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
				'rewrite'             => $rewrite,
				'capability_type'     => 'post',
				'hierarchical'        => false,
				'menu_position'       => null, //'5',
				'has_archive'         => true,
				'exclude_from_search' => false,
				'supports'			  => array( 'title','thumbnail','editor'), //,'editor'  ,'excerpt','custom-fields'
				'taxonomies'          => array(),
				'menu_icon' 		  => 'dashicons-slides'
			));
			register_post_type( $key, $args );	
		}


	public function add_slider_filters() { // shows id's in admin
		add_filter( 'manage_posts_columns', 'revealid_add_id_column' );
		add_action( 'manage_posts_custom_column', 'revealid_id_column_content', 10, 2);
		add_filter( 'manage_sliders_posts_columns', 'revealid_add_id_column' );
		add_action( 'manage_sliders_posts_custom_column', 'revealid_id_column_content', 10, 2);
		 // add_filter( 'manage_posts_columns', __NAMESPACE__ . '\\revealid_add_id_column' );
		 // add_action( 'manage_posts_custom_column', __NAMESPACE__ . '\\revealid_id_column_content', 10, 2);
		 // add_filter( 'manage_sliders_posts_columns', __NAMESPACE__ . '\\revealid_add_id_column' );
		 // add_action( 'manage_sliders_posts_custom_column', __NAMESPACE__ . '\\revealid_id_column_content', 10, 2);
		function revealid_add_id_column( $columns ) {
		   
		   $columns['revealid_id'] = 'ID';
		   return $columns;
		}
		
		function revealid_id_column_content( $column, $id ) {
			global $post;
		  if( 'revealid_id' == $column ) {
		    echo $id;
		  }
		}
	}
	
	//////------>> START - ADD CUSTOM LOCATION META BOXES TO PROJECT BUILDER FIELD <<------//////
	public function add_slider_meta_fileds() {

		//add_action( 'add_meta_boxes', __NAMESPACE__ . '\\project_builder_meta_box_add' ); // use when in theme (sage)
		add_action( 'add_meta_boxes', 'project_builder_meta_box_add' );
		
		function project_builder_meta_box_add() {
			//add_meta_box( 'slider-builder-meta-box-blocks', 'Project Block Builder', __NAMESPACE__ . '\\project_builder_meta_box', 'slider', 'normal', 'high' );  // use when in theme (sage)
			add_meta_box( 'slider-builder-meta-box-blocks', 'Slider Builder', 'slider_builder_meta_box', 'slider', 'normal', 'high' );
		}
		
		
		function slider_builder_meta_box() {
			//debug_to_console('meta box callback');
		    // $post is already set, and contains an object: the WordPress post
		    global $post;
			
			//slider-builder-meta-box-blocks
		
			$repeatable_project_fields = get_post_meta(get_the_ID(), 'slider-builder-meta-box-blocks', true);
		    // We'll use this nonce field later on when saving.
		    wp_nonce_field( 'repeatable_meta_box_nonce', 'repeatable_meta_box_nonce' );
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
			</style>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
		
				var sortableLenth = $('.blockTypeRadioContainer').length;
		
				$('.metabox_submit').click(function(e) {
					e.preventDefault();
					$('#publish').click();
				});
		
				$('#add-block').on('click', function() {
					var row = $('.empty-row.screen-reader-text').clone(true);
					row.removeClass('empty-row screen-reader-text');
					row.insertBefore('#repeatable-fieldset-one tbody:first>tr:last');
					var radioCount = $('input[name*="counter"]').length - 2; //--> There are two hidden ones we don't want to count
					var radioName = 'blockType' + radioCount + '[]';
					row.find('.block-type-radio').attr('name', radioName);
					row.find('.block-order').attr('value', radioCount);
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
					var currentButton = currentContainer.find('.button-link');
		
					if(currentButton.hasClass('closed')){
						currentButton.removeClass('closed');
						currentButton.addClass('open');
						currentContainer.find('.project-block-field-containter').slideDown(400);
					}
		
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
						currentContainer.find('#block-type-' + selectedSection).fadeIn();
						currentContainer.find('.project-block-field-containter').slideDown(400);
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
				
				//console.log('custom admin script added');
				
				/*
				 * Select/Upload image(s) event
				 */
				$('body').on('click', '.meta_field_upload_image_button', function(e){
					e.preventDefault();
					console.log('custom slider media event');
 
			    		var button = $(this),
			    		    custom_uploader = wp.media({
						title: 'Insert image',
						library : {
							// uncomment the next line if you want to attach image to the current post
							uploadedTo : wp.media.view.settings.post.id, 
							type : 'image'
						},
						button: {
							text: 'Use this image' // button label text
						},
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
			<h5 style="display:inline-block">Slider Shortcode: </h5> <input type="text" style="width:200px;" value="[custom_slider id='<?php  echo get_the_ID(); ?>']" />
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
		
				foreach ( $repeatable_project_fields as $key => $field ) {
			?>
			<tr style="background-color: #f1f1f1; padding:5px; box-sizing:border-box;">   <!-- rowspan="2" -->
				<td><a class="button remove-row" href="#">-</a></td>
				<td>
					<table style="width:100%;">
						<tr style="background:#fff;">
							<td style="width:24%;"><h4 class="project-block-type-title">Slide Type:</h4></td>  <!--<input type="text" class="widefat" name="name[]" value="<?php //if($field['name'] != '') echo esc_attr( $field['name'] ); ?>" /> -->
							<td style="width:75%;">
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
										<input type="radio" name="blockType<?php echo $key; ?>[]" class="block-type-radio block-type-image-radio" id="block-type-image-radio" style="margin-left:12px;" value="image" <?php checked( $field['blockType'], 'image' ); ?> > Image 
							  			<input type="radio" name="blockType<?php echo $key; ?>[]" class="block-type-radio block-type-video-radio" id="block-type-video-radio" style="margin-left:12px;" value="video" <?php checked( $field['blockType'], 'video' ); ?> > Video
								</div>
								<button type="button" class="button-link custom-accordian closed" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Project Block Builder</span><span class="toggle-indicator"></span></button>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="block-type-fields" id="block-type-image">
								<div class="project-block-field-containter">
									<h4>Image Slide Fields</h4>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image -->
										<div style="float:left; width:32%;">Slide Image:</div>
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
												<a href="#" class="meta_field_remove_image_button button" style="display:<?php echo $display; ?>; margin-top:14px;">Remove image</a> 
											</div>
										</div>
									</div>
									
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link -->
										<div style="float:left; width:32%;">Slide Link:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="imageSlideLink[]" value="<?php if (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != '') echo esc_attr( $field['imageSlideLink'] ); ?>" />
										</div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Overlay Title -->
										<div style="float:left; width:32%;">Slide Overlay Title:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="imageSlideOverlayTitle[]" value="<?php if (array_key_exists('imageSlideOverlayTitle', $field) && $field['imageSlideOverlayTitle'] != '') echo esc_attr( $field['imageSlideOverlayTitle'] ); ?>" />
										</div>
									</div>
									<div>
										<div>Slide Overlay Copy:</div><br /><!-- Slide Overlay Copy -->
										<textarea style="width:100%;" rows="10" placeholder="" name="imageSlideOverlayCopy[]" value="<?php if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != '') echo esc_attr( $field['imageSlideOverlayCopy'] ); ?>"><?php if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != '') echo esc_attr( $field['imageSlideOverlayCopy'] ); ?></textarea>
									</div>
								</div>
							</td>
							<td colspan="2" class="block-type-fields" id="block-type-video">
								<div class="project-block-field-containter">
									<h4>Video Slide Fields</h4>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image (Poster)  -->
										<div style="float:left; width:32%;">Slide Video Poster Image:</div>
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
										<div style="float:left; width:32%;">SlideVideo Link (mp4):</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideSourceLink[]" value="<?php if (array_key_exists('videoSlideSourceLink', $field) && $field['videoSlideSourceLink'] != '') echo esc_attr( $field['videoSlideSourceLink'] ); ?>" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Link - videoSlideLink -->
										<div style="float:left; width:32%;">Slide Link:</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideLink[]" value="<?php if (array_key_exists('videoSlideLink', $field) && $field['videoSlideLink'] != '') echo esc_attr( $field['videoSlideLink'] ); ?>" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Overlay Title - videoOverlayTitle -->
										<div style="float:left; width:32%;">Slide Overlay Title:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="videoSlideOverlayTitle[]" value="<?php if (array_key_exists('videoSlideOverlayTitle', $field) && $field['videoSlideOverlayTitle'] != '') echo esc_attr( $field['videoSlideOverlayTitle'] ); ?>" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Overlay Copy videoOverlayCopy -->
										<div>Slide Overlay Copy:</div><br />
										<textarea style="width:100%;" rows="10" placeholder="" name="videoSlideOverlayCopy[]" value="<?php if (array_key_exists('videoSlideOverlayCopy', $field) && $field['videoSlideOverlayCopy'] != '') echo esc_attr( $field['videoSlideOverlayCopy'] ); ?>"><?php if (array_key_exists('videoSlideOverlayCopy', $field) && $field['videoSlideOverlayCopy'] != '') echo esc_attr( $field['videoSlideOverlayCopy'] ); ?></textarea>
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
				// show a blank one
			?>
			<tr style="background-color: #f1f1f1; padding:5px; box-sizing:border-box;">
				<td><a class="button remove-row" href="#">-</a></td>
				<td>
					<table style="width:100%;">
						<tr style="background:#fff;">
							<td style="width:24%;"><h4 class="project-block-type-title">Slide Type:</h4></td>
							<td style="width:75%;">
								<div class="blockTypeRadioContainer">
										<input type="hidden" name="counter[]" class="block-counter" value="inactive-block" />
							  			<input type="hidden" name="blockOrder[]" class="block-order" value="0" />
							  			<input type="radio" name="blockType0[]" class="block-type-radio block-type-image-radio" id="block-type-image-radio" value="image" style="margin-left:12px;"> Image
							  			<input type="radio" name="blockType0[]" class="block-type-radio block-type-video-radio" id="block-type-video-radio" value="video" style="margin-left:12px;"> Video
								</div>
								<button type="button" class="button-link custom-accordian closed" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Project Block Builder</span><span class="toggle-indicator"></span></button>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="block-type-fields" id="block-type-image">
								<div class="project-block-field-containter">
									<h4>Image Slide Fields</h4>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image -->
										<div style="float:left; width:32%;">Slide Image:</div>
										<div style="float:left; width:68%;">
											<?php
											$content = '<a href="#" class="meta_field_upload_image_button button">Upload image</a>';
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
										<div style="float:left; width:32%;">Slide Link:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="imageSlideLink[]" />
										</div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div style="float:left; width:32%;">Slide Overlay Title:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="imageSlideOverlayTitle[]" />
										</div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<span>Slide Overlay Copy:</span><br />
										<textarea style="width:100%;" rows="10" placeholder="" name="imageSlideOverlayCopy[]"></textarea>
									</div>
								</div>
							</td>
							<td colspan="2" class="block-type-fields" id="block-type-video">
								<div class="project-block-field-containter">
									<h4>Video Slide Fields</h4>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image (Poster)  -->
										<div style="float:left; width:32%;">Slide Video Poster Image:</div>
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
										<div style="float:left; width:32%;">Slide Video Link (mp4):</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideSourceLink[]" value="" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div style="float:left; width:32%;">Slide Link:</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideLink[]" value="" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div style="float:left; width:32%;">Slide Overlay Title</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideOverlayTitle[]" value="" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div>Slide Overlay Copy:</div><br />
										<textarea style="width:100%;" rows="10" placeholder="" name="videoSlideOverlayCopy[]"></textarea>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</td>
				<td><a class="sort">|||</a></td>   <!-- rowspan="2" -->
			</tr>
			<?php endif; ?>
		
			<!-- empty hidden one for jQuery -->
			<tr class="empty-row screen-reader-text" style="background-color: #f1f1f1; padding:5px; box-sizing:border-box;">
				<td><a class="button remove-row" href="#">-</a></td>
				<td>
					<table style="width:100%;">
						<tr style="background:#fff;">
							<td style="width:24%;"><h4 class="project-block-type-title">Slide Type:</h4></td>
							<td style="width:75%;">
								<div class="blockTypeRadioContainer">
										<input type="hidden" name="counter[]" class="block-counter" value="inactive-block" />
							  			<input type="hidden" name="blockOrder[]" class="block-order" value="" />
							  			<input type="radio" name="blockType[]" class="block-type-radio block-type-image-radio" id="block-type-image-radio" value="image" style="margin-left:12px;"> Image
							  			<input type="radio" name="blockType[]" class="block-type-radio block-type-video-radio" id="block-type-video-radio" value="video" style="margin-left:12px;"> Video
								</div>
								<button type="button" class="button-link custom-accordian closed" aria-expanded="false"><span class="screen-reader-text">Toggle panel: Project Block Builder</span><span class="toggle-indicator"></span></button>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="block-type-fields" id="block-type-image">
								<div class="project-block-field-containter">
									<h4>Image Slide Fields</h4>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image -->
										<div style="float:left; width:32%;">Slide Image:</div>
										<div style="float:left; width:68%;">
											<?php
											$content = '<a href="#" class="meta_field_upload_image_button button">Upload image</a>';
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
										<div style="float:left; width:32%;">Slide Link:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="imageSlideLink[]" />
										</div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div style="float:left; width:32%;">Slide Overlay Title:</div>
										<div style="float:left; width:68%;">
											<input type="text" class="widefat" name="imageSlideOverlayTitle[]" />
										</div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div>Slide Overlay Copy:</div><br />
										<textarea style="width:100%;" rows="10" placeholder="" name="imageSlideOverlayCopy[]"></textarea>
									</div>
								</div>
							</td>
							<td colspan="2" class="block-type-fields" id="block-type-video">
								<div class="project-block-field-containter">
									<h4>Video Slide Fields</h4>
									<div style="margin-bottom:12px; overflow:hidden;"><!-- Slide Image (Poster)  -->
										<div style="float:left; width:32%;">Slide Video Poster Image:</div>
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
										<div style="float:left; width:32%;">Slide Video Link (mp4):</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideSourceLink[]" value="" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div style="float:left; width:32%;">Slide Link (link to page/url):</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideLink[]" value="" /></div>
									</div>
									
									<div style="margin-bottom:12px; overflow:hidden;">
										<div style="float:left; width:32%;">Slide Overlay Title</div>
										<div style="float:left; width:68%;"><input type="text" class="widefat" name="videoSlideOverlayTitle[]" value="" /></div>
									</div>
									<div style="margin-bottom:12px; overflow:hidden;">
										<div>Slide Overlay Copy:</div><br />
										<textarea style="width:100%;" rows="10" placeholder="" name="videoSlideOverlayCopy[]"></textarea>
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
		
			<p><a id="add-block" class="button" href="#">Add Another Slide</a>
			<input type="submit" class="metabox_submit button button-primary button-large" value="Save" />
			</p>
		
			<?php
		    // $blockTypes = $_POST['blockType'];
			// $blockTypesCount = count($blockTypes);
			//echo "Count: " . $counter;
		}
		
		//add_action('save_post', __NAMESPACE__ . '\\repeatable_meta_box_save'); // use when in theme (sage)
		add_action('save_post', 'repeatable_meta_box_save');
		
		function repeatable_meta_box_save($post_id) {
			//debug_to_console('SAVE meta box callback');
			
			if ( ! isset( $_POST['repeatable_meta_box_nonce'] ) ||
				! wp_verify_nonce( $_POST['repeatable_meta_box_nonce'], 'repeatable_meta_box_nonce' ) )
				return;
		
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return;
		
			if (!current_user_can('edit_post', $post_id))
				return;
		
		
			$old = get_post_meta($post_id, 'slider-builder-meta-box-blocks', true);
			$new = array();
		
		
			//--> Block Types <--//
			$blocks = $_POST['counter'];
			$blocksOrder = $_POST['blockOrder'];
			$blockCount = count( $blocks );
			$activeBlockCount = 0;
		
			//--> Image Section Fields <--//
			
			//  Slide Image (Poster) 
			
	  		$imageSlide = $_POST['imageSlide'];
			$imageSlideLink = $_POST['imageSlideLink'];
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
			$videoSlideSourceLink = $_POST['videoSlideSourceLink'];
			$videoSlideOverlayTitle = $_POST['videoSlideOverlayTitle'];
			$videoSlideOverlayCopy = $_POST['videoSlideOverlayCopy'];
			// $field['videoSlidePosterImage'];
			// $field['videoSlideLink'];
			// $field['videoSlideSourceLink'];
			// $field['videoSlideOverlayTitle'];
			// $field['videoSlideOverlayCopy'];
		
			
			for ( $i = 0; $i < $blockCount; $i++ ) {
				if ( $blocks[$i] == 'block-active' ) {
					$activeBlockCount++;
		
					//--> Set Block Types <--//
					$new[$i]['counter'] = stripslashes( strip_tags( $blocks[$i] ) );
					$new[$i]['blockType'] = $_POST['blockType' . $blocksOrder[$i]][0]; //-->This fixed sortable issue
		
					//--> Set - Image Section Fields <--//
					if ( $imageSlide[$i] != '' )
						$new[$i]['imageSlide'] = stripslashes($imageSlide[$i] );
					
					if ( $imageSlideLink[$i] != '' )
						$new[$i]['imageSlideLink'] = stripslashes($imageSlideLink[$i] );
		
					if ( $imageSlideOverlayTitle[$i] != '' )
						$new[$i]['imageSlideOverlayTitle'] = stripslashes($imageSlideOverlayTitle[$i] );
		
					if ( $imageSlideOverlayCopy[$i] != '' )
						$new[$i]['imageSlideOverlayCopy'] = stripslashes(  $imageSlideOverlayCopy[$i] );

		
					//--> Set - Video Section Fields <--//
					if ( $videoSlidePosterImage[$i] != '' )
						$new[$i]['videoSlidePosterImage'] = stripslashes($videoSlidePosterImage[$i] );

					if ( $videoSlideLink[$i] != '' )
						$new[$i]['videoSlideLink'] = stripslashes(  $videoSlideLink[$i]  );
		
					if ( $videoSlideSourceLink[$i] != '' )
						$new[$i]['videoSlideSourceLink'] = stripslashes( $videoSlideSourceLink[$i]  );
		
					if ( $videoSlideOverlayTitle[$i] != '' )
						$new[$i]['videoSlideOverlayTitle'] = stripslashes( $videoSlideOverlayTitle[$i] ); //stripslashes( strip_tags( $videoSlideOverlayTitle[$i] ) );
		
					if ( $videoSlideOverlayCopy[$i] != '' )
						$new[$i]['videoSlideOverlayCopy'] = stripslashes( $videoSlideOverlayCopy[$i] );

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
				update_post_meta( $post_id, 'slider-builder-meta-box-blocks', $new );
			elseif ( empty($new) && $old )
				delete_post_meta( $post_id, 'slider-builder-meta-box-blocks', $old );
		}
     }
     //////------>> END - ADD CUSTOM LOCATION META BOXES TO FOR SLIDER <<------//////
	
	
	 //////------>> START - DISPLAY SLIDER FUNCTION <<------//////
	 public function display_slider_fucntions() {
	 	
		
		function get_custom_excerpt($limit, $source = null){ // Custom Excerpt function by character count
		    if($source == "content" ? ($excerpt = get_the_content()) : ($excerpt = get_the_excerpt()));
			    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
			    $excerpt = strip_shortcodes($excerpt);
			    //$excerpt = strip_tags($excerpt);
			    if(strlen($excerpt) > $limit){
			    	$excerpt = substr($excerpt, 0, $limit);
					$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
			    	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
			    	//$excerpt = $excerpt.'... <a href="'.get_permalink($post->ID).'">more</a>';
					$excerpt = $excerpt . '...';
				}
		    return $excerpt;
		}
		
		function default_slider_job_block() {
			// Jobs Custom Options
			$post_type = 'jobs';
			$jobs_options = get_option('options_jobs_field_group');
			$jobs_expire_date = $jobs_options['jobs_expire_setting'];
			$jobs_minimum_blocks = $jobs_options['jobs_minimum_blocks'];
			$jobs_default_ID = $jobs_options['jobs_default_postID'];
			$obj = get_post_type_object( $post_type );
			$post_type_singular_name =  $obj->labels->singular_name;
			
			$args = '';
			$args=array(
		      'post_type' => $post_type,
		      'post_status' => 'publish',
		      'posts_per_page' => 1, //$block_num
		      'order' => 'DESC',
		      'post__in' => array($jobs_default_ID), 
		    );	
			
			$output = '';
			$my_query = null;
		    $my_query = new WP_Query($args); 
			if( $my_query->have_posts() ) {
				while ($my_query->have_posts()) : $my_query->the_post(); 
					$excerpt = get_custom_excerpt(85);
					$cityField = get_field('my_meta_box_city_text');
					$stateField = get_field('my_meta_box_state_select');
					$counteryField = get_field('my_meta_box_country_select');
					$state = convertState($stateField, $strFormat='name');
					$country = convertCountry($counteryField);
					$output .= '<div class="item">';
					$output .= '  	<div class="item-content" style="overflow:hidden;">';
					$output .= '		<h5>' . get_the_title() . '</h5>';
					$output .= '		<p>' . $excerpt . '</p>'; //get_the_excerpt() 
					if(get_field('dsp_job_posting_link') ){
						$output .= '				<div class="widgete-link"><a href="' .  get_field('dsp_job_posting_link') . '" class="blue-link" target="_blank">View Job Details</a></div>';
					}
					$output .= '		<span class="tag tag-blue">' . $post_type_singular_name . '</span>';
					$output .= '	</div>';
					$output .= '</div>';	
				endwhile;
			}
			wp_reset_postdata();
			wp_reset_query();
			return $output;
		}
		
	 	
		function custom_post_slider_function($atts) {
			$local_atts = shortcode_atts( array(
				'post_count' => -1,
		        'post_type' => 'post',
		        'taxonomy' => 'category',
		        'terms' => 'uncategorized',
		        'expire_date_type' => '',
		        'expire_integer' => '0'
		    ), $atts );
			
			$post_count = $local_atts[ 'post_count' ];
			$post_type = $local_atts[ 'post_type' ];
			$post_taxonomy = $local_atts[ 'taxonomy' ];
			$post_taxonomy_terms = $local_atts[ 'terms' ];

			// Jobs Custom Options
			$jobs_options = get_option('options_jobs_field_group');
			$jobs_expire_date = $jobs_options['jobs_expire_setting'];
			$jobs_minimum_blocks = $jobs_options['jobs_minimum_blocks'];
			$jobs_default_ID = $jobs_options['jobs_default_postID'];

			$post_expire_date = ( isset($jobs_expire_date) ) ? $jobs_expire_date : '12';				
			$expires_date = '-' . $post_expire_date  .' month';  // remove later
			$expire_date_param = date( 'Y-m-d', strtotime($expires_date) );  // remove later
			
			if($post_type == 'jobs') {

			} elseif($post_taxonomy == 'uncategorized'){	
				global $wp_version;	
				if ( $wp_version < 4.5 ) {
					$terms = get_terms( $post_taxonomy, array(
					    'hide_empty' => false,
					    'fields' => 'id=>slug'
					    )
					);
				}else {
					$terms = get_terms( array(
					    'taxonomy' => $post_taxonomy,
					    'hide_empty' => false,
					    'fields' => 'id=>slug'
					    )
					);
				}
			} else {  // if terms not 'all' then show only defined terms in the taxonomy (passed in atts)
				$terms = $post_taxonomy_terms;
			}

			if($post_type == 'jobs') {
				$args=array(
			      'post_type' => $post_type,
			      'post_status' => 'publish',
			      'order' => 'DESC',
			      'post__not_in' => array($jobs_default_ID),
			      'date_query' => array(
			        'after' => $expire_date_param,
			        'inclusive' => true,
			      )
			    );
			} elseif( $post_taxonomy != '' && $expires == 'true'){
			    $args=array(
			      'post_type' => $post_type,
			      'post_status' => 'publish',
			      'posts_per_page' => $post_count,
			      'orderby' => 'title',
			      'order' => 'ASC',
			      'tax_query' => array(
						array(
							'taxonomy' => $post_taxonomy,
							 'field' => 'slug',
							 'terms' => $post_taxonomy_terms
						),
				  ),
				  'date_query' => array(
			        'after' => $expire_date_param,
			        'inclusive' => true,
			      ) 
			    );
			}elseif($post_taxonomy != ''){
				$args=array(
			      'post_type' => $post_type,
			      'post_status' => 'publish',
			      'posts_per_page' => $post_count,
			      'orderby' => 'title',
			      'order' => 'ASC',
			      'tax_query' => array(
						array(
							'taxonomy' => $post_taxonomy,
							 'field' => 'slug',
							 'terms' => $post_taxonomy_terms
						),
				  ) 
			    );
			}else{
				$args=array(
			      'post_type' => $post_type,
			      'post_status' => 'publish',
			      'posts_per_page' => $post_count,
			      'orderby' => 'title',
			      'order' => 'ASC'
			    );
			}
			
			$obj = get_post_type_object( $post_type );
			$post_type_singular_name =  $obj->labels->singular_name;
			$counter = 0;
			$activeClass = '';
			$output = '';
			$my_query = null;
			
		    $my_query = new WP_Query($args);
		    if( $my_query->have_posts() ) {
		    	$output .= '<div id="carousel-header-' . $post_type . '-' . $post_taxonomy . '" class="carousel slide" data-ride="carousel" style="overflow:hidden">';

				$output .= '<ol class="carousel-indicators">';
				while ($my_query->have_posts()) : $my_query->the_post();
				$activeClass = ($counter == 0) ? 'active' : '';
					$output .= '<li data-target="#carousel-header-' . $post_type . '-' . $post_taxonomy . '" data-slide-to="' . $counter . '"  class="' . $activeClass . '"></li>';
				$counter++;
				endwhile;
				$output .= '</ol>';
				$counter = 0;

				$output .= '<div class="carousel-inner" role="listbox">';
				while ($my_query->have_posts()) : $my_query->the_post();
					$excerpt = get_custom_excerpt(85);
					$activeClass = ($counter == 0) ? 'active' : '';
					$output .= '<div class="item ' . $activeClass . '">';
					$output .= '  	<div class="item-content" style="overflow:hidden;">';
					$output .= '		<h5>' . get_the_title() . '</h5>';
					$output .= '		<p>' . $excerpt . '</p>'; 
					if(get_field('dsp_job_posting_link') ){
						//$output .= '				<div class="widgete-link"><a href="' .  get_field('dsp_job_posting_link') . '" class="blue-link" target="_blank">View Job Details</a></div>';
					}
					$output .= '		<div class="widgete-link"><a href="/community/jobs/" class="blue-link" target="_blank">View All Jobs</a></div>';
					//$output .= '		<span class="tag tag-blue">' . $post_type_singular_name . '</span>';
					$output .= '	</div>';
					$output .= '</div>';
				
				$counter++;
				endwhile;
				if($post_type == 'jobs' && $counter < ( $jobs_minimum_blocks + 1 )){
					$output .= default_slider_job_block($atts);
				}
				$output .= '</div>';
				
				
				$output .= '</div>';
			}else{
				if($post_type == 'jobs'){
					$output .= default_slider_job_block($atts);
				}else{
					
				}
			}
			wp_reset_postdata();
			wp_reset_query();
			return $output;
		}
		
	 	
		function custom_slider_function($atts) {
				
			global $post;
			$local_atts = shortcode_atts( array(
		        'id' => '',
		        'name' => ''
		    ), $atts );
			$slider_id = $local_atts[ 'id' ];
			
			$repeatable_project_fields = get_post_meta($slider_id, 'slider-builder-meta-box-blocks', true);
			$image_size = "full";
			$output = '';
			
			//$output .= "POST: " . $post->ID;
			$output .= '<div id="carousel-header-' . $slider_id . '" class="carousel slide" data-ride="carousel" style="background:#333; overflow:hidden">';
			if ( $repeatable_project_fields ) :
				$output .= '<ol class="carousel-indicators">';
				foreach ( $repeatable_project_fields as $key => $field ) {
					$activeClass = ($key == 0) ? 'active' : '';
					$output .= '<li data-target="#carousel-header-' . $slider_id . '" data-slide-to="' . $key . '"  class="' . $activeClass . '"></li>';
				}
				$output .= '</ol>';
				
				$output .= '<div class="carousel-inner" role="listbox">';
				foreach ( $repeatable_project_fields as $key => $field ) {
					if($field['blockType'] == 'image'){ 
						//$output .= "image";
						// $field['imageSlide'];
						// $field['imageSlideLink'];
						// $field['imageSlideOverlayTitle'];
						// $field['imageSlideOverlayCopy'];
						$activeClass = ($key == 0) ? 'active' : '';
						$output .= '<div class="item ' . $activeClass . '">';
					    $output .= '  	<div class="item-content">';
						if (array_key_exists('imageSlide', $field) && $field['imageSlide'] != '') {
							$attachmentID = esc_attr( $field['imageSlide'] ); 
							if( $image_attributes = wp_get_attachment_image_src( $attachmentID, $image_size ) ) {
								$output .= ' <img src="' . $image_attributes[0] . '" />';
							}
						}
					    $output .= '    	<div class="container">';
						if (array_key_exists('imageSlideLink', $field) && $field['imageSlideLink'] != ''){
							$output .= '		<a href="' . $field['imageSlideLink']  . '">';
							$output .= '       	 	<div class="overlay-container">';
							$output .= '        		<div class="overlay">';
							if (array_key_exists('imageSlideOverlayTitle', $field) && $field['imageSlideOverlayTitle'] != ''){
								 $output .= 		    	'<h5>' . $field['imageSlideOverlayTitle'] . '</h5>';
						    }
							if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != ''){
								 $output .= 		    	'<p>' . $field['imageSlideOverlayCopy'] . '</p>';
						    }
							$output .= '        		</div>';
							$output .= '        	</div>';
							$output .= '    	</a>';
						}else{
						 	$output .= '		<div class="overlay-container">';
							if (array_key_exists('imageSlideOverlayTitle', $field) && $field['imageSlideOverlayTitle'] != ''){
								 $output .= 		    	'<h5>' . $field['imageSlideOverlayTitle'] . '</h5>';
						    }
							if (array_key_exists('imageSlideOverlayCopy', $field) && $field['imageSlideOverlayCopy'] != ''){
								 $output .= 		    	'<p>' . $field['imageSlideOverlayCopy'] . '</p>';
						    }
							$output .= '    	</div>';
						}
						$output .= '        </div>';
					    $output .= '    </div>';
					    $output .= '  </div>';
						
					}elseif($field['blockType'] == 'video'){  
						//$output .= "video";
						// $field['videoSlidePosterImage'];
						// $field['videoSlideLink'];
						// $field['videoSlideSourceLink'];
						// $field['videoSlideOverlayTitle'];
						// $field['videoSlideOverlayCopy'];
						if (array_key_exists('videoSlidePosterImage', $field) && $field['videoSlidePosterImage'] != '') {
							$videoPosterImageID = esc_attr( $field['videoSlidePosterImage'] ); 
							if( $image_poster_attributes = wp_get_attachment_image_src( $videoPosterImageID, $image_size ) ) {
								$videoPosterImagePath = $image_poster_attributes[0];
							}
						}
						$activeClass = ($key == 0) ? 'active' : '';
						$output .= '<div class="item ' . $activeClass . '">';
					    $output .= '  	<div class="item-content">';
						$output .= '  		<div class="video-container">';
					    $output .= '  	  		<video preload="auto" width="1160" height="325" class="vid" poster="' . $videoPosterImagePath . '" controls>';
				        $output .= '  	      		<source src="' . $field['videoSlideSourceLink'] . '" type="video/mp4">';
				        $output .= '  	   		</video>';
			            $output .= '  		</div>';
					    $output .= '    	<div class="container">';
						if (array_key_exists('videoSlideLink', $field) && $field['videoSlideLink'] != ''){
							$output .= '		<a href="' . $field['videoSlideLink']  . '">';
							$output .= '       	 	<div class="overlay-container">';
							$output .= '        		<div class="overlay"><h5>' . $field['videoSlideOverlayTitle'] . '</h5><p>' . $field['videoSlideOverlayCopy'] . '</p></div>';
							$output .= '        	</div>';
							$output .= '    	</a>';
						}else{
						 	$output .= '		<div class="overlay-container">';
							$output .= '    		<div class="overlay"><h5>' . $field['videoSlideOverlayTitle'] . '</h5><p>' . $field['videoSlideOverlayCopy'] . '</p></div>';
							$output .= '    	</div>';
						}
						$output .= '        </div>';
					    $output .= '    </div>';
					    $output .= '  </div>';
					}
				}
				$output .= '</div>';
			
			else:
				
			endif;	
				$output .= '<a class="left carousel-control" href="#carousel-header" role="button" data-slide="prev">';
			    $output .= '  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
			    $output .= '  <span class="sr-only">Previous</span>';
			    $output .= '</a>';
			    $output .= '<a class="right carousel-control" href="#carousel-header" role="button" data-slide="next">';
			    $output .= '  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
			    $output .= '  <span class="sr-only">Next</span>';
			    $output .= '</a>';
			$output .= '</div>';
			wp_reset_postdata();
			return $output;
		}
		
		add_shortcode('custom_slider', 'custom_slider_function');
		add_shortcode('custom_post_slider', 'custom_post_slider_function');
		
		
		//[custom_post_slider post_type="jobs" taxonomy="category"]
		//[custom_post_slider post_type="events" terms="learn" taxonomy="event-type"] 'post_count' => '-1',
		        // 'post_type' => 'post',
		        // 'taxonomy' => 'category',
		        // 'terms' => 'uncategorized'
		
		//add_shortcode('custom_slider', 'custom_slider_function');
		//add_shortcode('custom_slider', __NAMESPACE__ . '\\custom_slider_function');  //--if in sage them
		//-->> USAGE EXAMPLE : [custom_slider id="33" transition="slide"]
		
	 }
	
	
	
	//////------>> END - DISPLAY SLIDER FUNCTION <<------//////
}

$BASEKIT_Function_Slider = new BASEKIT_Function_Slider();