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

// Accused Gender
$context['female_count'] = count_in_term('accused', 'accused-gender', 'female');
$context['male_count'] = count_in_term('accused', 'accused-gender', 'male');

// Parties
$context['republicans'] = count_in_term('accused', 'political-party', 'republican');
$context['democracts'] = count_in_term('accused', 'political-party', 'democract');

// Victim Gender
$context['female_victim'] = count_in_term('accused', 'victim-gender', 'female');
$context['male_victim'] = count_in_term('accused', 'victim-gender', 'male');

Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );