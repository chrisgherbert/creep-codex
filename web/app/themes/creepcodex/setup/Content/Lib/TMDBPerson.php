<?php

namespace Content\Lib;
use Tmdb\Client;
use Tmdb\ApiToken;

class TMDBPerson {

	public $id;
	public $api_obj;
	protected $combined_credits;

	public function __construct($id){
		$this->id = $id;
		$this->api_obj = $this->create_api_obj();
	}

	public function get_combined_credits(){

		if (isset($this->combined_credits)){
			return $this->combined_credits;
		}

		// Check for transient
		if ($data = get_transient($this->get_transient_key())){
			return $data;
		}

		// Get data from TMDB API
		$data = $this->api_obj->getCombinedCredits($this->id);

		// Set transient using lifetime set in environment variable
		set_transient($this->get_transient_key(), $data, getenv('TMDB_CACHE_LIFETIME'));

		$this->combined_credits = $data;

		return $this->combined_credits;

	}

	public function get_processed_cast_credits(){

		$raw_credits = $this->get_combined_credits();

		$raw_cast = $raw_credits['cast'] ?? false;

		$processed_cast = array_map(function($item){

			return array(
				'title' => $item['title'] ?? $item['name'],
				'id' => $item['id'] ?? '',
				'role' => $item['character'] ?? '',
				'media_type' => $item['media_type'] ?? '',
				'release_date' => $item['release_date'] ?? $item['first_air_date'] ?? '',
				'tmdb_url' => self::create_tmdb_credit_url($item['id'], $item['media_type']),
				'poster_thumbnail' => self::get_poster_image_url_small($item['poster_path']),
				'episode_count' => $item['episode_count'] ?? ''
			);

		}, $raw_cast);

		$processed_cast = $this->order_processed_credits_by_date($processed_cast);

		return $processed_cast;

	}

	public function get_processed_crew_credits(){

		$raw_credits = $this->get_combined_credits();

		$raw_crew = $raw_credits['crew'] ?? false;

		$processed_crew = array_map(function($item){

			return array(
				'title' => $item['title'] ?? $item['name'],
				'id' => $item['id'] ?? '',
				'role' => $item['job'] ?? '',
				'media_type' => $item['media_type'] ?? '',
				'release_date' => $item['release_date'] ?? $item['first_air_date'] ?? '',
				'tmdb_url' => self::create_tmdb_credit_url($item['id'], $item['media_type']),
				'poster_thumbnail' => self::get_poster_image_url_small($item['poster_path']),
				'episode_count' => $item['episode_count'] ?? ''
			);

		}, $raw_crew);

		$processed_crew = $this->order_processed_credits_by_date($processed_crew);

		return $processed_crew;

	}

	///////////////
	// Protected //
	///////////////

	protected function order_processed_credits_by_date($credits){

		usort($credits, function($a, $b){

			if ($a['release_date'] == $b['release_date']){
				return 0;
			}

			return ($a['release_date'] < $b['release_date']) ? -1 : 1;

		});

		$credits = array_reverse($credits);

		return $credits;

	}

	protected function create_api_obj(){

		$key = getenv('TMDB_API_KEY');
		$token = new ApiToken($key);
		$client = new Client($token);
		$api_obj = $client->getPeopleApi();

		return $api_obj;

	}

	protected function get_transient_key(){
		return 'tmdbperson_' . $this->id;
	}

	////////////
	// Static //
	////////////

	protected static function get_poster_image_url_small($poster_path = false){
		if ($poster_path){
			return "https://image.tmdb.org/t/p/w58_and_h87_bestv2$poster_path";
		}
	}

	protected static function get_poster_image_url_original($poster_path = false){
		if ($poster_path){
			return "https://image.tmdb.org/t/p/original$poster_path";
		}
	}

	protected static function create_tmdb_credit_url($id, $media_type){
		return "https://www.themoviedb.org/$media_type/$id";
	}

	public static function extract_id_from_url($url){

		$basename = basename($url);

		// Isolate the ID
		$parts = explode('-', $basename);

		return $parts[0] ?? false;

	}

	/////////////
	// Factory //
	/////////////

	public static function get_by_url($url){

		$id = self::extract_id_from_url($url);

		return new self($id);

	}

}