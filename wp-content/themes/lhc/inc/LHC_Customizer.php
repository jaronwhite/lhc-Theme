<?php

class LHC_Customizer {
	function customize_theme_options( $wp_customize ) {
		$wp_customize->add_section(
			'theme_options',
			array(
				'title'       => __( 'Theme Options', 'lhc' ),
				'priority'    => 100,
				'capability'  => 'edit_theme_options',
				'description' => __( 'Change theme options here.', 'lhc' ),
			)
		);

		$wp_customize->add_setting( 'page_layout', array(
			'default' => false,
		) );

		$wp_customize->add_control( 'page_layout',
			array(
				'label'       => __( 'Front Page Section', 'lhc' ),
				'description' => 'desc text',
				'section'     => 'theme_options',
				'settings'    => 'page_layout',
				'type'        => 'dropdown-pages',
				'priority'    => 10,
			)
		);
	}
}