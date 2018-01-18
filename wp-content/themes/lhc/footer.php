</main>


<?php get_sidebar();

?>

<footer id="footer">
	<?php
	if ( has_nav_menu( 'social' ) ) {
		$walker        = new LHC_WalkerNavMenu;
		$socialNavArgs = array(
			'theme_location'  => 'social',
			'menu_id'         => 'social-nav',
			'menu_class'      => '',
			'container'       => 'nav',
			'container_class' => 'menu',
			'container_id'    => 'social',
			'depth'           => 1,
			'walker'          => $walker
		);
		wp_nav_menu( $socialNavArgs );
	}
	?>
    <p class="copyright">&copy;<?php echo date( 'Y' ); ?> <span><?php bloginfo( 'name' ); ?>.</span> All Rights
        Reserved.<a href="http://raingaugemedia.com"><span id="rglogo"></span></a></p>
</footer>

<?php wp_footer(); ?>

</body>
</html>
