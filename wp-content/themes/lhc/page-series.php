<?php
/*
Template Name: Current Series
*/
get_header();

$seriesArgs      = array(
	'post_type'      => 'series',
	'posts_per_page' => 1,
);
$seriesLoop      = new WP_Query( $seriesArgs );
$seriesLoopCount = 0;

$seriesPostArgs      = array(
	'post_type'      => 'series_post',
	'posts_per_page' => 9,
);
$seriesPostLoop      = new WP_Query( $seriesPostArgs );
$seriesPostLoopCount = 0;

if ( have_posts() ) {
	echo '<section class="content-section">';
	while ( have_posts() ) {
		the_post();
		echo '<h2>page-series</h2>';
		the_title( '<h2 class="section-title lhc-top-title">', '</h2>' );
		the_content();
		?>
		<section id="series-wrap">
			<h1 class="series-title">Series Title</h1>
			<div class="series-image">series image</div><div class="series-desc">series desc</div>
			<div id="series-post-wrap">
				<article class="series-post">
					<h2 class="series-post-title">series post title</h2>
					<p class="series-post-desc">series post desc</p>
				</article>
				<article class="series-post">
					<h2 class="series-post-title">series post title</h2>
					<p class="series-post-desc">series post desc</p>
				</article>
			</div>
		</section>
		<?php
		if ( $seriesLoop->have_posts() ) {
			while ( $seriesLoop->have_posts() ) {
				$seriesLoop->the_post();
				the_title( '<h2>', '</h2>' );
				the_content();
				if ( $seriesPostLoop->have_posts() ) {
					while ( $seriesPostLoop->have_posts() ) {
						$seriesPostLoop->the_post();
						the_title('<h3>','</h3>');
						the_excerpt();
					}
				}
			}
		}
	}
	echo '</section>';
}

get_sidebar();

get_footer();

/*

		$args      = array(
			'post_type'      => 'event',
			'posts_per_page' => 10,
			'meta_key'       => 'eventDate',
			'meta_value'     => date( "Ymd", ( time() - $_COOKIE['rgmTz'] ) ),
			'meta_compare'   => '>=',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC'
		);
		$loop      = new WP_Query( $args );
		$loopCount = 0;
		$events    = '';
		$controls  = '';
		if ( $loop->have_posts() ) {

			$events   .= '<article id="event-slider" class="wide-article">'
			             . '<div id="event-slider-bg"></div>';
			$controls .= '<div id="controls">';

			while ( $loop->have_posts() ) {
				$loop->the_post();
				$activeClass = ( $loopCount == 0 ) ? 'active' : '';
				$postTitle   = get_the_title();
				$postExcerpt = get_the_excerpt();
				$postURL     = get_the_guid();
				$imageURL    = get_the_post_thumbnail_url();
				$time        = get_post_meta( get_the_ID(), 'eventTime', true );
				$date        = DateTime::createFromFormat(
					'Ymd',
					get_post_meta( get_the_ID(), 'eventDate', true )
				)->format( 'F j, Y' );

				$events .= '<div class="event '
				           . $activeClass
				           . '" style="background:url('
				           . $imageURL
				           . ') no-repeat center; background-size: cover;">';
				$events .= '<a href="' . $postURL . '">';
				$events .= '<div class="event-content-wrap">';
				$events .= '<div class="event-content-inner">';
				$events .= '<h2 class="event-title">' . $postTitle . '</h2>';
				$events .= '<p class="event-desc">' . $postExcerpt
				           . '<br/>'
				           . $date
				           . ' @ '
				           . $time
				           . '</p>';
				$events .= '</div>';
				$events .= '</div>';
				$events .= '</a>';
				$events .= '</div>';

				$controls .= '<span class="' . $activeClass . '"></span>';

				$loopCount ++;
			}
			$controls .= '</div>';
			$events   .= $controls . '</article>';
		}

		return $events;
	}
 */
