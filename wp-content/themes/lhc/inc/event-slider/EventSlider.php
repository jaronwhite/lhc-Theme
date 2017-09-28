<?php

class EventSlider {
	function init() {
		add_action( 'admin_menu', array( $this, 'addMenuPage' ) );
		add_shortcode( 'EvtSlider', array( 'EventSliderShortcode', 'event_slider_shortcode' ) );
	}

	function addMenuPage() {
		add_menu_page( 'Event Slider', 'Event Slider', 'manage_options', __FILE__, 'admin-page.php', 'dashicons-plus' );
	}
}

