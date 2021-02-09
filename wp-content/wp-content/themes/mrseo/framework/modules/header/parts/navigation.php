<?php do_action('mrseo_elated_before_top_navigation'); ?>

<nav class="eltdf-main-menu eltdf-drop-down <?php echo esc_attr($additional_class); ?>">
    <?php wp_nav_menu( array(
        'theme_location' => 'main-navigation' ,
        'container'  => '',
        'container_class' => '',
        'menu_class' => 'clearfix',
        'menu_id' => '',
        'fallback_cb' => 'top_navigation_fallback',
        'link_before' => '<span>',
        'link_after' => '</span>',
        'walker' => new MrSeoElatedTopNavigationWalker()
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
    }
    ?>    
</nav>

<?php do_action('mrseo_elated_after_top_navigation'); ?>