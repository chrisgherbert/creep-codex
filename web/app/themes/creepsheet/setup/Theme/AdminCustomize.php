<?php

namespace Theme;

class AdminCustomize {

	public function __construct(){

		// add_action('login_head', array($this, 'custom_login_logo');

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


}