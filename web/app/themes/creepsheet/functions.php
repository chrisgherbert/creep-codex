<?php

$timber = new \Timber\Timber();

////////////////////////////////
// Make sure Timber is loaded //
////////////////////////////////

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

/////////////////////////////////////
// Setup Timber template locations //
/////////////////////////////////////

Timber::$dirname = array('templates', 'views');


////////////////////////////
// Initiate Theme Classes //
////////////////////////////

// Register the autoloaders
require( get_template_directory() . '/setup/autoloaders.php');

// Instantiate theme classes
new Theme\Assets();
new Theme\PostTypes();
new Theme\CustomFields();
new Theme\Taxonomies();
new Theme\SiteOptions();
new Theme\AdminCustomize();
new Theme\Timber\TwigFilters();
new Theme\Timber\Context();
new Theme\Hooks\Feeds();
new Theme\Hooks\Archives();
new Theme\Hooks\Ajax();
new Theme\Hooks\YoutubeFeaturedImage();
new Theme\Hooks\ContentCreation();

/////////////////////////
// Basic Theme Options //
/////////////////////////

// Theme Support
add_theme_support( 'post-formats', []);
add_theme_support( 'post-thumbnails' );
add_theme_support( 'menus' );
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );

// Remove extraneous <head> tags
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Order accused archive by name
add_action('pre_get_posts', function($query){

	if (is_post_type_archive('accused') && !is_admin()) {

		$query->set('orderby', 'title');
		$query->set('order','ASC');

	}

});