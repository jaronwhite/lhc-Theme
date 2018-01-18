<?php

/**
 * Nav Menu API: Walker_Nav_Menu class
 *
 * @package WordPress
 * @subpackage Nav_Menus
 * @since 4.6.0
 */

/**
 * Core class used to implement an HTML list of nav menu items.
 *
 * @since 3.0.0
 *
 * @see Walker
 */
class LHC_WalkerNavMenu extends Walker {
	/**
	 * What the class handles.
	 *
	 * @since 3.0.0
	 * @access public
	 * @var string
	 *
	 * @see Walker::$tree_type
	 */
	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	/**
	 * Database fields to use.
	 *
	 * @since 3.0.0
	 * @access public
	 * @todo Decouple this.
	 * @var array
	 *
	 * @see Walker::$db_fields
	 */
	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args An object of `wp_nav_menu()` arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param WP_Post $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @param int $id Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param WP_Post $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string $title Title attribute.
		 * @type string $target Target attribute.
		 * @type string $rel The rel attribute.
		 * @type string $href The href attribute.
		 * }
		 *
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param WP_Post $item The current menu item.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
		$svg   = $this->getSocialSVG( $title );

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . $svg . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param WP_Post $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param WP_Post $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$output .= "</li>{$n}";
	}

	/**
	 * Places title in <p> tag if not a recognized social media platform or replaces title with svg icon.
	 * Current version supports facebook, instagram, email, youtube, amazon.
	 *
	 * @param $title
	 *
	 * @return string
	 */
	public function getSocialSVG( $title ) {

		$facebookSVG = '<svg id="social-facebook" class="social-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 288 288">
                    <path class="social-bg"
                          d="M36 0h216c19.9 0 36 16.1 36 36v216c0 19.9-16.1 36-36 36H36c-19.9 0-36-16.1-36-36V36C0 16.1 16.1 0 36 0z"/>
                    <path class="social-fg"
                          d="M207.6 133.2v-28.8c0-10.8 7.2-13.2 12-13.2h31.2V42h-43.2c-48 0-60 36-60 60v32.4H120v50.4h27.6V326.4H206.4V183.6H246l4.8-50.4H207.6z"/>
                </svg>';

		$instaSVG = '<svg id="social-instagram" class="social-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 288 288">
                    <path class="social-bg"
                          d="M36 0h216c19.9 0 36 16.1 36 36v216c0 19.9-16.1 36-36 36H36c-19.9 0-36-16.1-36-36V36C0 16.1 16.1 0 36 0z"/>
                    <path class="social-fg"
                          d="M144 24.2c-32.6 0-36.6 0.2-49.4 0.7 -12.8 0.6-21.4 2.6-29.1 5.6 -7.9 3.1-14.6 7.2-21.2 13.8S33.5 57.6 30.5 65.5c-3 7.6-5 16.3-5.6 29.1 -0.6 12.8-0.7 16.8-0.7 49.4s0.2 36.6 0.7 49.4c0.6 12.8 2.6 21.5 5.6 29.1 3.1 7.9 7.2 14.6 13.8 21.2 6.7 6.7 13.3 10.8 21.2 13.8 7.6 3 16.3 5 29.1 5.6 12.8 0.6 16.8 0.7 49.4 0.7s36.6-0.1 49.4-0.7c12.8-0.6 21.5-2.6 29.1-5.6 7.9-3.1 14.6-7.2 21.2-13.8 6.7-6.7 10.8-13.3 13.8-21.2 3-7.6 5-16.3 5.6-29.1 0.6-12.8 0.7-16.8 0.7-49.4s-0.1-36.6-0.7-49.4c-0.6-12.8-2.6-21.5-5.6-29.1 -3.1-7.9-7.2-14.6-13.8-21.2 -6.7-6.7-13.3-10.8-21.2-13.8 -7.6-3-16.3-5-29.1-5.6C180.6 24.3 176.6 24.2 144 24.2zM144 45.7c32 0 35.8 0.2 48.4 0.7 11.7 0.5 18 2.5 22.2 4.1 5.6 2.2 9.6 4.8 13.8 8.9 4.2 4.2 6.8 8.2 8.9 13.8 1.6 4.2 3.6 10.6 4.1 22.2 0.6 12.6 0.7 16.4 0.7 48.4s-0.1 35.8-0.7 48.4c-0.6 11.7-2.6 18-4.2 22.2 -2.2 5.6-4.8 9.6-9 13.8 -4.2 4.2-8.2 6.8-13.8 8.9 -4.2 1.6-10.6 3.6-22.3 4.1 -12.7 0.6-16.5 0.7-48.5 0.7 -32.1 0-35.8-0.1-48.5-0.7 -11.7-0.6-18.1-2.6-22.3-4.2 -5.7-2.2-9.6-4.8-13.8-9 -4.2-4.2-6.9-8.2-9-13.8 -1.6-4.2-3.6-10.6-4.2-22.3 -0.4-12.6-0.6-16.5-0.6-48.4 0-31.9 0.2-35.8 0.6-48.5C46.5 83.6 48.4 77.2 50.1 73c2.1-5.7 4.8-9.6 9-13.8 4.2-4.2 8.1-6.9 13.8-9 4.2-1.7 10.5-3.6 22.2-4.2 12.7-0.4 16.5-0.6 48.5-0.6L144 45.7zM144 82.5c-34 0-61.5 27.6-61.5 61.5 0 34 27.6 61.5 61.5 61.5 34 0 61.5-27.6 61.5-61.5C205.5 110 178 82.5 144 82.5zM144 183.9c-22.1 0-39.9-17.9-39.9-39.9 0-22.1 17.9-39.9 39.9-39.9 22.1 0 39.9 17.9 39.9 39.9C183.9 166.1 166.1 183.9 144 183.9zM222.4 80c0 7.9-6.5 14.4-14.4 14.4 -7.9 0-14.4-6.5-14.4-14.4s6.5-14.4 14.4-14.4C215.9 65.7 222.4 72.1 222.4 80z"/>
                </svg>';

		$emailSVG = '<svg id="social-email" class="social-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 288 288">
                    <path class="social-bg"
                          d="M36 0h216c19.9 0 36 16.1 36 36v216c0 19.9-16.1 36-36 36H36c-19.9 0-36-16.1-36-36V36C0 16.1 16.1 0 36 0z"/>
                    <path class="social-fg"
                          d="M240 48H48c-13.2 0-23.9 10.8-23.9 24L24 216c0 13.2 10.8 24 24 24h192c13.2 0 24-10.8 24-24V72C264 58.8 253.2 48 240 48zM240 96l-96 60L48 96V72l96 60 96-60V96z"/>
                </svg>';

		$youtubeSVG = '<svg id="social-youtube" class="social-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 288 288">
                    <path class="social-bg"
                          d="M36 0h216c19.9 0 36 16.1 36 36v216c0 19.9-16.1 36-36 36H36c-19.9 0-36-16.1-36-36V36C0 16.1 16.1 0 36 0z"/>
                    <path class="social-fg"
                          d="M258.8 86.1c-2.8-10.1-10.7-18-20.9-20.9 -18.7-5-93.8-5-93.8-5s-75-0.1-93.8 5C40.2 68.1 32.3 76 29.4 86.1c-3.5 19.1-5.3 38.5-5.2 58 -0.1 19.4 1.7 38.7 5.2 57.8 2.8 10.1 10.7 18 20.9 20.9 18.7 5 93.8 5 93.8 5s75 0 93.8-5c10.1-2.8 18-10.7 20.9-20.9 3.5-19.1 5.1-38.4 5-57.7C263.9 124.7 262.3 105.3 258.8 86.1zM120.1 180v-71.8l62.6 36L120.1 180z"/>
                </svg>';

		$amazonSVG = '<svg id="social-amazon" class="social-svg" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 288 288">
                    <path class="social-bg"
                          d="M36 0h216c19.88 0 36 16.12 36 36v216c0 19.88-16.12 36-36 36H36c-19.88 0-36-16.12-36-36V36C0 16.12 16.12 0 36 0z"/>
                    <path class="social-fg"
                          d="M25.06 203.89c0.72-1.15 1.86-1.23 3.46-0.22 36.17 20.99 75.55 31.5 118.09 31.5 28.37 0 56.39-5.3 84.03-15.87l3.14-1.39c1.37-0.6 2.33-0.99 2.92-1.29 2.25-0.87 3.88-0.46 5.22 1.29 1.19 1.73 0.89 3.34-1.19 4.78 -2.55 1.89-5.97 4.08-10.01 6.51 -12.37 7.39-26.26 13.09-41.63 17.17 -15.22 4.04-30.29 6.07-44.93 6.07 -22.53 0-43.87-3.94-64.02-11.81 -20.1-7.9-38-19-54.02-33.33 -0.99-0.74-1.49-1.49-1.49-2.19 0-0.47 0.2-0.89 0.5-1.29L25.06 203.89zM90.37 142.03c0-10 2.46-18.53 7.39-25.64 4.92-7.06 11.64-12.43 20.29-16.07 7.92-3.33 17.47-5.72 28.97-7.16 3.88-0.46 10.28-1.02 19.1-1.73v-3.68c0-9.25-1.04-15.5-2.98-18.65 -3-4.28-7.76-6.47-14.32-6.47h-1.81c-4.78 0.46-8.91 1.95-12.39 4.58 -3.48 2.69-5.72 6.27-6.71 10.9 -0.6 2.98-2.05 4.63-4.33 5.07l-25.07-3.13c-2.47-0.6-3.7-1.79-3.7-3.88 0-0.46 0.07-0.9 0.22-1.49 2.46-12.83 8.51-22.38 18.11-28.65 9.71-6.13 20.89-9.7 33.72-10.45h5.37c16.42 0 29.42 4.32 38.68 12.83 1.34 1.49 2.69 2.98 4.03 4.78 1.19 1.64 2.23 3.12 2.82 4.48 0.75 1.33 1.49 3.28 1.94 5.67 0.6 2.53 1.05 4.18 1.34 5.07 0.3 1.03 0.62 2.98 0.76 6.12 0.1 3.11 0.2 4.91 0.2 5.5v52.53c0 3.74 0.6 7.16 1.64 10.31 1.05 3.11 2.09 5.37 3.13 6.71l5.07 6.7c0.9 1.35 1.35 2.55 1.35 3.58 0 1.19-0.6 2.25-1.79 3.12 -11.94 10.45-18.5 16.12-19.53 17.01 -1.64 1.34-3.73 1.49-6.27 0.45 -1.94-1.65-3.73-3.3-5.23-4.93l-3.08-3.45c-0.6-0.74-1.65-2.09-3.15-4.18l-2.98-4.33c-8.06 8.81-15.95 14.33-23.88 16.56 -4.91 1.49-10.87 2.26-18.21 2.26 -11.04 0-20.29-3.41-27.46-10.29 -7.16-6.86-10.74-16.56-10.74-29.25l-0.5-0.76L90.37 142.03zM127.71 137.67c0 5.63 1.39 10.15 4.23 13.57 2.84 3.38 6.72 5.09 11.49 5.09 0.45 0 1.06-0.07 1.94-0.2 0.9-0.16 1.33-0.23 1.65-0.23 6.11-1.59 10.74-5.5 14.17-11.72 1.64-2.78 2.84-5.77 3.58-9.05 0.9-3.18 1.19-5.87 1.34-7.96 0.15-1.94 0.15-5.37 0.15-10v-5.37c-8.36 0-14.76 0.6-19.1 1.79 -12.68 3.58-19.1 11.64-19.1 24.17l-0.35-0.2V137.67zM218.85 207.58c0.3-0.6 0.75-1.09 1.31-1.69 3.6-2.42 7.1-4.08 10.45-4.97 5.47-1.32 10.84-2.21 16.04-2.39 1.39-0.12 2.79 0 4.08 0.3 6.47 0.6 10.45 1.67 11.66 3.28 0.63 0.9 0.9 2.27 0.9 3.88v1.49c0 5.07-1.39 11.04-4.13 17.91 -2.77 6.86-6.61 12.42-11.5 16.71 -0.73 0.6-1.39 0.9-1.96 0.9 -0.3 0-0.6 0-0.9-0.12 -0.9-0.44-1.06-1.19-0.64-2.39 5.37-12.53 8.02-21.32 8.02-26.26 0-1.49-0.3-2.69-0.86-3.42 -1.44-1.65-5.47-2.56-12.18-2.56 -2.42 0-5.3 0.16-8.65 0.46 -3.61 0.45-6.96 0.9-9.95 1.34 -0.9 0-1.47-0.14-1.79-0.44 -0.3-0.3-0.36-0.47-0.2-0.77 0-0.17 0.06-0.3 0.2-0.63v-0.6L218.85 207.58z"/>
                </svg>';

		$title       = strtoupper( $title );
		$socialArray = array(
			'FACEBOOK'  => $facebookSVG,
			'INSTAGRAM' => $instaSVG,
			'EMAIL'     => $emailSVG,
			'YOUTUBE'   => $youtubeSVG,
			'AMAZON'    => $amazonSVG
		);

		return ( array_key_exists( $title, $socialArray ) ) ? $socialArray[$title] : '<p>' . $title . '</p>';
	}

} // Walker_Nav_Menu