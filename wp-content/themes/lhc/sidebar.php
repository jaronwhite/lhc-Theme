<!-- sidebar -->
<?php
if ( is_active_sidebar( 'sidebar-bottom' ) ) : ?>
    <aside id="sidebar-bottom" class="">
		<?php dynamic_sidebar( 'sidebar-bottom' ); ?>
    </aside>
<?php endif; ?>
