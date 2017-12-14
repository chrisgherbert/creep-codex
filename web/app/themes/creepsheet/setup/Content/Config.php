<?php
/**
 * The configuration class for our site
 * Holds class mapping references, etc.
 */

namespace Content;

class Config {

	public static function post_type_classes(){
		return array(
			'post' => '\Content\Post',
			'accused' => '\Content\Accused'
		);
	}

}