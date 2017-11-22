<?php
/**
 * The class for enqueuing CSS & JS assets
 */

namespace Theme;

class Assets {

	public function __construct(){
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_javascript') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_stylesheets') );
	}

	public function enqueue_javascript(){
		//
		// Use Bower version of jQuery, load in the footer. This can make
		// major improvements to Google Speed Insights score, and maybe actual
		// speed.
		//
		// If there are weird JS issues with plugins that do not
		// properly list jQuery as a dependency, just comment this out.
		//
		wp_deregister_script('jquery');
		wp_register_script('jquery', get_template_directory_uri() . '/assets/bower_components/jquery/dist/jquery.min.js', false, '3.1.1', true);

		wp_register_script( 'main', get_template_directory_uri() . '/theme_dist/main.js', array('jquery'), '', true );
		wp_localize_script( 'main', 'wpObject', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'themeDir' => get_template_directory_uri(),
		) );
		wp_enqueue_script( 'main' );

	}

	public function enqueue_stylesheets(){

		// Create timestamp for cache-busting
		$timestamp = filemtime(get_template_directory() . '/theme_dist/main.css');
		wp_enqueue_style('main', get_template_directory_uri() . '/theme_dist/main.css', false, $timestamp);

	}

}