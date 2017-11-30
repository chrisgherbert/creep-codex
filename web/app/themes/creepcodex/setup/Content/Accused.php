<?php

namespace Content;

use Embed\Embed;

class Accused extends Post {

	public function get_schema(){

		$data = array(
			'@context' => 'http://schema.org/',
			'@type' => 'Person',
			'name' => $this->post_title
		);

		if ($image = $this->thumbnail()){
			$data['image'] = $image->src('large');
		}

		if ($this->meta('job_title')){
			$data['jobTitle'] = $this->meta('job_title');
		}

		if ($this->meta('wikipedia_url')){
			$data['sameAs'] = $this->meta('wikipedia_url');
		}

		if ($this->meta('allegation_short')){
			$data['description'] = 'Allegation summary: ' . $this->meta('allegation_short');
		}
		else if ($this->content()){
			$data['description'] = $this->content();
		}

		return $data;

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