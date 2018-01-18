<?php get_header();

if ( have_posts() ) :
	echo '<section class="content-section">';
	while ( have_posts() ) :
		$seriesPostArgs = array(
			'posts_per_page'   => - 1,
			'post_type'        => 'series_post',
			'meta_key'         => 'serieslist',
			'meta_value'       => $post->ID,
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$seriesPosts = get_posts( $seriesPostArgs );

		$otherSeriesArgs = array(
			'posts_per_page'   => 4,
			'post_type'        => 'series',
			'exclude'          => $post->ID,
			'post_status'      => 'publish',
			'suppress_filters' => true
		);
		$otherSeries     = get_posts( $otherSeriesArgs );

		the_post();
		the_title( '<h2 class="section-title lhc-top-title">', '</h2>' );
		?>

        <article class="series-wrap">
            <!--HAS MEDIA-->
            <div class="series-col series-media">
                <div class="series-media-wrap">
                    <!--HAS IMAGE (STYLE-TAG ONLY)-->
                    <div class="media-wrap"
                         style="background:url(<?php the_post_thumbnail_url(); ?>) center no-repeat;background-size: cover">
                        <!--END HAS IMAGE-->
                        <!--HAS AUDIO (ONLY SERIES_POST)-->
						<?php the_post_thumbnail_url(); ?>
                        <!--END HAS AUDIO-->
                    </div>
                    <!--HAS VIDEO (ONLY SERIES_POST)
                <iframe width="560" height="315"
                        src="https://www.youtube.com/embed/<?php /*echo $videoID; */
					?>?rel=0"
                        frameborder="0"
                        gesture="media" allow="encrypted-media" allowfullscreen="">
                </iframe>
                <!--END HAS VIDEO-->
                </div>
                <!--END HAS MEDIA-->
            </div>
            <div class="series-col series-content">
				<?php the_content(); ?>
            </div>
        </article>

        <!--POSTS IN THIS SERIES-->
        <article class="series-posts">
            <h2 class="series-posts-title">In This Series</h2>
			<?php
			foreach ( $seriesPosts as $sp ) {
				$spId = $sp->ID;
				( has_post_thumbnail( $spId ) ) ? $spBg = 'background:url('
				                                          . esc_url( get_the_post_thumbnail_url( $spId, 'medium_large' ) )
				                                          . ') center no-repeat;background-size:cover;' :
					( has_post_thumbnail( $series ) ) ? $spBg = 'background:url('
					                                            . esc_url( get_the_post_thumbnail_url( $series, 'medium_large' ) )
					                                            . ') center no-repeat;background-size:cover;' :
						$spBg = 'background: #cccccc;';

				$spStyle = esc_attr( $spBg );
				$spLink  = esc_url( get_the_permalink( $spId ) );
				?>
                <div class="series-post">
                    <a href="<?php echo $spLink; ?>">
                        <div class="series-post-img" style="<?php echo $spStyle; ?>">
                        </div>
                        <div class="series-post-content">
                            <h3 class="series-post-title"><?php echo $sp->post_title; ?></h3>
							<?php if ( has_excerpt( $spId ) ) { ?>
                                <p class="series-post-desc"><?php echo $sp->post_excerpt; ?></p>
							<?php } ?>
                        </div>
                    </a>
                </div>
				<?php
			}
			?>
        </article>

        <!--Other Series-->
        <article class="other-series-wrap">
            <h2 class="other-series-wrap-title">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'series' ) ); ?>">Other Series</a>
            </h2>
			<?php
			foreach ( $otherSeries as $os ) {
				$osId    = $os->ID;
				$osBg    = ( has_post_thumbnail( $osId ) ) ? 'background:url('
				                                             . esc_url( get_the_post_thumbnail_url( $spId, 'medium' ) )
				                                             . ') center no-repeat;background-size:cover;' :
					'background: #cccccc;';
				$osStyle = esc_attr( $osBg );
				$osLink  = esc_url( get_the_permalink( $osId ) );
				?>
                <div class="other-series">
                    <a href="<?php echo $osLink; ?>">
                        <div class="other-series-img" style="<?php echo $osStyle; ?>">
                        </div>
                        <div class="other-series-content">
                            <h3 class="other-series-title"><?php echo $os->post_title; ?></h3>
                            <p class="other-series-desc"><?php echo $os->post_excerpt; ?></p>
                        </div>
                    </a>
                </div>
				<?php
			}
			?>
        </article>
		<?php
	endwhile;
	echo '</section>';
endif;

get_sidebar();

get_footer();