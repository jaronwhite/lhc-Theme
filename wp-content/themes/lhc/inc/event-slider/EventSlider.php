<?php

class EventSlider {
	function init() {
		add_action( 'wp_head', array( 'EventReader', 'head' ) );
//		add_action( 'admin_head', array( 'EventReader', 'head' ) );
		add_action( 'admin_menu', array( $this, 'addMenuPage' ) );
		add_shortcode( 'EvtSlider', array( 'EventSliderShortcode', 'event_slider_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'es_enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'es_admin_enqueue' ) );
	}

	function addMenuPage() {
		add_menu_page( 'Event Slider', 'Event Slider', 'manage_options', __FILE__, array(
			$this,
			'admin_page'
		), 'dashicons-leftright' );
	}

	function admin_page() {
		require_once( 'admin-page.php' );
	}

	function es_enqueue() {
		$dm = 'https://rawgit.com/jaronwhite/DateOMatic/master/DateOMatic.min.js';
		wp_register_style( 'es-style', get_template_directory_uri() . '/inc/event-slider/css/event-slider-style.css' );
		wp_enqueue_style( 'es-style' );
		wp_register_script( 'DateOMatic', get_template_directory_uri() . '/inc/event-slider/js/DateOMatic.js', array(), '1.0.0' );
		wp_enqueue_script( 'DateOMatic' );
		wp_register_script( 'es-scripts', get_template_directory_uri() . '/inc/event-slider/js/event-slider-script.js', array(
			'jquery',
			'DateOMatic'
		), '1.0.0', true );
		wp_enqueue_script( 'es-scripts' );
	}

	function es_admin_enqueue( $hook ) {
		$topLvl = 'toplevel_page_' . __DIR__ . '/EventSlider';
		$pl     = explode( "/", $hook );
		$pll    = $pl[ sizeof( $pl ) - 1 ];
		if ( 'EventSlider' != $pll ) {
			return;
		}

		wp_register_style( 'es-admin-style', get_template_directory_uri() . '/inc/event-slider/css/event-slider-admin-style.css' );
		wp_enqueue_style( 'es-admin-style' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_register_script( 'DateOMatic', get_template_directory_uri() . '/inc/event-slider/js/DateOMatic.js', array(), '1.0.0' );
		wp_enqueue_script( 'DateOMatic' );
		wp_register_script( 'es-admin-scripts', get_template_directory_uri() . '/inc/event-slider/js/event-slider-admin-script.js', array(
			'jquery',
			'DateOMatic',
			'wp-color-picker'
		), '1.0.0', true );
		wp_enqueue_script( 'es-admin-scripts' );
	}
}