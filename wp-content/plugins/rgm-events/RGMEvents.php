<?php

/*
Plugin Name:    RGM Events
Plugin URI:     http://raingaugemedia.com
Description:    A simple events list builder.
Version:        1.0.0
Author:         Jaron White
Author URI:     http://raingaugemedia.com/jaronwhite
License:        GNU General Public License v2 or later
License URI:    http://www.gnu.org/licenses/gpl-2.0.html
*/

class RGMEvents {

	public static function main() {
		//Setup Autoloader
		self::autoload();

		//Set Globals in Config
		SConfig::set( "prefix", "rgmE_" );
		SConfig::set( "pluginFile", __FILE__ );
		SConfig::set( "pluginDir", dirname( __FILE__ ) );
		SConfig::set( "googleAPIKey", "AIzaSyDVj8soWjeyWleFV7NVvIRjGboGqC8RARA" );

		//Initialize Plugin
		new SPluginInit();
	}

	private static function autoload() {
		spl_autoload_register( function ( $class ) {
			$class = str_replace( "\\", "/", $class );
			$sub0  = glob( plugin_dir_path( __FILE__ ) );
			$sub1  = glob( plugin_dir_path( __FILE__ ) . "*/", GLOB_ONLYDIR );
			$sub2  = glob( plugin_dir_path( __FILE__ ) . "*/*/", GLOB_ONLYDIR );
			$subs  = array_merge( $sub0, $sub1, $sub2 );

			foreach ( $subs as $dir ) {
				$file = $dir . $class . ".php";
				if ( file_exists( $file ) && is_readable( $file ) ) {
					require_once( $file );
				}

			}
		} );
	}
}

RGMEvents::main();