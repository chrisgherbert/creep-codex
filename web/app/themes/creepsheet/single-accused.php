<?php
/**
 * The Template for displaying all single posts
 */

$context = Timber::get_context();
$post = Timber::get_post($post->ID, Content\Config::post_type_classes());
$context['post'] = $post;

// Schema
$context['schema'] = $post->get_schema();
// Disable Yoast SEO's default schema data
add_filter( 'wpseo_json_ld_output', '__return_false' );

// Open Graph
$context['open_graph'] = $post->get_open_graph_data();
// Twitter Cards
$context['twitter_cards'] = $post->get_twitter_card_data();
// Meta description
$context['meta_description'] = $post->get_meta_description();

$context['more_accused'] = Timber::get_posts(
	array(
		'post_type' => 'accused',
		'posts_per_page' => 5,
		'orderby' => 'rand'
	),
	Content\Config::post_type_classes()
);

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );
}
