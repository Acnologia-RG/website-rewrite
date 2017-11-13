<?php
/**
 * Maya Winterfox WordPress Theme Functions - functions.php
 */

/**
 * Enqueue Stylesheet
 */
if ( is_admin() === FALSE ) {
    wp_enqueue_style( 'style', get_stylesheet_uri(), array(), FALSE, 'all' );
}

/**
 * Register Navigation Menus
 */
register_nav_menus( array(
    'header_menu' => 'Header Menu',
) );

/**
 * Register Sidebar
 */
register_sidebar( array(
    'name' => 'Footer',
    'id' => 'footer-sidebar',
) );

/**
 * Add Image Sizes
 */
add_image_size( 'feature', 1920, 1080, array( 'center', 'center' ) );

/**
 * Declare Support for Post Thumbnails
 */
add_theme_support( 'post-thumbnails' );

/**
 * Load Classes
 */
include_once __DIR__ . '/classes/autoload.php';

/**
 * Load Vendor Scripts
 */
include_once __DIR__ . '/vendor/autoload.php';

/**
 * General Settings / Declarations
 */
include_once __DIR__ . '/settings/general.php';

/**
 * Load Utilities
 */
include_once __DIR__ . '/utilities/autoload.php';

/**
 * Load Includes
 */
include_once __DIR__ . '/includes/autoload.php';

/**
 * Load Products Array
 */
include_once __DIR__ . '/data/products.php';
