<?php

/**
 * Initialize the plugin
 */
class SPluginInit {
	function __construct() {
		$file = SConfig::get( "pluginFile" );

		//On plugin activation && deactivation
		register_activation_hook( $file, array( $this, 'activate' ) );
		register_deactivation_hook( $file, array( $this, 'deactivate' ) );

		//Add menu page
		add_action( 'init', array( $this, 'buildSeriesPostType' ) );
		add_action( 'admin_init', array( $this, 'seriesMetaBoxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 999 );
		add_filter( 'single_template', array( $this, 'customPostTemplate' ) );
		/*



		add_action( 'admin_menu', array( $this, 'addMenuPage' ) );
		add_shortcode( 'rgm-event', array( $this, 'addShortCode' ) );
		add_action( 'admin_head', 'getTz' );
		function getTz() {
			echo '<script type="text/javascript">'
			     . 'var rgmTz = (new Date().getTimezoneOffset())*60;'
			     . 'document.cookie = "rgmTz="+rgmTz;'
			     . '</script>';
		}*/
	}

	/*
		public function addMenuPage() {
			add_menu_page(
				'rgm-events',
				'RGM Events',
				'manage_options',
				__FILE__, array(
				new AdminPage(),
				'renderAdminPage'
			),
				'dashicons-calendar-alt'
			);
		}


		public function addShortCode( $atts ) {
			$a         = shortcode_atts( array(), $atts );
			$dateQ     = date( "Ymd", ( time() - $_COOKIE['rgmTz'] ) );
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
	public function activate() {
		$this->buildSeriesPostType();
	}

	public function deactivate() {
		unregister_post_type( 'series' );
		unregister_post_type( 'series-post' );
		flush_rewrite_rules();
	}

	public function enqueue() {
		global $post;
		//JavaScript files
		wp_register_script( 'series-scripts', plugins_url( 'js/series-scripts.js', __FILE__ ), array( 'jquery' ), true, true );
		wp_enqueue_script( 'series-scripts' );

		//Style Sheets
		if ( $post->post_type == "series_post" ) {
			wp_register_style( 'series-post-styles', plugins_url( 'css/series-post-styles.css', __FILE__ ), array(), true );
			wp_enqueue_style( 'series-post-styles' );
		}

		if ( $post->post_type == "series" ) {
			wp_register_style( 'series-styles', plugins_url( 'css/series-styles.css', __FILE__ ), array(), true );
			wp_enqueue_style( 'series-styles' );
		}
	}

	public function adminEnqueue( $hook ) {
		global $post;
		//		if ( 'toplevel_page_bkmrco/PluginInit' != $hook ) :
		//			return;
		//		endif;


		//Admin JavaScript Files
		wp_enqueue_media();
		wp_register_script( 'series-admin-scripts', plugins_url( 'js/series-admin-scripts.js', __FILE__ ), array(
			'jquery',
		), true, true );
		wp_enqueue_script( 'series-admin-scripts' );


		//Admin Style Sheets
		wp_register_style( 'series-admin-styles', plugins_url( 'css/series-admin-styles.css', __FILE__ ), array(), true );
		wp_enqueue_style( 'series-admin-styles' );

	}

	function buildSeriesPostType() {
		$seriesName        = 'series';
		$SeriesName        = ucwords( $seriesName );
		$seriesLabels      = array(
			'name'               => __( $SeriesName ),
			'singular_name'      => __( $SeriesName ),
			'add_new'            => __( 'Add New ' . $SeriesName ),
			'add_new_item'       => __( 'Add New ' . $SeriesName ),
			'edit_item'          => __( 'Edit ' . $SeriesName ),
			'new_item'           => __( 'Add New ' . $SeriesName ),
			'view_item'          => __( 'View ' . $SeriesName ),
			'search_items'       => __( 'Search ' . $SeriesName ),
			'not_found'          => __( 'No ' . $seriesName . ' found' ),
			'not_found_in_trash' => __( 'No ' . $seriesName . ' found in trash' )
		);
		$seriesSupport     = array(
			'title',
			'editor',
			'thumbnail',
			'comments',
			'excerpt',
			'revisions',
		);
		$seriesArgs        = array(
			'labels'            => $seriesLabels,
			'supports'          => $seriesSupport,
			'public'            => true,
			'has_archive'       => true,
			'menu_icon'         => 'dashicons-admin-post',
			'show_ui'           => true,
			'show_in_menu'      => true,
			'menu_position'     => 25,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'capability_type'   => 'post',
		);
		$seriesPostName    = 'series post';
		$SeriesPostName    = ucwords( $seriesPostName );
		$seriesPostId      = str_replace( ' ', '_', $seriesPostName );
		$seriesPostLabels  = array(
			'name'               => __( $SeriesPostName . 's' ),
			'singular_name'      => __( $SeriesPostName ),
			'add_new'            => __( 'Add New ' . $SeriesPostName ),
			'add_new_item'       => __( 'Add New ' . $SeriesPostName ),
			'edit_item'          => __( 'Edit ' . $SeriesPostName ),
			'new_item'           => __( 'Add New ' . $SeriesPostName ),
			'view_item'          => __( 'View ' . $SeriesPostName ),
			'search_items'       => __( 'Search ' . $SeriesPostName ),
			'not_found'          => __( 'No ' . $seriesPostName . 's found' ),
			'not_found_in_trash' => __( 'No ' . $seriesPostName . 's found in trash' )
		);
		$seriesPostSupport = array(
			'title',
			'editor',
			'thumbnail',
			'comments',
			'excerpt',
			'revisions',
			'post-formats'
		);
		$seriesPostArgs    = array(
			'labels'            => $seriesPostLabels,
			'supports'          => $seriesPostSupport,
			'public'            => true,
			'has_archive'       => false,
			'menu_icon'         => 'dashicons-admin-post',
			'show_ui'           => true,
			'show_in_menu'      => true,
			'menu_position'     => 25,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'capability_type'   => 'post',
		);

		register_post_type( $seriesName, $seriesArgs );
		register_post_type( $seriesPostId, $seriesPostArgs );
		flush_rewrite_rules();
	}

	function seriesMetaBoxes() {
		add_meta_box(
			'rgm_series_media',
			'Media URL',
			'rgm_series_media',
			'series_post',
			'side',
			'core'
		);
		function rgm_series_media() {
			global $post;
			wp_nonce_field( basename( __FILE__ ), 'series_media' );

			$mediaURL = get_post_meta( $post->ID, 'media', true );
			echo '<input type="text" name="media" value="'
			     . esc_textarea( $mediaURL )
			     . '" class="widefat"/>';
		}

		add_action( 'save_post', 'wpt_save_events_meta', 1, 2 );
		function wpt_save_series_meta( $post_id, $post ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['series_media'] ) || ! wp_verify_nonce( $_POST['series_media'], basename( __FILE__ ) ) ) {
				return;
			}


			$events_meta['media'] = sanitize_text_field( $_POST['media'] );

			foreach ( $events_meta as $key => $value ) {
				if ( 'revision' === $post->post_type ) {
					return;
				}
				if ( get_post_meta( $post_id, $key, false ) ) {
					update_post_meta( $post_id, $key, $value );
				} else {
					add_post_meta( $post_id, $key, $value );
				}
				if ( ! $value ) {
					delete_post_meta( $post_id, $key );
				}
			}
		}

		add_meta_box(
			'rgm_series_list',
			'Series',
			'rgm_series_list',
			'series_post',
			'side',
			'core'
		);
		function rgm_series_list() {
			global $post;
			wp_nonce_field( basename( __FILE__ ), 'series_list' );

			$args        = array(
				'posts_per_page'   => - 1,
				'post_type'        => 'series',
				'post_status'      => array(
					'publish',
					'future'
				),
				'suppress_filters' => true
			);
			$posts_array = get_posts( $args );

			$selectedSeries = get_post_meta( $post->ID, 'serieslist', true );
			echo '<select name="serieslist" class="series-list widefat">';
			foreach ( $posts_array as $p ) {
				echo '<option value="' . $p->ID . '" ' . selected( esc_textarea( $selectedSeries ), $p->ID ) . '>'
				     . $p->post_title
				     . '</option>';
			}
			echo '</select>';
		}

		add_action( 'save_post', 'wpt_save_series_list', 1, 2 );
		function wpt_save_series_list( $post_id, $post ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['series_list'] ) || ! wp_verify_nonce( $_POST['series_list'], basename( __FILE__ ) ) ) {
				return;
			}


			$events_meta['serieslist'] = sanitize_text_field( $_POST['serieslist'] );

			foreach ( $events_meta as $key => $value ) {
				if ( 'revision' === $post->post_type ) {
					return;
				}
				if ( get_post_meta( $post_id, $key, false ) ) {
					update_post_meta( $post_id, $key, $value );
				} else {
					add_post_meta( $post_id, $key, $value );
				}
				if ( ! $value ) {
					delete_post_meta( $post_id, $key );
				}
			}
		}
	}

	//Add the custom post type template
	function customPostTemplate( $single ) {
		global $post;
		$dir = SConfig::get( "pluginDir" );

		( $post->post_type == "series" ) ? $single = $dir . '/single-series.php' : '';
		( $post->post_type == "series_post" ) ? $single = $dir . '/single-series_post.php' : '';

		return $single;
	}

	//Add the custom post type archive
	function customPostArchive() {
		global $post;
		$dir = SConfig::get( "pluginDir" );

		if ( $post->post_type == "series" ) {

		}

		if ( $post->post_type == "series_post" ) {

		}
	}
}