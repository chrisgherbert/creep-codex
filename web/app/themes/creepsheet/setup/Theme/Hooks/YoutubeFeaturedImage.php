<?php
/**
 *
 */

namespace Theme\Hooks;

use bermanco\YouTubeVideo\YouTubeVideo;
use bermanco\WordpressImageDownload\WordpressImageDownload;

class YoutubeFeaturedImage {

	public function __construct(){
		add_action('updated_post_meta', array($this, 'set_featured_image'), 10, 4);
		add_action('added_post_meta', array($this, 'set_featured_image'), 10, 4);
	}

	public function set_featured_image($meta_id, $post_id, $meta_key, $meta_value){

		if (!class_exists('bermanco\YouTubeVideo\YouTubeVideo') || !class_exists('bermanco\WordpressImageDownload\WordpressImageDownload')){
			error_log('Did you forget to run Composer?');
			return false;
		}

		if ($meta_key == 'featured_video_url' && $meta_value && !has_post_thumbnail($post_id)){

			$api_key = getenv('YOUTUBE_API_KEY');

			$yt_video = YouTubeVideo::create($api_key, $meta_value);

			$largest_thumbnail = $yt_video->get_largest_thumbnail_url();

			if ($largest_thumbnail){

				$downloader = new WordpressImageDownload($largest_thumbnail);

				$attachment_id = $downloader->create_media_attachment();

				if ($attachment_id){
					return set_post_thumbnail($post_id, $attachment_id);
				}

			}

		}

	}

}