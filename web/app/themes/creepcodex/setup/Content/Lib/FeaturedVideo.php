<?php
/**
 * Adds Featured Video functionality to a class
 * Assumes a custom field with the id 'featured_video_url' that
 * contains a valid YouTube URL
 */

namespace Content\Lib;

use bermanco\YouTubeVideo\YouTubeVideo;

trait FeaturedVideo
{
	protected $yt_object;

	public function get_featured_video_url()
	{
		return get_post_meta($this->ID, 'featured_video_url', true);
	}

	public function get_featured_video_embed()
	{
		$video = $this->get_featured_video();

		if ($video){
			return $video->get_embed();
		}
	}

	protected function get_featured_video()
	{
		if ($this->yt_object){
			return $this->yt_object;
		}

		$url = $this->get_featured_video_url();
		$api_key = getenv('YOUTUBE_API_KEY');

		if (!$url) {
			return false;
		}

		$this->yt_object = YouTubeVideo::create($api_key, $url);

		return $this->yt_object;
	}
}