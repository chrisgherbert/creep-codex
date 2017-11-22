<?php

namespace Theme\Hooks;

class ContentCreation {

	public function __construct(){

		add_action('updated_post_meta', array($this, 'wikipedia_featured_image_and_caption'), 10, 4);
		add_action('added_post_meta', array($this, 'wikipedia_featured_image_and_caption'), 10, 4);

	}

	public function wikipedia_featured_image_and_caption($meta_id, $post_id, $meta_key, $meta_value){

		if ($meta_key == 'wikipedia_url' && $meta_value){

			// If there's already a featured image set, stop
			if (has_post_thumbnail($post_id)){
				return false;
			}

			// Get the wikipedia image and attribution text
			$wiki_article = new \Content\Lib\WikipediaArticle($meta_value);

			$wiki_image = $wiki_article->get_article_image_object();

			// If no image present, stop
			if (!$wiki_image){
				return;
			}

			$image_credit = $wiki_image->get_image_attribution_string();

			$image_url = $wiki_image->get_image_url();

			if ($image_credit && $image_url){

				// Create attachment from image URL
				$wordress_img_creator = new \bermanco\WordpressImageDownload\WordpressImageDownload($image_url);

				$attachment_id = $wordress_img_creator->create_media_attachment();

				// Set featured image
				set_post_thumbnail($post_id, $attachment_id);

				// Set the caption (stored in the post_excerpt field)
				wp_update_post(array(
					'ID' => $attachment_id,
					'post_excerpt' => $image_credit
				));

			}

		}

	}

}