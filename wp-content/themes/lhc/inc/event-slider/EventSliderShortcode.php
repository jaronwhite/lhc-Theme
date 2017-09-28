<?php

class EventSliderShortcode {
	//Produces the slider with shortcode [EvtSlider]
	function event_slider_shortcode( $atts ) {
		$a     = shortcode_atts( array(), $atts );


		return "Event Slider";
	}
}