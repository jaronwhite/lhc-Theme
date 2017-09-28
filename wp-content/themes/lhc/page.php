<?php get_header();

if ( have_posts() ) :
	echo '<section class="content-section">';
	while ( have_posts() ) :
		the_title( '<h2 class="section-title">', '</h2>' );
		the_post();
		the_content();
	endwhile;
	echo '</section>';
endif;

get_sidebar();

get_footer(); ?>
