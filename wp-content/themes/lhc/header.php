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

<?php
( is_front_page() ) ? $headerClass = "front" : $headerClass = "page";
?>

<header id="header" class="<?php echo $headerClass; ?>">

    <!-- style="background: url('<?php //echo get_header_image(); ?>') center no-repeat;background-size: cover;" -->

    <div id="header-bg" class="<?php echo $headerClass; ?>">
		<?php

		if ( is_front_page() ) {
			the_custom_header_markup();
		} else {
			?>
            <div id="header-image-wrap"
                 style="background: url(<?php echo get_header_image(); ?>) center no-repeat; background-size: cover;"></div>
			<?php
		}
		?>
        <div id="header-title-wrap">
            <h1><?php echo get_bloginfo( 'name' ); ?></h1>
            <h3><?php echo get_bloginfo( 'description' ); ?></h3>
        </div>
        <div id="header-shade"></div>
		<?php
		?>

    </div>
</header>
<?php
$primaryNavArgs = array(
	'walker'          => new WalkingTheDog(),
	'theme_location'  => 'primary',
	'menu_id'         => 'primary-ul',
	'menu_class'      => '',
	'container'       => 'nav',
	'container_class' => 'menu',
	'container_id'    => 'primary',
	'depth'           => 2
);
wp_nav_menu( $primaryNavArgs );
?>
<main id="main-content">