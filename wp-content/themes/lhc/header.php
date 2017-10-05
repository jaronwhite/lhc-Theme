<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php wp_title(); ?></title>
    <meta name="description" content="Living Hope Church">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>
<body>

<header id="header">
    <div id="header-bg"
         style="background: url('<?php echo get_header_image(); ?>') center no-repeat;background-size: cover;">
	    <?php the_custom_header_markup(); ?>
    </div>
    <div id="header-title-wrap">
        <h1><?php echo get_bloginfo( 'name' ); ?></h1>
        <h3><?php echo get_bloginfo( 'description' ); ?></h3>
    </div>

    <nav id="primary" class="menu">
        <div id="menu-toggle-wrap">
            <p>MENU</p>
            <div id="hamburglar" class="open">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
		<?php
		$primaryNavArgs = array(
			'theme_location'  => 'primary',
			'menu_id'         => 'primary-ul',
			'menu_class'      => '',
			'container'       => false,
			'container_class' => 'menu',
			'container_id'    => 'primary'
		);
		wp_nav_menu( $primaryNavArgs );
		?>
    </nav>
</header>

<main id="main-content">
