<?php

namespace Theme;

class AdminCustomize {

	public function __construct(){

		// add_action('login_head', array($this, 'custom_login_logo');

		add_action('init', array($this, 'change_default_post_type_labels'));

	}

	/**
	 * Customize the logo displayed on the Wordpress login page
	 */
	public function custom_login_logo() {
		echo '<style type="text/css">
		h1 a { background-image: url('.get_bloginfo('template_directory').'/assets/img/logo.svg) !important;
		 background-size: 100% !important;
		 width: 100% !important;
		 height: 125px !important;
		 pointer-events: none;
		}
		</style>';
	}

	public function change_default_post_type_labels(){

		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'Blog Posts';
		$labels->singular_name = 'Blog Post';

	}


}