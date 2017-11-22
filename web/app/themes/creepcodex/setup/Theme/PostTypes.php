<?php
/**
 * This class creates our post types
 */

namespace Theme;

class PostTypes {

	protected $types = array(
		'accused'
	);

	public function __construct(){
		if ($this->types && !empty($this->types)){
			foreach ($this->types as $type) {
				$this->$type();
			}
		}
	}

	public function accused(){

		register_via_cpt_core(
			array(
				'Accused',
				'Accused',
				'accused'
			),
			array(
				'menu_icon' => 'dashicons-businessman',
				'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
				'has_archive' => true
			)
		);

	}

}
