<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
  //'lib/header.php',
  //'lib/carousel.php',
  'lib/block-builder.php',
  'lib/wp_bootstrap_navwalker.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

if (defined('WP_DEV_UPLOADS')){
    if (is_dir(WP_DEV_UPLOADS)){
        function wp_dev_upload_dir($upload_dir){
            $upload_dir['path']    = str_replace( $upload_dir['basedir'], WP_DEV_UPLOADS, $upload_dir['path'] );
            $upload_dir['basedir'] = WP_DEV_UPLOADS;

            return $upload_dir;
        }
        add_filter('upload_dir', 'wp_dev_upload_dir');
    } else {
        error_log('WP_DEV_UPLOADS is defined but does not exist: ' . WP_DEV_UPLOADS);
    }
}
