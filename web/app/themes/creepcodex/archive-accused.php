<?php
/**
 * The template for displaying the Accused Archive page.
 *
 */

$context = Timber::get_context();

$context['title'] = 'Archive';
if ( is_day() ) {
	$context['title'] = 'Archive: '.get_the_date( 'D M Y' );
} else if ( is_month() ) {
	$context['title'] = 'Archive: '.get_the_date( 'M Y' );
} else if ( is_year() ) {
	$context['title'] = 'Archive: '.get_the_date( 'Y' );
} else if ( is_tag() ) {
	$context['title'] = single_tag_title( '', false );
} else if ( is_category() ) {
	$context['title'] = single_cat_title( '', false );
} else if ( is_post_type_archive() ) {
	$context['title'] = post_type_archive_title( '', false );
}

$context['industries'] = Timber::get_terms('industry');
$context['orders'] = array(
	'name' => 'Name',
	'date' => 'Date Added'
);

$context['query']['order'] = $_REQUEST['order'] ?? false;
$context['query']['orderby'] = $_REQUEST['orderby'] ?? false;
$context['query']['industry'] = $_REQUEST['industry'] ?? false;

$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();

Timber::render( 'archive-accused.twig', $context );
