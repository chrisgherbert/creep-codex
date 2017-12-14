<?php
/**
 * Class that modifies our archives pages
 */

namespace Theme\Hooks;

class Archives {

	public function __construct(){
		add_filter('pre_get_posts', array($this, 'add_cpt_to_archives'), 10, 1);
	}

	/**
	 * Checks to make sure we're on a generic archive page (taxonomy, date, etc.) and
	 * not a specific post type archive, then adds our custom post types to the query
	 * @param Array $query
	 */
	public function add_cpt_to_archives($query){

		if ( $query->is_main_query() && $query->is_archive() && !$query->is_post_type_archive() && empty( $query->query_vars['suppress_filters'] ) ) {
			$query->set( 'post_type', array('article') );
		}

	}

}