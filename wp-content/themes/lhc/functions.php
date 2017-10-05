<?php

//Add theme support
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-logo' );
add_theme_support( 'custom-header', array(
	'video' => true
) );

//Includes for function pages
include( 'inc/LHC_Enqueue.php' );
include( 'inc/LHC_Customizer.php' );
include( 'inc/widgets.php' );
include( 'inc/LHC_Shortcodes.php' );
include( 'inc/LHC_CustomPostTypes.php' );

//Add actions & filters
add_action( 'init', array( 'LHC_CustomPostTypes', 'portfolio_post_type' ) );
add_action( 'init', array( 'LHC_CustomPostTypes', 'services_post_type' ) );
add_action( 'init', array( 'LHC_CustomPostTypes', 'gallery_post_type' ) );
add_action( 'widgets_init', 'lhc_widgets_init' );
add_action( 'wp_enqueue_scripts', array( 'LHC_Enqueue', 'styles' ) );
add_action( 'wp_enqueue_scripts', array( 'LHC_Enqueue', 'scripts' ) );
add_action( 'customize_register', array( 'LHC_Customizer', 'customize_theme_options' ) );


//Add shortcodes
add_shortcode( 'portfolio', array( 'LHC_Shortcodes', 'portfolio' ) );
add_shortcode( 'services', array( 'LHC_Shortcodes', 'services' ) );
add_shortcode( 'gallery', array( 'LHC_Shortcodes', 'gallery' ) );

//Event Slider Plugin
include( 'inc/event-slider/EventSlider.php' );
include( 'inc/event-slider/EventSliderShortcode.php' );
$evtSldr = new EventSlider();
$evtSldr->init();

//Register and add nav menus
register_nav_menus( array(
	'primary' => __( 'Main Menu' )
) );

