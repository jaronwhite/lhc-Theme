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
	<?php the_custom_header_markup(); ?>
    <nav>
        <div id="identity">
            <a href="<?php echo home_url(); ?>">
				<?php if ( has_custom_logo( 'custom_logo' ) ) : ?>
                    <div id="logo-wrap">
						<?php
						$custom_logo_id = get_theme_mod( 'custom_logo' );
						$image          = wp_get_attachment_image_src( $custom_logo_id, 'full' );
						?>
                        <img id="logo" src="<?php echo $image[0]; ?>"/>
                    </div>
				<?php
				elseif ( ! has_custom_logo( 'custom_logo' ) ) : ?>
                    <h1 class="title" style="color:#<?php echo get_header_textcolor(); ?>;">LHC</h1>
				<?php endif; ?>
            </a>
        </div>

		<?php

		$primaryNavArgs = array(
			'theme_location'  => 'primary',
			'menu_id'         => '',
			'menu_class'      => '',
			'container_class' => 'menu',
			'container_id'    => 'primary'
		);

		wp_nav_menu( $primaryNavArgs );

		?>
    </nav>
</header>
<main id="main-content">
