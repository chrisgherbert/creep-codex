<?php
/**
 * Template Name: By the Numbers
 */

$context = Timber::get_context();
$post = Timber::get_post($post->ID, 'bermanco\ExtendedTimberClasses\Post');

$context['post'] = $post;
$context['open_graph'] = $post->get_open_graph_data();

function count_in_term($post_type, $taxonomy, $term_slug){

	$query_obj = new WP_Query(array(
		'post_type' => $post_type,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => array($term_slug)
			)
		)
	));

	return $query_obj->found_posts;

}

// Stats

// Get the total count
$count_data = wp_count_posts('accused');

// Total
$context['total_count'] = $count_data->publish ?? 0;

// Accused Gender
$context['female_count'] = count_in_term('accused', 'accused-gender', 'female');
$context['male_count'] = $context['total_count'] - $context['female_count'];

// Parties
$context['republicans'] = count_in_term('accused', 'political-party', 'republican');
$context['democrats'] = count_in_term('accused', 'political-party', 'democrat');

// Victim Gender
$context['female_victim'] = count_in_term('accused', 'victim-gender', 'female');
$context['male_victim'] = count_in_term('accused', 'victim-gender', 'male');

// Poltical Parties
$context['political_parties'] = Timber::get_terms(array(
	'taxonomy' => 'political-party',
	'orderby' => 'count',
	'order' => 'desc'
));

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );