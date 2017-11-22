<?php
/**
 * Class to define our taxonomies
 *
 * Depends on the webdevstudios/taxonomy_core library
 */

namespace Theme;

class Taxonomies {

	protected $taxonomies = array(
		'industry',
		'victim_gender',
	);

	public function __construct(){

		if ( ! class_exists( 'Taxonomy_Core' ) ){
			error_log('Please load the webdevstudios/taxonomy_core library');
			return false;
		}

		if ($this->taxonomies && !empty($this->taxonomies)){
			foreach ($this->taxonomies as $taxonomy) {
				$this->$taxonomy();
			}
		}

	}

	////////////////
	// Taxonomies //
	////////////////

	public function industry(){

		register_via_taxonomy_core(
			array(
				'Industry', // Singular Name
				'Industries', // Plural Name
				'industry'
			),
			array(
				'description' => 'Industry that a person works in (ex: "Entertainment")',
				'hierarchical' => true, // "true" for category-like interface, "false" for tag-link interface,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true
			),
			array('accused') // Object types (custom post types) to include
		);

	}

	public function victim_gender(){

		register_via_taxonomy_core(
			array(
				'Victim Gender',
				'Victim Genders',
				'victim-gender'
			),
			array(
				'description' => 'The genders of the alleged victims. More than one can be checked.',
				'hierarchical' => true, // "true" for category-like interface, "false" for tag-link interface,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true
			),
			array('accused')
		);

	}

}