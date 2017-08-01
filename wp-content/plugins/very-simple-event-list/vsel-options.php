<?php

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// add admin options page
function vsel_menu_page() {
    add_options_page( __( 'VSEL', 'very-simple-event-list' ), __( 'VSEL', 'very-simple-event-list' ), 'manage_options', 'vsel', 'vsel_options_page' );
}
add_action( 'admin_menu', 'vsel_menu_page' );


// add admin settings and such 
function vsel_admin_init() {
	add_settings_section( 'vsel-section', __( 'General', 'very-simple-event-list' ), 'vsel_section_callback', 'vsel' );

	add_settings_field( 'vsel-field', __( 'Uninstall', 'very-simple-event-list' ), 'vsel_field_callback', 'vsel', 'vsel-section' );
	register_setting( 'vsel-options', 'vsel-setting', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-1', __( 'Summary', 'very-simple-event-list' ), 'vsel_field_callback_1', 'vsel', 'vsel-section' );
	register_setting( 'vsel-options', 'vsel-setting-1', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-9', __( 'Title', 'very-simple-event-list' ), 'vsel_field_callback_9', 'vsel', 'vsel-section' );
	register_setting( 'vsel-options', 'vsel-setting-9', 'sanitize_text_field' );

	add_settings_section( 'vsel-section-3', __( 'Page', 'very-simple-event-list' ), 'vsel_section_callback_3', 'vsel' );

	add_settings_field( 'vsel-field-8', __( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_8', 'vsel', 'vsel-section-3' );
	register_setting( 'vsel-options', 'vsel-setting-8', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-10', __( 'Link to more info', 'very-simple-event-list' ), 'vsel_field_callback_10', 'vsel', 'vsel-section-3' );
	register_setting( 'vsel-options', 'vsel-setting-10', 'sanitize_text_field' );

	add_settings_section( 'vsel-section-2', __( 'Widget', 'very-simple-event-list' ), 'vsel_section_callback_2', 'vsel' );

	add_settings_field( 'vsel-field-2', __( 'Date', 'very-simple-event-list' ), 'vsel_field_callback_2', 'vsel', 'vsel-section-2' );
	register_setting( 'vsel-options', 'vsel-setting-2', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-3', __( 'Time', 'very-simple-event-list' ), 'vsel_field_callback_3', 'vsel', 'vsel-section-2' );
	register_setting( 'vsel-options', 'vsel-setting-3', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-4', __( 'Location', 'very-simple-event-list' ), 'vsel_field_callback_4', 'vsel', 'vsel-section-2' );
	register_setting( 'vsel-options', 'vsel-setting-4', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-5', __( 'Image', 'very-simple-event-list' ), 'vsel_field_callback_5', 'vsel', 'vsel-section-2' );
	register_setting( 'vsel-options', 'vsel-setting-5', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-7', __( 'Content', 'very-simple-event-list' ), 'vsel_field_callback_7', 'vsel', 'vsel-section-2' );
	register_setting( 'vsel-options', 'vsel-setting-7', 'sanitize_text_field' );

	add_settings_field( 'vsel-field-6', __( 'Link to more info', 'very-simple-event-list' ), 'vsel_field_callback_6', 'vsel', 'vsel-section-2' );
	register_setting( 'vsel-options', 'vsel-setting-6', 'sanitize_text_field' );
}
add_action( 'admin_init', 'vsel_admin_init' );


function vsel_section_callback() {
    echo __( 'General Settings', 'very-simple-event-list' );
}

function vsel_section_callback_3() {
    echo __( 'Page Settings', 'very-simple-event-list' );
}

function vsel_section_callback_2() {
    echo __( 'Widget Settings', 'very-simple-event-list' );
}


// the checkbox fields
function vsel_field_callback() {
	$value = esc_attr( get_option( 'vsel-setting' ) );
	?>
	<input type='hidden' name='vsel-setting' value='no'>
	<label><input type='checkbox' name='vsel-setting' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Do not delete events and settings.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_1() {
	$value = esc_attr( get_option( 'vsel-setting-1' ) );
	?>
	<input type='hidden' name='vsel-setting-1' value='no'>
	<label><input type='checkbox' name='vsel-setting-1' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Show a summary instead of all content.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_9() {
	$value = esc_attr( get_option( 'vsel-setting-9' ) );
	?>
	<input type='hidden' name='vsel-setting-9' value='no'>
	<label><input type='checkbox' name='vsel-setting-9' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Link title to the event page.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_8() {
	$value = esc_attr( get_option( 'vsel-setting-8' ) );
	?>
	<input type='hidden' name='vsel-setting-8' value='no'>
	<label><input type='checkbox' name='vsel-setting-8' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_10() {
	$value = esc_attr( get_option( 'vsel-setting-10' ) );
	?>
	<input type='hidden' name='vsel-setting-10' value='no'>
	<label><input type='checkbox' name='vsel-setting-10' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide on page with all events.', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_2() { 
	$value = esc_attr( get_option( 'vsel-setting-2' ) );
	?>
	<input type='hidden' name='vsel-setting-2' value='no'>
	<label><input type='checkbox' name='vsel-setting-2' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_3() { 
	$value = esc_attr( get_option( 'vsel-setting-3' ) );
	?>
	<input type='hidden' name='vsel-setting-3' value='no'>
	<label><input type='checkbox' name='vsel-setting-3' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_4() { 
	$value = esc_attr( get_option( 'vsel-setting-4' ) );
	?>
	<input type='hidden' name='vsel-setting-4' value='no'>
	<label><input type='checkbox' name='vsel-setting-4' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_5() { 
	$value = esc_attr( get_option( 'vsel-setting-5' ) );
	?>
	<input type='hidden' name='vsel-setting-5' value='no'>
	<label><input type='checkbox' name='vsel-setting-5' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_7() { 
	$value = esc_attr( get_option( 'vsel-setting-7' ) );
	?>
	<input type='hidden' name='vsel-setting-7' value='no'>
	<label><input type='checkbox' name='vsel-setting-7' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}

function vsel_field_callback_6() { 
	$value = esc_attr( get_option( 'vsel-setting-6' ) );
	?>
	<input type='hidden' name='vsel-setting-6' value='no'>
	<label><input type='checkbox' name='vsel-setting-6' <?php checked( $value, 'yes' ); ?> value='yes'> <?php _e( 'Hide', 'very-simple-event-list' ); ?></label>
	<?php
}


// display admin options page
function vsel_options_page() {
?>
<div class="wrap"> 
	<div id="icon-plugins" class="icon32"></div> 
	<h1><?php _e( 'Very Simple Event List', 'very-simple-event-list' ); ?></h1> 
	<form action="options.php" method="POST">
	<?php settings_fields( 'vsel-options' ); ?>
	<?php do_settings_sections( 'vsel' ); ?>
	<?php submit_button(); ?>
	</form>
</div>
<?php
}

?>