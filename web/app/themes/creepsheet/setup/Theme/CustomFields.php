<?php
/**
 * This class defines custom fields for our post types
 */

namespace Theme;

class CustomFields {

	/**
	 * Optional custom field prefix
	 * @var string
	 */
	protected $prefix;
	/**
	 * Array of box names - correspond to method names
	 * @var array
	 */
	protected $boxes = array(
		'industry',
		'person',
		'accused',
	);

	protected $acf_boxes = array(
		'acf_external_sources'
	);

	/**
	 * Constructor - should fire off all metabox creation when called.
	 */
	public function __construct(){
		$this->add_show_on_filters();
		$this->load_boxes();
		$this->load_acf_boxes();
	}

	/**
	 * Loop through our boxes property and invoke the corresponding
	 * function that will define its metabox(es)
	 */
	protected function load_boxes(){
		if ($this->boxes && !empty($this->boxes)){
			foreach ($this->boxes as $box) {
				add_action('cmb2_admin_init', array($this, $box));
			}
		}
	}

	protected function load_acf_boxes(){
		if ($this->acf_boxes && !empty($this->acf_boxes)){
			foreach ($this->acf_boxes as $box) {
				$this->$box();
			}
		}
	}

	///////////////
	// Metaboxes //
	///////////////

	/**
	 * Industries
	 */

