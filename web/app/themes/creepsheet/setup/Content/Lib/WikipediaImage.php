<?php

namespace Content\Lib;
use \Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;

class WikipediaImage {

	public $image_title;
	public $image_data;

	public function __construct($image_title){
		$this->image_title = $image_title;
	}

	public function get_image_data(){

		if (isset($this->image_data)){
			return $this->image_data;
		}

		$api = MediawikiApi::newFromPage('https://en.wikipedia.org/w/api.php');

		$request = new SimpleRequest('query', array(
			'titles' => 'Image:' . $this->image_title,
			'prop' => 'imageinfo',
			'iiprop' => 'extmetadata|url'
		));

		$data = $api->getRequest($request);

		if (isset($data['query']['pages']) && $data['query']['pages']){

			$page = reset($data['query']['pages']);

			$this->image_data = $page;

			return $this->image_data;

		}
		else {
			$this->image_data = false;
			return $this->image_data;
		}

	}

	public function get_image_url(){

		$data = $this->get_image_data();

		if (!$data){
			return false;
		}

		if (isset($data['imageinfo'][0]['url'])){
			return $data['imageinfo'][0]['url'];
		}

	}

	public function get_image_attribution_string(){

		if ($this->is_creative_commons_licensed()){
			$attribution = $this->get_cc_image_credit_string();
		}
		else {
			$attribution = $this->get_generic_image_credit_string();
		}

		return self::clean_markup($attribution);

	}

	public function get_metadata_item($key){

		$data = $this->get_image_data();

		if ($data['imageinfo'][0]['extmetadata'][$key]['value'] ?? false){
			return $data['imageinfo'][0]['extmetadata'][$key]['value'];
		}

	}

	public function get_description_url(){

		$data = $this->get_image_data();

		if ($data['imageinfo'][0]['descriptionurl'] ?? false){
			return $data['imageinfo'][0]['descriptionurl'];
		}

	}

	public function get_sensible_image_description(){

		$description = strip_tags($this->get_metadata_item('ImageDescription'));
		$title = $this->get_metadata_item('ObjectName');

		if ($description && strlen($description) < 100){
			return $description;
		}
		elseif ($title){
			return $title;
		}

	}

	///////////////
	// Protected //
	///////////////

	protected function is_trademarked(){

		if ($this->get_metadata_item('Restrictions') == 'trademarked'){
			return true;
		}

	}

	protected function is_public_domain(){

		if ($this->get_metadata_item('Copyrighted') == 'False'){
			return true;
		}

	}

	protected function is_creative_commons_licensed(){

		$license_url = $this->get_metadata_item('LicenseUrl');

		if (strpos($license_url, 'creativecommons.org') !== false){
			return true;
		}
		else {
			return false;
		}

	}

	protected function get_generic_image_credit_string(){

		$description_text = $this->get_sensible_image_description();
		$description_url = $this->get_description_url();

		$parts = array();

		if ($description_text){
			$parts['description'] = $description_text;
		}

		if ($description_url){
			$parts['url'] = "(<a href='$description_url'>link</a>)";
		}

		return implode(' ', $parts);

	}

	protected function get_cc_image_credit_string(){

		$description_url = $this->get_description_url();
		$description_text = $this->get_sensible_image_description();
		$author_string = $this->get_metadata_item('Artist');
		$license_text = $this->get_metadata_item('LicenseShortName');
		$license_url = $this->get_metadata_item('LicenseUrl');

		$parts = array();

		if ($description_text && $description_url) {
			$parts['title'] = "$description_text (<a href='$description_url'>link</a>)";
		}

		if ($author_string){
			$parts['author'] = "by $author_string";
		}

		if ($license_text && $license_url){
			$parts['license'] = "is licensed $license_text (<a href='$license_url'>link</a>)";
		}

		return implode(' ', $parts);

	}

	////////////
	// Static //
	////////////

	protected static function clean_markup($html){

		// Remove all tags except links
		$stripped = strip_tags($html, '<a>');

		// Remove style attributes (double quotes)
		$stripped = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $stripped);

		// Remove style attributes (single quotes)
		$stripped = preg_replace('/(<[^>]+) style=\'.*?\'/i', '$1', $stripped);

		return $stripped;

	}

}