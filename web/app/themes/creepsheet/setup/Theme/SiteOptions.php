<?php
/**
 * Class for creating a tabbed site options page
 * Extends the cmb2-metatabs-options library
 */

namespace Theme;

class SiteOptions {

	/**
	 * Key used for database key and prefixes
	 * @var string
	 */
	protected $options_key = 'site_options';

	/**
	 * Constructor function
	 * Hooks into cmb2_admin_init to create page
	 */
	public function __construct(){
		add_action( 'cmb2_admin_init', array( $this, 'initialize' ) );
	}

	/**
	 * Creates instance of Cmb2_Metatabs_Options with parameters
	 * defined within this class.
	 */
	public function initialize(){

		// configuration array
		$args = array(
			'key'     => $this->options_key,
			'cols'    => 1,
			'boxes'   => $this->define_boxes(),
			'tabs'    => $this->define_tabs(),
			'menuargs' => array(
				'parent_slug'   => '', // blank for new top-level menu
				'menu_title'    => 'Site Options',
				'page_title'    => get_bloginfo('name') . ' Site Options',
				'position'      => 200,
			)
		);

		if (class_exists('Cmb2_Metatabs_Options')) {
			// create the options page
			new \Cmb2_Metatabs_Options( $args );
		} else {
			throw new \Exception('Please install the Cmb2_Metatabs_Options module to use site options.');
		}

	}

	/**
	 * Function to create the tabs
	 * @return array
	 */
	function define_tabs(){

		$tabs = array();

		$tabs[] = array(
			'id'    => 'tab_general',
			'title' => 'General Info',
			'desc'  => '<p>Address, Phone Number, etc.</p>',
			'boxes' => array(
				'general',
			),
		);
		$tabs[] = array(
			'id'    => 'tab_social',
			'title' => 'Social Media',
			'desc'  => '<p>Social Media Accounts</p>',
			'boxes' => array(
				'social',
			),
		);
		$tabs[] = array(
			'id' => 'tab_tracking',
			'title' => 'Tracking & Analytics',
			'desc' => '',
			'boxes' => array(
				'tracking'
			)
		);

		return $tabs;

	}

	function define_boxes(){

		// holds all CMB2 box objects
		$boxes = array();

		// we will be adding this to all boxes
		$show_on = array(
			'key' => 'options-page',
			'value' => array($this->options_key),
		);

		/////////////////////////////
		// General Information Box //
		/////////////////////////////

		$box_key = 'general';

		$cmb = new_cmb2_box( array(
			'id'        => $box_key,
			'title'     => 'General Site Information',
			'show_on'   => $show_on, // critical, see wiki for why
		));
		$cmb->add_field( array(
			'id'       => $box_key . '_sign_up_modal_content',
			'name'     => 'Sign Up Modal Content',
			'type'     => 'textarea_code',
			'desc'     => 'The content that will populate the sign up modal',
		) );

		$cmb->object_type( 'options-page' );  // critical, see wiki for why
		$boxes[] = $cmb;

		//////////////////////
		// Social Media Box //
		//////////////////////

		$box_key = 'social';

		$cmb = new_cmb2_box( array(
			'id'        => $box_key,
			'title'     => 'Social Media Accounts',
			'show_on'   => $show_on,
		));
		$cmb->add_field( array(
			'id' => $box_key . '_twitter',
			'type' => 'text_url',
			'name' => 'Twitter Profile URL'
		));
		$cmb->add_field( array(
			'id' => $box_key . '_facebook',
			'type' => 'text_url',
			'name' => 'Facebook Profile URL'
		));
		$cmb->add_field(array(
			'id' => $box_key . '_linkedin',
			'type' => 'text_url',
			'name' => 'LinkedIn Profile URL'
		));
		$cmb->object_type( 'options-page' );
		$boxes[] = $cmb;

		//////////////////////////
		// Tracking & Analytics //
		//////////////////////////

		$box_key = 'tracking';

		$cmb = new_cmb2_box(array(
			'id' => $box_key,
			'title' => 'Tracking & Analytics',
			'show_on' => $show_on,
			'desc' => ''
		));
		$cmb->add_field(array(
			'id' => $box_key . '_codes',
			'type' => 'textarea_code',
			'name' => 'Tracking Codes',
			'desc' => 'Scripts and code inserted directly into the head of each page.  Used for Google Analytics tracking scripts, for example.'
		));
		$cmb->object_type('options-page');
		$boxes[] = $cmb;


		return $boxes;

	}

}