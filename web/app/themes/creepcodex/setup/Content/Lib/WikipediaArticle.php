<?php

namespace Content\Lib;
use \Mediawiki\Api\MediawikiApi;
use Mediawiki\Api\SimpleRequest;

class WikipediaArticle {

	public $article_url;
	public $article_data;

	public function __construct($article_url){
		$this->article_url = $article_url;
		$this->article_title = self::extract_article_title_from_url($article_url);
	}

	public function get_article_data(){

		if ($this->article_data){
			return $this->article_data;
		}

		$api = MediawikiApi::newFromPage('https://en.wikipedia.org/w/api.php');

		$request = new SimpleRequest('query', array(
			'titles' => $this->article_title,
			'prop' => 'pageprops'
		));

		$data = $api->getRequest($request);

		if (isset($data['query']['pages']) && $data['query']['pages']){

			$page = reset($data['query']['pages']);

			$this->article_data = $page;

			return $this->article_data;

		}
		else {
			$this->article_data = false;
			return $this->article_data;
		}

	}

	public function get_article_image_object(){

		$data = $this->get_article_data();

		if ($data){

			$img_title = '';

			if (isset($data['pageprops']['page_image_free'])){
				$img_title = $data['pageprops']['page_image_free'];
			}

			if (isset($data['pageprops']['page_image'])){
				$img_title = $data['pageprops']['page_image'];
			}

			if ($img_title){
				return new WikipediaImage($img_title);
			}

		}

	}

	////////////
	// Static //
	////////////

	public static function extract_article_title_from_url($article_url){

		$parts = explode('/wiki/', $article_url);

		if (count($parts) == 2){
			return urldecode($parts[1]);
		}

	}

}