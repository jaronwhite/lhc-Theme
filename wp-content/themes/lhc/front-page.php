<?php get_header();

if ( have_posts() ) :
	echo '<section class="content-section">';
	while ( have_posts() ) :
		the_post();
		the_title( '<h2 class="section-title lhc-top-title">', '</h2>' );
		the_content();
	endwhile;
	echo '</section>';

	$postId = get_theme_mod( 'page_layout' );
	if ( $postId ) {
		$post    = get_post( $postId );
		$title   = $post->post_title;
		$content = apply_filters( 'the_content', $post->post_content );
		echo '<section id="" class="content-section">
        <h2 class="section-title">' . $title . '</h2>' . $content . '</section>';
	}
endif;

get_sidebar();

get_footer(); ?>
