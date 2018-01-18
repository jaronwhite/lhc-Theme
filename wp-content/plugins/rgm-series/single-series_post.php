<?php get_header();

if ( have_posts() ) :
	echo '<section class="content-section">';
	while ( have_posts() ) :
		the_post();

		$series          = get_post( get_post_meta( $post->ID, 'serieslist', true ) );
		$seriesPostArgs  = array(
			'posts_per_page'   => 4,
			'exclude'          => $post->ID,
			'post_type'        => 'series_post',
			'meta_key'         => 'serieslist',
			'meta_value'       => $series->ID,
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$seriesPostArray = get_posts( $seriesPostArgs );

		$videoID = null;
		if ( get_post_format() == "video" && get_post_meta( $post->ID, 'media', true ) ) {
			$videoID = explode( "v=", get_post_meta( $post->ID, 'media', true ) )[1];
		}

		the_title( '<h2 class="section-title lhc-top-title">', '</h2>' );

		?>
        <article class="series-post-wrap">

			<?php
			if ( has_post_format() || has_post_thumbnail() ) {
				?>
                <div class="series-post-col series-post-media">
					<?php
					if ( has_post_format() && get_post_format() == 'video' ) {
						?>
                        <div class="series-video-wrap">
                            <iframe width="560" height="315"
                                    src="https://www.youtube.com/embed/<?php echo $videoID; ?>?rel=0"
                                    frameborder="0"
                                    gesture="media" allow="encrypted-media" allowfullscreen=""></iframe>
                        </div>
						<?php
					} elseif ( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium' );
					}
					?>
                </div>
				<?php
			}
			?>
            <div class="series-post-col  series-post-content">
				<?php the_content(); ?>
            </div>
        </article>

		<?php if ( sizeof( $seriesPostArray ) > 0 ) { ?>
        <article class="series-more-wrap">
            <h2 class="series-more-wrap-title">
                <a href="<?php echo get_post_permalink( $series->ID ); ?>">
                    More in the <?php echo $series->post_title; ?> series.
                </a>
            </h2>
            <div class="series-more-posts">
				<?php

				foreach ( $seriesPostArray as $p ) {
					?>
                    <div class="series-more-post">
                        <a href="<?php echo get_the_permalink( $p->ID ); ?>">
                            <div class="series-more-img">
								<?php
								if ( has_post_thumbnail( $p->ID ) ) {
									echo get_the_post_thumbnail( $p->ID, 'medium' );
								} elseif ( has_post_thumbnail( $series->ID ) ) {
									echo get_the_post_thumbnail( $series->ID, 'medium' );
								}
								?>
                            </div>
                            <div class="series-more-content">
                                <h3 class="series-more-title">
									<?php echo $p->post_title; ?>
                                </h3>
                                <p class="series-more-desc">
									<?php echo $p->post_content; ?>
                                </p>
                            </div>
                        </a>
                    </div>
					<?php
				}
				?>
            </div>
        </article>
		<?php
	}
	endwhile;
	echo '</section>';
endif;

get_sidebar();

get_footer();