<?php

class EventSlider {
	function init() {
		add_action('wp_head', array('EventReader','head'));
		add_action( 'admin_menu', array( $this, 'addMenuPage' ) );
		add_shortcode( 'EvtSlider', array( 'EventSliderShortcode', 'event_slider_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'es_enqueue' ) );
	}

	function addMenuPage() {
		add_menu_page( 'Event Slider', 'Event Slider', 'manage_options', __FILE__, array(
			$this,
			'admin_page'
		), 'dashicons-plus' );
	}

	function admin_page() {
		require_once( 'admin-page.php' );
	}

	function es_enqueue() {
		wp_register_style( 'event-slider', get_template_directory_uri() . '/inc/event-slider/css/event-slider-style.css' );
		wp_enqueue_style( 'event-slider' );
		wp_register_script( 'es-scripts', get_template_directory_uri() . '/inc/event-slider/js/event-slider-script.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'es-scripts' );
	}
}