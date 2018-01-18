<?php


//Portfolio
function portfolio( $atts ) {
	$a = shortcode_atts( array(
		"posts" => array()
	), $atts );

	$posts  = explode( ",", $a['posts'] );
	$length = ( sizeof( $posts ) < 4 ) ? sizeof( $posts ) : 3;
	$stuff  = '<article id="portfolio-container">';

	for ( $i = 0; $i < $length; $i ++ ) {
		$postId = $posts[ $i ];
		$post   = get_post( $postId );
		$stuff  .= '<div class="portfolio">'
		           . '<div class="portfolio-image" style="background: url(' . get_the_post_thumbnail_url( $postId, "medium_large" ) . ') center no-repeat;
                background-size: cover;"></div>'
		           . '<a href="'
		           . get_the_permalink( $postId )
		           . '" class="portfolio-content-wrap">'
		           . '<h3 class="portfolio-title">' . $post->post_title . '</h3>';
		if ( has_excerpt() ) {
			$stuff .= '<p class="portfolio-desc">' . $post->post_excerpt . '</p>';
		}

		$stuff .= '</a></div>';
	}

	$stuff .= "</article>";

	return $stuff;

	//Add this if no posts in array
	/*while ( $loop->have_posts() ) : $loop->the_post();
		$stuff .= '<div class="portfolio">'
		          . '<div class="portfolio-image" style="background: url(' . get_the_post_thumbnail_url() . ') center no-repeat;
                background-size: cover;"></div>'
		          . '<a href="'
		          . get_permalink()
		          . '" class="portfolio-content-wrap">'
		          . '<h3 class="portfolio-title">' . get_the_title() . '</h3>';
		if ( has_excerpt() ) {
			$stuff .= '<p class="portfolio-desc">' . get_the_excerpt() . '</p>';
		}

		$stuff .= '</a></div>';

	endwhile;*/
}

//Services
function services( $atts ) {
	$a = shortcode_atts( array(
		"posts"     => array(),
		"link_text" => "Learn More"
	), $atts );
	/*$args  = array( 'post_type' => 'services', 'posts_per_page' => 4 );
	$loop  = new WP_Query( $args );*/
	$posts  = explode( ",", $a['posts'] );
	$length = ( sizeof( $posts ) < 5 ) ? sizeof( $posts ) : 4;
	$stuff  = '<article id="services">';

	for ( $i = 0; $i < $length; $i ++ ) {
		$postId = $posts[ $i ];
		$post   = get_post( $postId );
		$stuff  .= '<div class="service-post">'
		           . '<a href="'
		           . get_the_permalink( $postId )
		           . '" class="service-image" style="background: url('
		           . get_the_post_thumbnail_url( $postId, "medium_large" )
		           . ') no-repeat center; background-size: cover"></a>'
		           . '<div class="service-content-wrap">'
		           . '<h3 class="service-title">'
		           . $post->post_title
		           . '</h3><p class="service-desc">'
		           . $post->post_excerpt
		           . '</p>'
		           . '<a href="'
		           . get_the_permalink( $postId )
		           . '" class="service-page-link">' . $a['link_text'] . ' &rightarrow;</a>'
		           . '</div>'
		           . '</div>';
	}
	/*while ( $loop->have_posts() ) : $loop->the_post();

		$stuff .= '<div class="service-post">'
		          . '<a href="'
		          . get_permalink()
		          . '" class="service-image" style="background: url('
		          . get_the_post_thumbnail_url()
		          . ') no-repeat center; background-size: cover"></a>'
		          . '<div class="service-content-wrap">'
		          . '<h3 class="service-title">'
		          . get_the_title()
		          . '</h3><p class="service-desc">'
		          . get_the_content()
		          . '</p>'
		          . '<a href="'
		          . get_permalink()
		          . '" class="service-page-link">' . $a['link_text'] . ' &rightarrow;</a>'
		          . '</div>'
		          . '</div>';

	endwhile;*/
	$stuff .= '</article>';

	return $stuff;
}

//Gallery
/*function gallery( $atts ) {
	$a     = shortcode_atts( array(), $atts );
	$args  = array( 'post_type' => 'portfolio', 'posts_per_page' => 6 );
	$loop  = new WP_Query( $args );
	$stuff = '';
	while ( $loop->have_posts() ) : $loop->the_post();

		$stuff .= get_the_title() . get_the_content();

	endwhile;

	return $stuff;
}*/
