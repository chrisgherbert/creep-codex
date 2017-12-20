<?php

namespace Content;

use Embed\Embed;
use Content\Lib\TMDBPerson;

class Accused extends Post {

	public function get_schema(){

		$data = array(
			'@context' => 'http://schema.org/',
			'@type' => 'Person',
			'name' => $this->post_title
		);

		if ($image = $this->thumbnail()){
			$data['image'] = $image->src('full');
		}

		if ($this->meta('job_title')){
			$data['jobTitle'] = $this->meta('job_title');
		}

		if ($this->meta('wikipedia_url')){
			$data['sameAs'] = $this->meta('wikipedia_url');
		}

		if ($this->meta('allegation_short')){
			$data['description'] = 'Allegation summary: ' . strtolower($this->meta('allegation_short'));
		}
		else if ($this->content()){
			$data['description'] = $this->content();
		}

		return $data;

	}

	public function get_meta_description(){

		$text = $this->title();

		if ($job = $this->meta('job_title')){
			$job = strtolower($job);
			$text .= " ($job)";
		}

		if ($summary = $this->meta('allegation_short')){
			$summary = strtolower($summary);
			$text .= " was accused of $summary";
		}

		return $text;

	}

	public function get_tmdb_cast_credits(){

		if ($tmdb_url = $this->meta('tmdb_url')){

			$tmdb_data = TMDBPerson::get_by_url($tmdb_url);

			return $tmdb_data->get_processed_cast_credits();

		}

	}

	public function get_tmdb_crew_credits(){

		if ($tmdb_url = $this->meta('tmdb_url')){

			$tmdb_data = TMDBPerson::get_by_url($tmdb_url);

			return $tmdb_data->get_processed_crew_credits();

		}

	}

	public function get_open_graph_data(){

		$data = array(
			array(
				'key' => 'og:title',
				'value' => $this->get_page_title()
			),
			array(
				'key' => 'og:type',
				'value' => 'profile'
			),
			array(
				'key' => 'og:description',
				'value' => strip_tags($this->post_content) ?? $this->meta('allegation_short')
			),
			array(
				'key' => 'og:url',
				'value' => $this->link()
			)
		);

		if ($this->thumbnail()){
			$data[] = array(
				'key' => 'og:image',
				'value' => $this->thumbnail()->src('full')
			);
		}

		$data[] = array(
			'key' => 'og:image',
			'value' => get_template_directory_uri() . '/assets/img/open-graph-default.png'
		);

		return $data;

	}

	public function get_page_title(){

		if ($this->meta('allegation_short')){
			return $this->post_title . ' - Accused of ' . strtolower($this->meta('allegation_short'));
		}
		else {
			return $this->title();
		}

	}

	public function get_external_links(){

		$data = get_field('external_source_urls', $this->ID);

		if (!$data){
			return false;
		}

		$links = array();

		foreach ($data as $item){
			if ($item['link_url']){
				$links[] = $item['link_url'];
			}
		}

		return $links;

	}

	public function get_external_links_info(){

		$links = $this->get_external_links();

		if (!$links){
			return false;
		}

		$info = array();

		foreach ($links as $link){

			$info[] = $this->get_url_info($link);

		}

		return $info;

	}

	protected function get_url_info($url){

		$cache_key = 'urlinfo_' . md5($url);

		if (get_transient($cache_key) !== false){
			return get_transient($cache_key);
		}

		try {
			$data = Embed::create($url);
			set_transient($cache_key, $data, MONTH_IN_SECONDS); // Cache for 1 month
			return $data;
		} catch (\Exception $e) {
			error_log($e->getMessage());
			return array(
				'url' => $url
			);
		}

	}

}