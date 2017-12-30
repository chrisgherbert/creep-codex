<?php

namespace Content\Export;
use League\Csv\Writer;

class AccusedCsvCreator {

	public $export_path;

	public function __construct($export_path){

		$this->export_path = $export_path;

	}

	public function write_to_csv(){

		// Get data
		$data = $this->create_data_array();

		$csv = Writer::createFromPath($this->export_path, "w");

		// Get header row & add it to the CSV
		$header_row = array_keys($data[0]);
		$csv->insertOne($header_row);

		// Insert everything else
		$csv->insertAll($data);

		echo "Wrote " . count($data) . " records to " . $this->export_path;

	}

	public function create_data_array(){

		// Get all profiles
		$profiles = \Timber::get_posts(array(
			'post_type' => 'accused',
			'posts_per_page' => -1
		));

		$data = array();

		foreach ($profiles as $profile){

			$data[] = $this->create_data_item($profile);

		}

		return $data;

	}

	public function create_data_item($profile){

		$data = array(
			'Full Name' => $profile->post_title,
			'Last Name' => $profile->meta('last_name'),
			'Gender' => $profile->terms('accused-gender')[0] ?? '',
			'AKA' => $profile->meta('aka'),
			'Wikipedia Profile URL' => $profile->meta('wikipedia_url'),
			'TMDB Profile URL' => $profile->meta('tmdb_url'),
			'Alleged Offenses Summary' => $profile->meta('allegation_short'),
			'Profile Text' => strip_tags($profile->post_content),
			'Industries' => html_entity_decode(implode(', ', $profile->terms('industry'))),
			'Alleged Victim Genders' => html_entity_decode(implode(', ', $profile->terms('victim-gender'))),
			'Political Parties' => html_entity_decode(implode(', ', $profile->terms('political-party'))),
			'Source Link 1' => get_field('external_source_urls', $profile->ID)[0]['link_url'] ?? '',
			'Source Link 2' => get_field('external_source_urls', $profile->ID)[1]['link_url'] ?? '',
			'Source Link 3' => get_field('external_source_urls', $profile->ID)[2]['link_url'] ?? '',
			'Source Link 4' => get_field('external_source_urls', $profile->ID)[3]['link_url'] ?? '',
			'Source Link 5' => get_field('external_source_urls', $profile->ID)[4]['link_url'] ?? '',
			'Source Link 6' => get_field('external_source_urls', $profile->ID)[5]['link_url'] ?? '',
			'Source Link 7' => get_field('external_source_urls', $profile->ID)[6]['link_url'] ?? '',
			'Source Link 8' => get_field('external_source_urls', $profile->ID)[7]['link_url'] ?? ''
		);

		if ($profile->thumbnail()) {
			$data['Image URL'] = $profile->thumbnail()->src('full') ?? '';
		}

		if ($profile->thumbnail()) {
			$data['Image Attribution String'] = $profile->thumbnail()->caption() ?? '';
		}

		return $data;

	}

}