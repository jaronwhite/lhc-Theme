<?php

function lhc_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Bottom Sidebar', 'lhc' ),
		'id'            => 'sidebar-bottom',
		'before_widget' => '<div class="widget"><div class="widget-inner">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}