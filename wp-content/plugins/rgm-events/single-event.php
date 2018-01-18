<?php
/**
 * Created by PhpStorm.
 * User: jaronwhite
 * Date: 12/18/2017
 * Time: 11:33 PM
 */

get_header();
if ( have_posts() ) :

	echo '<section class="content-section event-section">';
	while ( have_posts() ) :
		the_post();

		$location  = get_post_meta( $post->ID, 'location', true );
		$addMap    = get_post_meta( $post->ID, 'addMap', true );
		$eventDate = get_post_meta( $post->ID, 'eventDate', true );
		$eventTime = get_post_meta( $post->ID, 'eventTime', true );
		$mapURL    = esc_url( 'https://www.google.com/maps/embed/v1/place?q=' . $location
		                      . '&key=AIzaSyDVj8soWjeyWleFV7NVvIRjGboGqC8RARA' );

		the_title( '<h2 class="section-title lhc-top-title">', '</h2>' );
		echo '<p><strong>When: </strong>' . $eventDate . ' @ ' . $eventTime . '</p>';
		echo '<p><strong>Where: </strong>' . $location . '</p>';
		if ( $addMap ) {
			echo '<iframe class="rgm-events-map" src="'
			     . $mapURL
			     . '"  frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
		}
		the_content();
	endwhile;
	echo '</section>';
endif;
get_sidebar();
get_footer();

