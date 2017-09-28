<?php

class LHC_CustomPostTypes {
	//Portfolio
	function portfolio_post_type() {
		register_post_type( 'portfolio',
			array(
				'labels'   => array(
					'name'          => __( 'Portfolio' ),
					'singular_name' => __( 'Portfolio' )
				),
				'public'   => true,
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'revisions',
					'page-attributes'
				),

			)
		);
	}

	//Services
	function services_post_type() {
		register_post_type( 'services',
			array(
				'labels'   => array(
					'name'          => __( 'Services' ),
					'singular_name' => __( 'Service' )
				),
				'public'   => true,
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'revisions',
					'page-attributes'
				),
			)
		);
	}

	//Gallery
	function gallery_post_type() {
		register_post_type( 'gallery',
			array(
				'labels'   => array(
					'name'          => __( 'Gallery' ),
					'singular_name' => __( 'Gallery' )
				),
				'public'   => true,
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'revisions',
					'page-attributes'
				),
			)
		);
	}
}