	public function industry(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'industry',
			'title' => 'Extended Info',
			'object_types' => array('term'),
			'taxonomies' => array('industry')
		));

		$cmb2->add_field(array(
			'id' => 'icon',
			'name' => 'Industry Icon',
			'desc' => 'This should be white at 25% opacity. It will be displayed offset.',
			'type' => 'file',
			'options' => array(
				'url' => false
			)
		));

	}

	/**
	 * Metabox for person content types (could include accuser/victim if added)
	 */
	public function person(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'person',
			'title' => 'Person Info',
			'object_types' => array('accused')
		));

		$cmb2->add_field(array(
			'id' => 'job_title',
			'name' => 'Title',
			'type' => 'text',
			'desc' => 'Person professions/titles (ex: "Athlete, Actor")'
		));

		$cmb2->add_field(array(
			'id' => 'last_name',
			'name' => 'Last Name',
			'type' => 'text',
			'desc' => 'Used only for sorting. If no last name, use normal name'
		));

		$cmb2->add_field(array(
			'id' => 'aka',
			'name' => 'Also Known As',
			'type' => 'text',
			'desc' => 'List other names the person goes by'
		));

		$cmb2->add_field(array(
			'id' => 'wikipedia_url',
			'name' => 'Wikipedia Profile URL',
			'type' => 'text_url',
			'desc' => 'URL of the person\'s Wikipedia profile.  With this the site may be able to automatically pull a photo with an appropriate attribution notice.'
		));

		$cmb2->add_field(array(
			'id' => 'tmdb_url',
			'name' => 'The Movie DB URL',
			'type' => 'text_url',
			'desc' => 'URL of the person\'s profile on <a target="_blank" href="https://www.themoviedb.org">The Movie DB</a>. The site will use this to pull in film/TV credits.'
		));

	}

	public function accused(){

		$cmb2 = new_cmb2_box(array(
			'id' => 'accused',
			'title' => 'Accused Info',
			'object_types' => array('accused')
		));

		$cmb2->add_field(array(
			'id' => 'allegation_short',
			'name' => 'Short Allegation Description',
			'type' => 'textarea_small',
			'desc' => 'Short description of allegations. Ex: "Statutory rape, child pornography"'
		));

	}

	public function front(){

		$cmb2 = new_cmb2_box( array(
			'id'           => 'front_page_details',
			'title'        => 'Front Page Details',
			'object_types' => array( 'page', ),
			'show_on' => array( 'key' => 'front-page' )
		) );

		$cmb2->add_field( array(
			'id'       => 'hero_image',
			'name'     => 'Hero Image',
			'type'     => 'file',
			'desc'     => 'An image that will appear prominently on the front page.',
			'options'  => array(
				'url'  => false
			)
		) );

		$this->subtitle_field($cmb2);

	}

	///////////////////
	// ACF Metaboxes //
	///////////////////

	public function acf_external_sources(){

		if (!function_exists('acf_add_local_field_group')){
			error_log('Looks like ACF is not active!');
			return false;
		}

		acf_add_local_field_group(array(
			'key' => 'source_links',
			'title' => 'Source Links',
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'accused'
					)
				)
			),
			'fields' => array(
				array(
					'key' => 'external_source_urls',
					'name' => 'external_source_urls',
					'type' => 'repeater',
					'layout' => 'row',
					'sub_fields' => array(
						array(
							'key' => 'link_url',
							'label' => 'URL',
							'name' => 'link_url',
							'type' => 'text',
							'instructions' => 'Use credible, mainstream sources.'
						)
					)
				)
			)
		));

		// Disgusting ACF repeater field definition
		// acf_add_local_field_group(array (
		// 	'key' => 'external_sources_metabox',
		// 	'title' => 'Sources',
		// 	'fields' => array (
		// 		array (
		// 			'key' => 'external_links',
		// 			'label' => 'External Sources',
		// 			'name' => 'external_links_name',
		// 			'type' => 'repeater',
		// 			'value' => NULL,
		// 			'instructions' => 'Include links to sources that were used in the profile. They should be mainstream, credible publications.',
		// 			'required' => 0,
		// 			'conditional_logic' => 0,
		// 			'wrapper' => array (
		// 				'width' => '',
		// 				'class' => '',
		// 				'id' => '',
		// 			),
		// 			'collapsed' => 'source_url',
		// 			'min' => 0,
		// 			'max' => 0,
		// 			'layout' => 'table',
		// 			'button_label' => '',
		// 			'sub_fields' => array (
		// 				array (
		// 					'key' => 'source_url',
		// 					'label' => 'url',
		// 					'name' => 'url_name',
		// 					'type' => 'url',
		// 					'value' => NULL,
		// 					'instructions' => '',
		// 					'required' => 1,
		// 					'conditional_logic' => 0,
		// 					'wrapper' => array (
		// 						'width' => '',
		// 						'class' => '',
		// 						'id' => '',
		// 					),
		// 					'default_value' => '',
		// 					'placeholder' => '',
		// 				),
		// 			),
		// 		),
		// 	),
		// 	'location' => array (
		// 		array (
		// 			array (
		// 				'param' => 'post_type',
		// 				'operator' => '==',
		// 				'value' => 'accused',
		// 			),
		// 		),
		// 	),
		// 	'menu_order' => 0,
		// 	'position' => 'normal',
		// 	'style' => 'default',
		// 	'label_placement' => 'top',
		// 	'instruction_placement' => 'label',
		// 	'hide_on_screen' => '',
		// 	'active' => 1,
		// 	'description' => '',
		// ));


	}

	/////////////////////
	// Reusable Fields //
	/////////////////////
	//
	// Easily reuse field definitions by passing in the
	// current $cmb2 object being manipulated

	/**
	 * Subtitle field
	 * @param  object $cmb2
	 */
	protected function subtitle_field($cmb2){
		return $cmb2->add_field( array(
			'id'       => 'subtitle',
			'name'     => 'Subtitle',
			'type'     => 'textarea_small',
			'desc'     => '(Optional) Enter a subtitle',
		) );
	}

	/**
	 * Featured Video URL field
	 */
	protected function featured_video_url_field($cmb2){
		return $cmb2->add_field( array(
			'id'       => 'featured_video_url',
			'name'     => 'Featured Video URL',
			'type'     => 'oembed',
			'desc'     => '(YouTube only) Add the URL of the YouTube video you would like featured. This will attempt to retrieve the thumbnail from YouTube and set it as the featured image.',
		) );
	}

	/////////////////////
	// Show On Filters //
	/////////////////////

	/**
	 * Create any custom show_on filters that we may want to utilize
	 */
	protected function add_show_on_filters(){
		add_filter('cmb2_show_on', array($this, 'show_on_front_page'), 10, 2);
	}

	public function show_on_front_page($display, $meta_box){

		// Move along if we haven't defined a "show_on" property
		if ( ! isset( $meta_box['show_on']['key'] ) ){
			return $display;
		}

		// Ignore if not the front-page "show_on"
		if ( 'front-page' !== $meta_box['show_on']['key'] ) {
			return $display;
		}

		$post_id = 0;

		// If we're showing it based on ID, get the current ID
		if ( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'];
		} elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = $_POST['post_ID'];
		}

		if ( ! $post_id ) {
			return false;
		}

		// Get ID of page set as front page, 0 if there isn't one
		$front_page = get_option( 'page_on_front' );

		// there is a front page set and we're on it!
		return $post_id == $front_page;

	}

}