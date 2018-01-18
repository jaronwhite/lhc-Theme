<?php get_header();

if ( have_posts() ) :
	echo '<section class="content-section">';
	echo '<h2 class="section-title lhc-top-title">' . get_the_title( get_option( 'page_for_posts', true ) ) . '</h2>';
	while ( have_posts() ) :
		the_post();
		the_title( '<h2 class="blog-post-title">', '</h2>' );
		the_excerpt();
	endwhile;
	echo '</section>';
endif;

get_sidebar();

get_footer(); ?>
