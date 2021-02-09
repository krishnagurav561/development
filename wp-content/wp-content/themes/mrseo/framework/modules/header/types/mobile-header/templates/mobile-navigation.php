<?php do_action('mrseo_elated_before_mobile_navigation'); ?>

<?php if (has_nav_menu('mobile-navigation') || has_nav_menu('main-navigation')) { ?>
	<nav class="eltdf-mobile-nav">
		<div class="eltdf-grid">
			<?php
			// Set main navigation menu as mobile if mobile navigation is not set
			$theme_location = has_nav_menu('mobile-navigation') ? 'mobile-navigation' : 'main-navigation';

			wp_nav_menu(array(
				'theme_location' => $theme_location,
				'container' => '',
				'container_class' => '',
				'menu_class' => '',
				'menu_id' => '',
				'fallback_cb' => 'top_navigation_fallback',
				'link_before' => '<span>',
				'link_after' => '</span>',
				'walker' => new MrSeoElatedMobileNavigationWalker()
			)); ?>
    <?php
    if (is_user_logged_in()) {
        ?>
        <ul id="menu-main-menu" class="clearfix">
            <li id="nav-menu-item-4375" class="menu-item menu-item-type-post_type menu-item-object-page  narrow"><a title="sign-up" href="<?php echo get_site_url(); ?>/my-account/" class=""><span class="item_outer"><span class="item_text">My Account</span></span></a></li>
        </ul>        
        <?php
    }else{
        ?>
        <ul id="menu-main-menu" class="clearfix">
            <li id="nav-menu-item-4374" class="menu-item menu-item-type-post_type menu-item-object-page  narrow"><a href="<?php echo get_site_url(); ?>/login/" class=""><span class="item_outer"><span class="item_text">Login</span></span></a></li>
            <li id="nav-menu-item-4373" class="menu-item menu-item-type-post_type menu-item-object-page  narrow"><a title="sign-up" href="<?php echo get_site_url(); ?>/sign-up/" class=""><span class="item_outer"><span class="item_text">Sign Up</span></span></a></li>
        </ul>        
        <?php
    }?>
		</div>
	</nav>
<?php } ?>

<?php do_action('mrseo_elated_after_mobile_navigation'); ?>