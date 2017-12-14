<?php
/**
 * The class used to add Twig filters and functions
 */

namespace Theme\Timber;

class TwigFilters {

	public function __construct(){
		add_filter('get_twig', array($this, 'add_filters'));
		// add_filter('get_twig', array($this, 'add_functions'));
	}

	public function add_filters($twig){
		$twig->addFilter(new \Twig_SimpleFilter('ordinal', array($this, 'ordinal')));
		return $twig;
	}

	public function add_functions($twig){
		return $twig;
	}

	/////////////
	// Filters //
	/////////////

	public function ordinal($number){

		if (!is_numeric($number)){
			return $number;
		}

		$ends = array('th','st','nd','rd','th','th','th','th','th','th');

		if (($number %100) >= 11 && ($number%100) <= 13){
			$ordinal = $number . '<sup>th</sup>';
		}
		else {
			$ordinal = $number . '<sup>' . $ends[$number % 10] . '</sup>';
		}

		return $ordinal;

	}

}