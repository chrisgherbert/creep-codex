<?php
/**
 *  Template Name: Home Page
 */

$context = Timber::get_context();

$context['schema'] = array(
	'@context' => 'http://schema.org',
	'@type' => 'WebSite',
	'url' => get_home_url(),
	'potentialAction' => array(
		'@type' => 'SearchAction',
		'target' => get_home_url() . '?s={search_term_string}',
		'query-input' => 'required name=search_term_string'
	)
);

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
	array(
		'key'  => 'og:image',
		'value' => get_template_directory_uri() . '/assets/img/open-graph-default.png'
	)
);

// Pull in current posts
$context['recent_accused'] = Timber::get_posts(
	array(
		'post_type' => 'accused',
		'posts_per_page' => 40
	),
	Content\Config::post_type_classes()
);

Timber::render( 'front-page.twig', $context, false, TimberLoader::CACHE_NONE );
