<?php

/**
 * Initialize the plugin
 */
class PluginInit {
	function __construct() {
		$file = SConfig::get( "pluginFile" );

		//On plugin activation && deactivation
		register_activation_hook( $file, array( $this, 'activate' ) );
		register_deactivation_hook( $file, array( $this, 'deactivate' ) );

		//Add menu page
		add_action( 'init', array( $this, 'buildEventPostType' ) );
		add_filter( 'single_template', array( $this, 'customPostTemplate' ) );
		add_action( 'admin_init', array( $this, 'eventMetaBoxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 999 );
		add_action( 'admin_menu', array( $this, 'addMenuPage' ) );
		add_shortcode( 'rgm-event', array( $this, 'addShortCode' ) );
		add_action( 'admin_head', 'getTz' );
		function getTz() {
			echo '<script type="text/javascript">'
			     . 'var rgmTz = (new Date().getTimezoneOffset())*60;'
			     . 'document.cookie = "rgmTz="+rgmTz;'
			     . '</script>';
		}
	}

	public function addMenuPage() {
		add_menu_page(
			'rgm-events',
			'RGM Events',
			'manage_options',
			__FILE__, array(
			new SAdminPage(),
			'renderAdminPage'
		),
			'dashicons-calendar-alt'
		);
	}

	public function enqueue() {
		//JavaScript files
		wp_register_script( 'event-scripts', plugins_url( 'js/event-scripts.js', __FILE__ ), array( 'jquery' ), true, true );
		wp_enqueue_script( 'event-scripts' );

		//Style Sheets
		wp_register_style( 'event-styles', plugins_url( 'css/event-styles.css', __FILE__ ), array(), true );
		wp_enqueue_style( 'event-styles' );
	}

	public function adminEnqueue( $hook ) {
		global $post;
//		if ( 'toplevel_page_bkmrco/PluginInit' != $hook ) :
//			return;
//		endif;


		//Admin JavaScript Files
		wp_enqueue_media();
		wp_register_script( 'event-admin-scripts', plugins_url( 'js/event-admin-scripts.js', __FILE__ ), array(
			'jquery',
			'wp-color-picker',
			'jquery-ui-core',
			'jquery-ui-datepicker'
		), true, true );
		wp_enqueue_script( 'event-admin-scripts' );

		/*if ( $post->post_type == "event" ) {
			wp_register_script( 'event-date-picker', plugins_url( 'event-date.js', __FILE__ ), array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-datepicker'
			), true );
			wp_enqueue_script( 'event-date-picker' );
			wp_enqueue_style( 'jquery-ui-datepicker' );
		}*/

		//Admin Style Sheets
		wp_register_style( 'event-admin-styles', plugins_url( 'css/event-admin-styles.css', __FILE__ ), array(), true );
		wp_enqueue_style( 'event-admin-styles' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'jquery-ui-datepicker' );

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

	public function activate() {
		$this->buildEventPostType();
	}

	public function deactivate() {
		unregister_post_type( 'event' );
		flush_rewrite_rules();
	}

	function buildEventPostType() {
		$name     = 'event';
		$Name     = ucfirst( $name );
		$labels   = array(
			'name'               => __( $Name . 's' ),
			'singular_name'      => __( $Name ),
			'add_new'            => __( 'Add New ' . $Name ),
			'add_new_item'       => __( 'Add New ' . $Name ),
			'edit_item'          => __( 'Edit ' . $Name ),
			'new_item'           => __( 'Add New ' . $Name ),
			'view_item'          => __( 'View ' . $Name ),
			'search_items'       => __( 'Search ' . $Name ),
			'not_found'          => __( 'No ' . $name . 's found' ),
			'not_found_in_trash' => __( 'No ' . $name . 's found in trash' )
		);
		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'comments',
			'excerpt',
			'revisions'
		);
		register_post_type( $name,
			array(
				'labels'            => $labels,
				'supports'          => $supports,
				'public'            => true,
				'has_archive'       => true,
				'menu_icon'         => 'dashicons-calendar-alt',
				'show_ui'           => true,
				'show_in_menu'      => true,
				'menu_position'     => 25,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'capability_type'   => 'post',
			)
		);
		flush_rewrite_rules();
	}

	function eventMetaBoxes() {
		add_meta_box(
			'wpt_events_location',
			'Event Location',
			'wpt_events_location',
			'event',
			'side',
			'default'
		);
		function wpt_events_location() {
			global $post;
			wp_nonce_field( basename( __FILE__ ), 'event_fields' );

			/*$tz = get_post_meta( $post->ID, 'rgmTz', true );
			echo '<input type="text" name="location" value="'
			     . esc_textarea( $location )
			     . '" class="widefat" hidden/>';
			echo '<input type="text" name="rgmTz" value="" />';*/

			$location = get_post_meta( $post->ID, 'location', true );
			echo '<h3>Location</h3>'
			     . '<p><input type="text" name="location" value="'
			     . esc_textarea( $location )
			     . '" class="widefat"/></p>';

			$addMap = get_post_meta( $post->ID, 'addMap', true );
			echo '<input type="checkbox" name="addMap" value="1" '
			     . checked( $addMap, '1', false )
			     . ' /> Enable Google Map';

			$date = ( get_post_meta( $post->ID, 'eventDate', true ) ) ? DateTime::createFromFormat(
				'Ymd',
				get_post_meta( $post->ID, 'eventDate', true )
			)->format( 'F j, Y' ) : '';

			echo '<h3>Date</h3>'
			     . '<p><input type="text" id="rgm-event-date" name="eventDate" value="'
			     . esc_textarea( $date )
			     . '" class="widefat"/></p>';

			$time = get_post_meta( $post->ID, 'eventTime', true );
			echo '<h3>Time</h3>'
			     . '<p><input type="time" name="eventTime" value="'
			     . esc_textarea( $time )
			     . '" class="widefat"/></p>';
		}

		add_action( 'save_post', 'wpt_save_events_meta', 1, 2 );
		function wpt_save_events_meta( $post_id, $post ) {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['event_fields'] ) || ! wp_verify_nonce( $_POST['event_fields'], basename( __FILE__ ) ) ) {
				return;
			}


			$date = DateTime::createFromFormat(
				'F j, Y',
				sanitize_text_field( $_POST['eventDate'] )
			);

			$events_meta['location']  = sanitize_text_field( $_POST['location'] );
			$events_meta['addMap']    = sanitize_text_field( $_POST['addMap'] );
			$events_meta['eventDate'] = $date->format( 'Ymd' );
			$events_meta['eventTime'] = sanitize_text_field( $_POST['eventTime'] );

			foreach ( $events_meta as $key => $value ) :
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
			endforeach;
		}
	}

	//Add the custom post type template
	function customPostTemplate( $single ) {
		global $post;
		$dir = SConfig::get( "pluginDir" );

		if ( $post->post_type == "event" ) :
			$single = $dir . '/single-event.php';
		endif;

		return $single;
	}
}