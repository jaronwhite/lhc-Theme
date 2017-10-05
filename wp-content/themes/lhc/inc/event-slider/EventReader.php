<?php

/**
 * Created by PhpStorm.
 * User: jaronwhite
 * Date: 10/4/2017
 * Time: 1:33 PM
 */
class EventReader {
	public function readFile( $fileName ) {
		$filePath    = get_theme_file_path() . '/inc/event-slider/';
		$fileSize    = filesize( $filePath . $fileName );
		$file        = fopen( $filePath . $fileName, "r" );
		$fileContent = fread( $file, $fileSize );
		fclose( $file );

		return json_decode( $fileContent );
	}

	public function buildSlider() {

		$settings     = $this->readFile( "event-slider-config.json" );
		$eventDetails = $this->readFile( "event-slider-events.json" );

		$eventCount = sizeof( $eventDetails->events );

		$sliderOpen   = '<article id="event-slider" class="wide-article">'
		                . '<div id="event-slider-bg"></div>';
		$sliderEvents = '';

		$sliderArrows = '<svg id="slider-left-arrow" class="slider-arrow" viewBox="0 0 100 150">'
		                . '<path fill="none" stroke-width="25" stroke-linecap="round" d="M85,15 L15,75 M85,135 L15,75"/>'
		                . '</svg><svg id="slider-right-arrow" class="slider-arrow" viewBox="0 0 100 150">'
		                . '<path fill="none" stroke-width="25" stroke-linecap="round" d="M15,15 L85,75 M15,135 L85,75"/>'
		                . '</svg>';
		$spanEls      = '';
		for ( $i = 0; $i < $eventCount; $i ++ ) {
			$event = $eventDetails->events[ $i ];

			$sliderEvents .= '<div class="event" style="background: url('
			                 . $event->image
			                 . ') no-repeat center; background-size: cover;">'
			                 . '<div class="event-content-wrap">'
			                 . '<h2 class="event-title">'
			                 . $event->title
			                 . '</h2>'
			                 . '<p class="event-desc">' . $event->description
			                 . " <br/>{$event->datetime->month} 1, 2017 @ 7:00PM</p>"
			                 . '</div>'
			                 . '</div>';
			$spanEls      .= '<span></span>';
		}
		echo htmlspecialchars( $sliderEvents );
		$sliderControls = '<div id="controls">'
		                  . $spanEls
		                  . '</div>';
		$sliderClose    = '</article>';

		$slider = $sliderOpen . $sliderEvents;
		if ( $eventCount > 1 ) {
			if ( $settings->controls->lr ) {
				$slider .= $sliderArrows;
			}
			if ( $settings->controls->select ) {
				$slider .= $sliderArrows;
			}
		}
		$slider .= $sliderClose;

		return $slider;
	}

	public function head() {
		echo '<script>'
		     . 'console.log("Header Testing Occurred!");'
		     . '</script>';
	}
}