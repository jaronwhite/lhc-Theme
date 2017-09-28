<?php

class LHC_Shortcodes {
	//Portfolio
	function portfolio( $atts ) {
		$a     = shortcode_atts( array(), $atts );
		$args  = array( 'post_type' => 'portfolio', 'posts_per_page' => 3 );
		$loop  = new WP_Query( $args );
		$stuff = '<article id="portfolio-container" class="wide-article">';
		while ( $loop->have_posts() ) : $loop->the_post();
			$t     = "T";
			$stuff .= '<div class="portfolio">
                <div class="portfolio-image" style="background: url(' . get_the_post_thumbnail_url() . ') center no-repeat;
                background-size: cover;"></div>
                <div class="portfolio-content-wrap">
                    <h3 class="portfolio-title">' . get_the_title() . '</h3>';
			if ( has_excerpt() ) {
				$stuff .= '<p class="portfolio-desc">' . get_the_excerpt() . '</p>';
			}
			$stuff .= '</div></div>';

		endwhile;
		$stuff .= "</article>";

		return $stuff;
	}

//Services
	function services( $atts ) {
		$a     = shortcode_atts( array(), $atts );
		$args  = array( 'post_type' => 'services', 'posts_per_page' => 4 );
		$loop  = new WP_Query( $args );
		$stuff = '<article id="services" class="wide-article">';
		while ( $loop->have_posts() ) : $loop->the_post();

			$stuff .= '
            <div class="service-post">
                <a href="#" class="service-image" style="background: url(' . get_the_post_thumbnail_url() . ') no-repeat
                center; background-size: cover"></a>
                <div class="service-content-wrap">
                    <h3 class="service-title">' . get_the_title() . '</h3>
                    <p class="service-desc">' . get_the_content() . '</p>
                    <a href="#" class="service-page-link">Learn More &rightarrow;</a>
                </div>
            </div>';

		endwhile;
		$stuff .= '</article>';

		return $stuff;
	}

//Gallery
	function gallery( $atts ) {
		$a     = shortcode_atts( array(), $atts );
		$args  = array( 'post_type' => 'portfolio', 'posts_per_page' => 6 );
		$loop  = new WP_Query( $args );
		$stuff = '';
		while ( $loop->have_posts() ) : $loop->the_post();

			$stuff .= get_the_title() . get_the_content();

		endwhile;

		return $stuff;
	}

	//Event Slider -- should be added to plugins folder
	function event_slider_shortcode() {
		return '<article id="event-slider" class="wide-article">
            <div class="event">
                <div class="event-content-wrap">
                    <h2 class="event-title">Event Title</h2>
                    <p class="event-desc">Event description. <br/>October 1, 2017 @ 5:00PM</p>
                </div>
            </div>
        </article>';
	}
}