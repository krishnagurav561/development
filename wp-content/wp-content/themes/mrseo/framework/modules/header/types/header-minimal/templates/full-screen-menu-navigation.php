<nav class="eltdf-fullscreen-menu">
	<?php
		wp_nav_menu(
			array(
				'theme_location' => 'minimal' ,
				'container'  => '',
				'container_class' => '',
				'menu_class' => '',
				'menu_id' => '',
				'fallback_cb' => 'top_navigation_fallback',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'walker' => new MrSeoElatedFullscreenNavigationWalker()
			)
		);
	?>
</nav>