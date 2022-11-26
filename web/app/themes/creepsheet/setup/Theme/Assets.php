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
		// If there are weird JS issues with plugins that do not
		// properly list jQuery as a dependency, just comment this out.
		//
		// wp_deregister_script('jquery');
		// wp_register_script('jquery', get_template_directory_uri() . '/node_modules/', false, '3.1.1', true);

		wp_register_script( 'main', get_template_directory_uri() . '/dist/main.js', array('jquery'), '', true );
		wp_localize_script( 'main', 'wpObject', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'themeDir' => get_template_directory_uri(),
		) );
		wp_enqueue_script( 'main' );

	}

	public function enqueue_stylesheets(){

		// Create timestamp for cache-busting
		$timestamp = filemtime(get_template_directory() . '/dist/main.css');
		wp_enqueue_style('main', get_template_directory_uri() . '/dist/main.css', false, $timestamp);

	}

}