<?php


//Enqueue Styles
function styles() {
	wp_register_style( 'lhc-style', get_stylesheet_uri() ); //main stylesheet
	wp_enqueue_style( 'lhc-style' );
	wp_register_style( 'google-font', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' );
	wp_enqueue_style( 'google-font' );
}

//Enqueue Scripts
function scripts() {
	wp_register_script( 'lhc-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'lhc-scripts' );
}
