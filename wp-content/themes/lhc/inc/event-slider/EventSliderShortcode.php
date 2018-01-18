<?php
include( 'EventReader.php' );

class EventSliderShortcode {

	public function event_slider_shortcode() {

		$er = new EventReader();
		$t  = $er->readFile( "event-slider-events.json" );

		return $er->buildSlider();
	}

}
