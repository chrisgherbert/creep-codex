<?php
/**
 * Class that modifies the site's feeds (RSS, Atom, etc.)
 */

namespace Theme\Hooks;

class Feeds {

	public function __construct(){
		add_filter( 'request', array( $this, 'add_cpt_to_rss_feed' ) );
	}

	/**
	 * Checks if the request is for a feed and a specific post type is not set
	 * Adds our custom post types to this feed (the default feed)
	 * @param Array $query
	 */
	public function add_cpt_to_rss_feed($query){

		if (isset($query['feed']) && !isset($query['post_type'])) {
			$query['post_type'] = array('article');
		}

		return $query;

	}
}