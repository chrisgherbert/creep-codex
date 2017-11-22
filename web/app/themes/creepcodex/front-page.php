<?php
/**
 *  Template Name: Home Page
 */

$context = Timber::get_context();

// Set special OG tags for the home page
$context['open_graph'] = array(
	array(
		'key' => 'og:title',
		'value' => get_option('blogname'),
	),
	array(
		'key' => 'og:url',
		'value' => get_option('home'),
	),
	array(
		'key' => 'og:description',
		'value' => get_option('blogdescription'),
	),
);

// Pull in current posts
$context['recent_accused'] = Timber::get_posts(
	array(
		'post_type' => 'accused',
		'posts_per_page' => 100
	),
	Content\Config::post_type_classes()
);

Timber::render( 'front-page.twig', $context, false, TimberLoader::CACHE_NONE );
