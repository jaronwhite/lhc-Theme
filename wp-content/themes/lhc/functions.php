<?php

//Add theme support
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-logo' );
add_theme_support( 'post-formats', array(
	'video',
	'audio'
) );
add_theme_support( 'custom-header', array(
	'video' => true
) );

function custom_excerpt_length( $length ) {
	return 20;
}

add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

//Includes for function pages
include( 'inc/LHC_Enqueue.php' );
include( 'inc/LHC_Customizer.php' );
include( 'inc/widgets.php' );
include( 'inc/LHC_Shortcodes.php' );
include( 'inc/LHC_CustomPostTypes.php' );
include( 'inc/widgets/RGM_PageHierarchyMenuWidget.php' );
include( 'inc/LHC_WalkerNavMenu.php' );
include( 'inc/WalkingTheDog.php' );
include( 'inc/WalkingTheAdminDog.php' );

//Add actions & filters
add_action( 'init', 'portfolio_post_type' );
add_action( 'init', 'services_post_type' );
add_action( 'init', 'gallery_post_type' );
add_action( 'widgets_init', 'lhc_widgets_init' );
add_action( 'wp_enqueue_scripts', 'styles' );
add_action( 'wp_enqueue_scripts', 'scripts' );
add_action( 'customize_register', 'customize_theme_options' );


//Add shortcodes
add_shortcode( 'portfolio', 'portfolio' );
add_shortcode( 'services', 'services' );
add_shortcode( 'gallery', 'gallery' );

/*//Event Slider Plugin
include( 'inc/event-slider/EventSlider.php' );
include( 'inc/event-slider/EventSliderShortcode.php' );
$evtSldr = new EventSlider();
$evtSldr->init();*/

//Register and add nav menus
register_nav_menus( array(
	'primary' => __( 'Main Menu' ),
	'social'  => __( 'Social Menu' )
) );

//Remove autop option
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'custom_wpautop' );
add_action( 'admin_init', 'pMetaBox' );
add_action( 'save_post', 'save_removep', 1, 2 );

function custom_wpautop( $content ) {
	if ( get_post_meta( get_the_ID(), 'removep', true ) == '1' ) {
		return $content;
	} else {
		return wpautop( $content );
	}
}

function pMetaBox() {
	add_meta_box(
		'removep',
		'Remove Wp Auto paragraph',
		'removep',
		'page',
		'normal',
		'default'
	);
	function removep() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'pcheck' );

		$removep = get_post_meta( $post->ID, 'removep', true );
		echo '<input type="checkbox" name="removep" value="1" '
		     . checked( $removep, '1', false )
		     . ' /> Remove';
	}


	function save_removep( $post_id, $post ) {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['pcheck'] ) || ! wp_verify_nonce( $_POST['pcheck'], basename( __FILE__ ) ) ) {
			return;
		}

		$events_meta['removep'] = sanitize_text_field( $_POST['removep'] );

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

/*CUSTOM MENU CRAP*/
add_filter( 'wp_edit_nav_menu_walker', function () {
	return 'WalkingTheAdminDog';
} );
function rgm_add_custom_nav_options( $menu_item ) {
	$menu_item->submenu = get_post_meta( $menu_item->ID, '_menu_item_submenu', true );

	return $menu_item;
}

add_filter( 'wp_setup_nav_menu_item', 'rgm_add_custom_nav_options' );

function rgm_save_custom_nav_options( $menu_id, $menu_item_db_id, $args ) {

// Check if element is properly sent
	if ( is_array( $_REQUEST['menu-item-submenu'] ) ) {
		$submenu_value = $_REQUEST['menu-item-submenu'][ $menu_item_db_id ];
		update_post_meta( $menu_item_db_id, '_menu_item_submenu', $submenu_value );
	}
}

add_action( 'wp_update_nav_menu_item', 'rgm_save_custom_nav_options', 10, 3 );

add_filter( 'wp_nav_menu_items', 'rgm_toggle_menu', 10, 2 );
function rgm_toggle_menu( $nav, $args ) {
	if ( $args->theme_location == 'primary' ) {
		$toggle = '<div id="menu-toggle-wrap">'
		          . '<p>MENU</p>'
		          . '<div id="hamburglar" class="open">'
		          . '<span></span><span></span><span></span>'
		          . '</div>'
		          . '</div>';

		return $toggle . $nav;
	}

	return $nav;
}