<?php

/**
 * Created by PhpStorm.
 * User: jaronwhite
 * Date: 7/15/2017
 * Time: 6:45 PM
 */
class AdminPage {
	public function renderAdminPage() {
		echo "<h1>Hello Plugin World!</h1>";
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