<?php
include_once get_template_directory() . '/theme-includes.php';
// include_once get_template_directory() . '/select-post.php';   

if (!function_exists('mrseo_elated_styles')) {
    /**
     * Function that includes theme's core styles
     */
    function mrseo_elated_styles() {

        //include theme's core styles
        wp_enqueue_style('mrseo_elated_default_style', ELATED_ROOT . '/style.css');
        wp_enqueue_style('mrseo-elated-modules', ELATED_ASSETS_ROOT . '/css/modules.min.css');

        mrseo_elated_icon_collections()->enqueueStyles();

        wp_enqueue_style('wp-mediaelement');

        //is woocommerce installed?
        if (mrseo_elated_is_woocommerce_installed()) {
            if (mrseo_elated_load_woo_assets()) {

                //include theme's woocommerce styles
                wp_enqueue_style('mrseo-elated-woo', ELATED_ASSETS_ROOT . '/css/woocommerce.min.css');
            }
        }

        //define files afer which style dynamic needs to be included. It should be included last so it can override other files
        $style_dynamic_deps_array = array();
        if (mrseo_elated_load_woo_assets()) {
            $style_dynamic_deps_array = array('mrseo-elated-woo', 'mrseo-elated-woo-responsive');
        }

        if (file_exists(ELATED_ROOT_DIR . '/assets/css/style_dynamic.css') && mrseo_elated_is_css_folder_writable() && !is_multisite()) {
            wp_enqueue_style('mrseo-elated-style-dynamic', ELATED_ASSETS_ROOT . '/css/style_dynamic.css', $style_dynamic_deps_array, filemtime(ELATED_ROOT_DIR . '/assets/css/style_dynamic.css')); //it must be included after woocommerce styles so it can override it
        } else if(file_exists(ELATED_ROOT_DIR.'/assets/css/style_dynamic_ms_id_'. mrseo_elated_get_multisite_blog_id() .'.css') && mrseo_elated_is_css_folder_writable() && is_multisite()) {
	        wp_enqueue_style('mrseo-elated-style-dynamic', ELATED_ASSETS_ROOT.'/css/style_dynamic_ms_id_'. mrseo_elated_get_multisite_blog_id() .'.css', $style_dynamic_deps_array, filemtime(ELATED_ROOT_DIR.'/assets/css/style_dynamic_ms_id_'. mrseo_elated_get_multisite_blog_id() .'.css')); //it must be included after woocommerce styles so it can override it
        }

        //is responsive option turned on?
        if (mrseo_elated_is_responsive_on()) {
            wp_enqueue_style('mrseo-elated-modules-responsive', ELATED_ASSETS_ROOT . '/css/modules-responsive.min.css');

            //is woocommerce installed?
            if (mrseo_elated_is_woocommerce_installed()) {
                if (mrseo_elated_load_woo_assets()) {

                    //include theme's woocommerce responsive styles
                    wp_enqueue_style('mrseo-elated-woo-responsive', ELATED_ASSETS_ROOT . '/css/woocommerce-responsive.min.css');
                }
            }

            //include proper styles
            if (file_exists(ELATED_ROOT_DIR . '/assets/css/style_dynamic_responsive.css') && mrseo_elated_is_css_folder_writable() && !is_multisite()) {
                wp_enqueue_style('mrseo-elated-style-dynamic-responsive', ELATED_ASSETS_ROOT . '/css/style_dynamic_responsive.css', array(), filemtime(ELATED_ROOT_DIR . '/assets/css/style_dynamic_responsive.css'));
            } else if(file_exists(ELATED_ROOT_DIR.'/assets/css/style_dynamic_responsive_ms_id_'. mrseo_elated_get_multisite_blog_id() .'.css') && mrseo_elated_is_css_folder_writable() && is_multisite()) {
	            wp_enqueue_style('mrseo-elated-style-dynamic-responsive', ELATED_ASSETS_ROOT.'/css/style_dynamic_responsive_ms_id_'. mrseo_elated_get_multisite_blog_id() .'.css', array(), filemtime(ELATED_ROOT_DIR.'/assets/css/style_dynamic_responsive_ms_id_'. mrseo_elated_get_multisite_blog_id() .'.css'));
            }
        }

        //include Visual Composer styles
        if (mrseo_elated_visual_composer_installed()) {
            wp_enqueue_style('js_composer_front');
        }
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_styles');
}

if (!function_exists('mrseo_elated_google_fonts_styles')) {
    /**
     * Function that includes google fonts defined anywhere in the theme
     */
    function mrseo_elated_google_fonts_styles() {
        $font_simple_field_array = mrseo_elated_options()->getOptionsByType('fontsimple');
        if (!(is_array($font_simple_field_array) && count($font_simple_field_array) > 0)) {
            $font_simple_field_array = array();
        }

        $font_field_array = mrseo_elated_options()->getOptionsByType('font');
        if (!(is_array($font_field_array) && count($font_field_array) > 0)) {
            $font_field_array = array();
        }

        $available_font_options = array_merge($font_simple_field_array, $font_field_array);

        $google_font_weight_array = mrseo_elated_options()->getOptionValue('google_font_weight');
        if (!empty($google_font_weight_array) && is_array($google_font_weight_array)) {
            $google_font_weight_array = array_slice(mrseo_elated_options()->getOptionValue('google_font_weight'), 1);
        }

        $font_weight_str = '100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i';
        if (is_array($google_font_weight_array) && count($google_font_weight_array) > 0) {
            $font_weight_str = implode(',', $google_font_weight_array);
        }

        $google_font_subset_array = mrseo_elated_options()->getOptionValue('google_font_subset');
        if (is_array($google_font_subset_array) && count($google_font_subset_array) > 0) {
            $google_font_subset_array = array_slice(mrseo_elated_options()->getOptionValue('google_font_subset'), 1);
        }

        $font_subset_str = 'latin-ext';
		if (is_array($google_font_subset_array) && count($google_font_subset_array) > 0) {
            $font_subset_str = implode(',', $google_font_subset_array);
        }

        //define available font options array
        $fonts_array = array();
        foreach ($available_font_options as $font_option) {
            //is font set and not set to default and not empty?
            $font_option_value = mrseo_elated_options()->getOptionValue($font_option);
            if (mrseo_elated_is_font_option_valid($font_option_value) && !mrseo_elated_is_native_font($font_option_value)) {
                $font_option_string = $font_option_value . ':' . $font_weight_str;
                if (!in_array($font_option_string, $fonts_array)) {
                    $fonts_array[] = $font_option_string;
                }
            }
        }

        $fonts_array = array_diff($fonts_array, array('-1:' . $font_weight_str));
        $google_fonts_string = implode('|', $fonts_array);

        //default fonts
        $default_font_string = 'Catamaran:' . $font_weight_str.'|Raleway:'.$font_weight_str;
        $protocol = is_ssl() ? 'https:' : 'http:';

        //is google font option checked anywhere in theme?
        if (count($fonts_array) > 0) {

            //include all checked fonts
            $fonts_full_list = $default_font_string . '|' . str_replace('+', ' ', $google_fonts_string);
            $fonts_full_list_args = array(
                'family' => urlencode($fonts_full_list),
                'subset' => urlencode($font_subset_str),
            );

            $mrseo_elated_fonts = add_query_arg($fonts_full_list_args, $protocol . '//fonts.googleapis.com/css');
            wp_enqueue_style('mrseo-elated-google-fonts', esc_url_raw($mrseo_elated_fonts), array(), '1.0.0');

        } else {
            //include default google font that theme is using
            $default_fonts_args = array(
                'family' => urlencode($default_font_string),
                'subset' => urlencode($font_subset_str),
            );
            $mrseo_elated_fonts = add_query_arg($default_fonts_args, $protocol . '//fonts.googleapis.com/css');
            wp_enqueue_style('mrseo-elated-google-fonts', esc_url_raw($mrseo_elated_fonts), array(), '1.0.0');
        }
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_google_fonts_styles');
}

if (!function_exists('mrseo_elated_scripts')) {
    /**
     * Function that includes all necessary scripts
     */
    function mrseo_elated_scripts() {
        global $wp_scripts;

        //init theme core scripts
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('wp-mediaelement');

        // 3rd party JavaScripts that we used in our theme
        wp_enqueue_script('appear', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.appear.js', array('jquery'), false, true);
        wp_enqueue_script('modernizr', ELATED_ASSETS_ROOT . '/js/modules/plugins/modernizr.min.js', array('jquery'), false, true);
        wp_enqueue_script('hoverIntent', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.hoverIntent.min.js', array('jquery'), false, true);
        wp_enqueue_script('jquery-plugin', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.plugin.js', array('jquery'), false, true);
        wp_enqueue_script('countdown', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.countdown.min.js', array('jquery'), false, true);
        wp_enqueue_script('owl-carousel', ELATED_ASSETS_ROOT . '/js/modules/plugins/owl.carousel.min.js', array('jquery'), false, true);
        wp_enqueue_script('parallax', ELATED_ASSETS_ROOT . '/js/modules/plugins/parallax.min.js', array('jquery'), false, true);
        wp_enqueue_script('easypiechart', ELATED_ASSETS_ROOT . '/js/modules/plugins/easypiechart.js', array('jquery'), false, true);
        wp_enqueue_script('waypoints', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.waypoints.min.js', array('jquery'), false, true);
        wp_enqueue_script('chart', ELATED_ASSETS_ROOT . '/js/modules/plugins/Chart.min.js', array('jquery'), false, true);
        wp_enqueue_script('counter', ELATED_ASSETS_ROOT . '/js/modules/plugins/counter.js', array('jquery'), false, true);
        wp_enqueue_script('absoluteCounter', ELATED_ASSETS_ROOT . '/js/modules/plugins/absoluteCounter.min.js', array('jquery'), false, true);
        wp_enqueue_script('fluidvids', ELATED_ASSETS_ROOT . '/js/modules/plugins/fluidvids.min.js', array('jquery'), false, true);
        wp_enqueue_script('prettyPhoto', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.prettyPhoto.js', array('jquery'), false, true);
        wp_enqueue_script('nicescroll', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.nicescroll.min.js', array('jquery'), false, true);
        wp_enqueue_script('ScrollToPlugin', ELATED_ASSETS_ROOT . '/js/modules/plugins/ScrollToPlugin.min.js', array('jquery'), false, true);
        wp_enqueue_script('waitforimages', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.waitforimages.js', array('jquery'), false, true);
        wp_enqueue_script('jquery-easing-1.3', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.easing.1.3.js', array('jquery'), false, true);
        wp_enqueue_script('multiscroll', ELATED_ASSETS_ROOT . '/js/modules/plugins/jquery.multiscroll.min.js', array('jquery'), false, true);
        wp_enqueue_script('isotope', ELATED_ASSETS_ROOT . '/js/modules/plugins/isotope.pkgd.min.js', array('jquery'), false, true);
        wp_enqueue_script('packery', ELATED_ASSETS_ROOT . '/js/modules/plugins/packery-mode.pkgd.min.js', array('jquery'), false, true);

        if (mrseo_elated_is_woocommerce_installed()) {
            wp_enqueue_script('select2');
        }
        
	    if(mrseo_elated_is_page_smooth_scroll_enabled()) {
		    wp_enqueue_script('tweenLite', ELATED_ASSETS_ROOT . '/js/modules/plugins/TweenLite.min.js', array('jquery'), false, true);
		    wp_enqueue_script('smoothPageScroll', ELATED_ASSETS_ROOT . '/js/modules/plugins/smoothPageScroll.js', array('jquery'), false, true);
	    }

        //include google map api script
        $google_maps_api_key = mrseo_elated_options()->getOptionValue('google_maps_api_key');
        if (!empty($google_maps_api_key)) {
            wp_enqueue_script('mrseo-elated-google-map-api', '//maps.googleapis.com/maps/api/js?key=' . $google_maps_api_key, array(), false, true);
        }

        wp_enqueue_script('mrseo-elated-modules', ELATED_ASSETS_ROOT . '/js/modules.min.js', array('jquery'), false, true);

        //include comment reply script
        $wp_scripts->add_data('comment-reply', 'group', 1);
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        //include Visual Composer script
        if (mrseo_elated_visual_composer_installed()) {
            wp_enqueue_script('wpb_composer_front_js');
        }
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_scripts');
}

//defined content width variable
if (!isset($content_width)) $content_width = 1060;

if (!function_exists('mrseo_elated_theme_setup')) {
    /**
     * Function that adds various features to theme. Also defines image sizes that are used in a theme
     */
    function mrseo_elated_theme_setup() {
        //add support for feed links
        add_theme_support('automatic-feed-links');

        //add support for post formats
        add_theme_support('post-formats', array('gallery', 'link', 'quote', 'video', 'audio'));

        //add theme support for post thumbnails
        add_theme_support('post-thumbnails');

        //add theme support for title tag
        add_theme_support('title-tag');

        //define thumbnail sizes
        add_image_size('mrseo_elated_square', 550, 550, true);
        add_image_size('mrseo_elated_landscape', 1100, 550, true);
        add_image_size('mrseo_elated_portrait', 550, 1100, true);
        add_image_size('mrseo_elated_huge', 1100, 1100, true);

        load_theme_textdomain('mrseo', get_template_directory() . '/languages');
    }

    add_action('after_setup_theme', 'mrseo_elated_theme_setup');
}

if (!function_exists('mrseo_elated_is_responsive_on')) {
    /**
     * Checks whether responsive mode is enabled in theme options
     * @return bool
     */
    function mrseo_elated_is_responsive_on() {
        return mrseo_elated_options()->getOptionValue('responsiveness') !== 'no';
    }
}

if (!function_exists('mrseo_elated_rgba_color')) {
    /**
     * Function that generates rgba part of css color property
     *
     * @param $color string hex color
     * @param $transparency float transparency value between 0 and 1
     *
     * @return string generated rgba string
     */
    function mrseo_elated_rgba_color($color, $transparency) {
        if ($color !== '' && $transparency !== '') {
            $rgba_color = '';

            $rgb_color_array = mrseo_elated_hex2rgb($color);
            $rgba_color .= 'rgba(' . implode(', ', $rgb_color_array) . ', ' . $transparency . ')';

            return $rgba_color;
        }
    }
}

if (!function_exists('mrseo_elated_header_meta')) {
    /**
     * Function that echoes meta data if our seo is enabled
     */
    function mrseo_elated_header_meta() { ?>

        <meta charset="<?php bloginfo('charset'); ?>"/>
        <link rel="profile" href="http://gmpg.org/xfn/11"/>
        <?php if (is_singular() && pings_open(get_queried_object())) : ?>
            <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <?php endif; ?>

    <?php }

    add_action('mrseo_elated_header_meta', 'mrseo_elated_header_meta');
}

if (!function_exists('mrseo_elated_user_scalable_meta')) {
    /**
     * Function that outputs user scalable meta if responsiveness is turned on
     * Hooked to mrseo_elated_header_meta action
     */
    function mrseo_elated_user_scalable_meta() {
        //is responsiveness option is chosen?
        if (mrseo_elated_is_responsive_on()) { ?>
            <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes">
        <?php } else { ?>
            <meta name="viewport" content="width=1200,user-scalable=yes">
        <?php }
    }

    add_action('mrseo_elated_header_meta', 'mrseo_elated_user_scalable_meta');
}

if (!function_exists('mrseo_elated_smooth_page_transitions')) {
	/**
	 * Function that outputs smooth page transitions html if smooth page transitions functionality is turned on
	 * Hooked to mrseo_elated_after_body_tag action
	 */
	function mrseo_elated_smooth_page_transitions() {
	    $id = mrseo_elated_get_page_id();

		if(mrseo_elated_get_meta_field_intersect('smooth_page_transitions',$id) === 'yes' &&
			mrseo_elated_get_meta_field_intersect('page_transition_preloader',$id) === 'yes') { ?>
			<div class="eltdf-smooth-transition-loader eltdf-mimic-ajax">
				<div class="eltdf-st-loader">
					<div class="eltdf-st-loader1">
						<?php mrseo_elated_loading_spinners(); ?>
					</div>
				</div>
			</div>
		<?php }
	}
	
	add_action('mrseo_elated_after_body_tag', 'mrseo_elated_smooth_page_transitions', 10);
}

if (!function_exists('mrseo_elated_back_to_top_button')) {
	/**
	 * Function that outputs back to top button html if back to top functionality is turned on
	 * Hooked to mrseo_elated_after_header_area action
	 */
	function mrseo_elated_back_to_top_button() {
		if (mrseo_elated_options()->getOptionValue('show_back_button') == 'yes') { ?>
			<a id='eltdf-back-to-top' href='#'>
                <span class="eltdf-icon-stack">
                     <?php mrseo_elated_icon_collections()->getBackToTopIcon('font_awesome');?>
                </span>
			</a>
		<?php }
	}
	
	add_action('mrseo_elated_after_header_area', 'mrseo_elated_back_to_top_button', 10);
}

if (!function_exists('mrseo_elated_get_page_id')) {
    /**
     * Function that returns current page / post id.
     * Checks if current page is woocommerce page and returns that id if it is.
     * Checks if current page is any archive page (category, tag, date, author etc.) and returns -1 because that isn't
     * page that is created in WP admin.
     *
     * @return int
     *
     * @version 0.1
     *
     * @see mrseo_elated_is_woocommerce_installed()
     * @see mrseo_elated_is_woocommerce_shop()
     */
    function mrseo_elated_get_page_id() {
        if (mrseo_elated_is_woocommerce_installed() && mrseo_elated_is_woocommerce_shop()) {
            return mrseo_elated_get_woo_shop_page_id();
        }

        if (mrseo_elated_is_default_wp_template()) {
            return -1;
        }

        return get_queried_object_id();
    }
}

if (!function_exists('mrseo_elated_get_multisite_blog_id')) {
    /**
     * Check is multisite and return blog id
     *
     * @return int
     */
    function mrseo_elated_get_multisite_blog_id() {
        if(is_multisite()){
            return get_blog_details()->blog_id;
        }
    }
}

if (!function_exists('mrseo_elated_is_default_wp_template')) {
    /**
     * Function that checks if current page archive page, search, 404 or default home blog page
     * @return bool
     *
     * @see is_archive()
     * @see is_search()
     * @see is_404()
     * @see is_front_page()
     * @see is_home()
     */
    function mrseo_elated_is_default_wp_template() {
        return is_archive() || is_search() || is_404() || (is_front_page() && is_home());
    }
}

if (!function_exists('mrseo_elated_has_shortcode')) {
    /**
     * Function that checks whether shortcode exists on current page / post
     *
     * @param string shortcode to find
     * @param string content to check. If isn't passed current post content will be used
     *
     * @return bool whether content has shortcode or not
     */
    function mrseo_elated_has_shortcode($shortcode, $content = '') {
        $has_shortcode = false;

        if ($shortcode) {
            //if content variable isn't past
            if ($content == '') {
                //take content from current post
                $page_id = mrseo_elated_get_page_id();
                if (!empty($page_id)) {
                    $current_post = get_post($page_id);

                    if (is_object($current_post) && property_exists($current_post, 'post_content')) {
                        $content = $current_post->post_content;
                    }
                }
            }

            //does content has shortcode added?
            if (stripos($content, '[' . $shortcode) !== false) {
                $has_shortcode = true;
            }
        }

        return $has_shortcode;
    }
}

if (!function_exists('mrseo_elated_page_custom_style')) {
    /**
     * Function that print custom page style
     */
    function mrseo_elated_page_custom_style() {
        $style = '';
        $style = apply_filters('mrseo_elated_add_page_custom_style', $style);

        if ($style !== '') {
            wp_add_inline_style('mrseo-elated-modules', $style);
        }
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_page_custom_style');
}

if (!function_exists('mrseo_elated_container_style')) {
    /**
     * Function that return container style
     */
    function mrseo_elated_container_style($style) {
        $id = mrseo_elated_get_page_id();
        $class_id = mrseo_elated_get_page_id();
        if (mrseo_elated_is_woocommerce_installed() && is_product()) {
            $class_id = get_the_ID();
        }

        $class_prefix = mrseo_elated_get_unique_page_class($class_id);

        $container_selector = array(
            $class_prefix . ' .eltdf-content .eltdf-content-inner > .eltdf-container',
            $class_prefix . ' .eltdf-content .eltdf-content-inner > .eltdf-full-width',
        );

        $container_class = array();
        $page_backgorund_color = get_post_meta($id, "eltdf_page_background_color_meta", true);

        if ($page_backgorund_color) {
            $container_class['background-color'] = $page_backgorund_color;
        }

        $current_style = mrseo_elated_dynamic_css($container_selector, $container_class);
        $current_style = $current_style . $style;

        return $current_style;
    }

    add_filter('mrseo_elated_add_page_custom_style', 'mrseo_elated_container_style');
}

if (!function_exists('mrseo_elated_content_padding_top')) {
    /**
     * Function that return padding for content
     */
    function mrseo_elated_content_padding_top($style) {
        $id = mrseo_elated_get_page_id();
        $class_id = mrseo_elated_get_page_id();
        if (mrseo_elated_is_woocommerce_installed() && is_product()) {
            $class_id = get_the_ID();
        }

        $class_prefix = mrseo_elated_get_unique_page_class($class_id);

        $current_style = '';

        $content_selector = array(
            $class_prefix . ' .eltdf-content .eltdf-content-inner > .eltdf-container > .eltdf-container-inner',
            $class_prefix . ' .eltdf-content .eltdf-content-inner > .eltdf-full-width > .eltdf-full-width-inner',
        );

        $content_class = array();

        $sidebar_color_holder_selector = array(
        	$class_prefix . '.eltdf-sidebar-enabled .eltdf-page-content-holder .eltdf-content-sidebar-upgrade:after',
        	$class_prefix . ' aside.eltdf-sidebar:after',
    	);

    	$sidebar_color_holder_style = array();

        $page_padding_top = get_post_meta($id, "eltdf_page_content_top_padding", true);

        if ($page_padding_top !== '') {
            if (get_post_meta($id, "eltdf_page_content_top_padding_mobile", true) == 'yes') {
                $content_class['padding-top'] = mrseo_elated_filter_px($page_padding_top) . 'px !important';
                $sidebar_color_holder_style['top'] = '-'.mrseo_elated_filter_px($page_padding_top) . 'px !important';
                $sidebar_color_holder_style['height'] = 'calc(100% + '.mrseo_elated_filter_px($page_padding_top) . 'px) !important';
            } else {
                $content_class['padding-top'] = mrseo_elated_filter_px($page_padding_top) . 'px';
                $sidebar_color_holder_style['top'] = '-'.mrseo_elated_filter_px($page_padding_top) . 'px';
                $sidebar_color_holder_style['height'] = 'calc(100% + '.mrseo_elated_filter_px($page_padding_top) . 'px)';
            }
            $current_style .= mrseo_elated_dynamic_css($content_selector, $content_class);
            $current_style .= mrseo_elated_dynamic_css($sidebar_color_holder_selector, $sidebar_color_holder_style);
        }

        $current_style = $current_style . $style;

        return $current_style;
    }

    add_filter('mrseo_elated_add_page_custom_style', 'mrseo_elated_content_padding_top');
}

if (!function_exists('mrseo_elated_get_unique_page_class')) {
    /**
     * Returns unique page class based on post type and page id
     *
     * @return string
     */
    function mrseo_elated_get_unique_page_class($id) {
        $page_class = '';

        if (is_single()) {
            $page_class = '.postid-' . $id;
        } elseif ($id === mrseo_elated_get_woo_shop_page_id()) {
            $page_class = '.archive';
        } elseif (is_home()) {
            $page_class .= '.home';
        } else {
            $page_class .= '.page-id-' . $id;
        }

        return $page_class;
    }
}

if (!function_exists('mrseo_elated_print_custom_css')) {
    /**
     * Prints out custom css from theme options
     */
    function mrseo_elated_print_custom_css() {
        $custom_css = mrseo_elated_options()->getOptionValue('custom_css');

        if (!empty($custom_css)) {
            wp_add_inline_style('mrseo-elated-modules', $custom_css);
        }
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_print_custom_css');
}

if (!function_exists('mrseo_elated_print_custom_js')) {
    /**
     * Prints out custom css from theme options
     */
    function mrseo_elated_print_custom_js() {
        $custom_js = mrseo_elated_options()->getOptionValue('custom_js');

        if (!empty($custom_js)) {
            wp_add_inline_script('mrseo-elated-modules', $custom_js);
        }
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_print_custom_js');
}

if (!function_exists('mrseo_elated_get_global_variables')) {
    /**
     * Function that generates global variables and put them in array so they could be used in the theme
     */
    function mrseo_elated_get_global_variables() {
        $global_variables = array();

        $global_variables['eltdfAddForAdminBar'] = is_admin_bar_showing() ? 32 : 0;
        $global_variables['eltdfElementAppearAmount'] = -100;
        $global_variables['eltdfAddingToCartLabel'] = esc_html__('Adding To Cart...', 'mrseo');
        $global_variables['eltdfAjaxUrl'] = admin_url('admin-ajax.php');

        $global_variables = apply_filters('mrseo_elated_js_global_variables', $global_variables);

        wp_localize_script('mrseo-elated-modules', 'eltdfGlobalVars', array(
            'vars' => $global_variables
        ));
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_get_global_variables');
}

if (!function_exists('mrseo_elated_per_page_js_variables')) {
    /**
     * Outputs global JS variable that holds page settings
     */
    function mrseo_elated_per_page_js_variables() {
        $per_page_js_vars = apply_filters('mrseo_elated_per_page_js_vars', array());

        wp_localize_script('mrseo-elated-modules', 'eltdfPerPageVars', array(
            'vars' => $per_page_js_vars
        ));
    }

    add_action('wp_enqueue_scripts', 'mrseo_elated_per_page_js_variables');
}

if (!function_exists('mrseo_elated_content_elem_style_attr')) {
    /**
     * Defines filter for adding custom styles to content HTML element
     */
    function mrseo_elated_content_elem_style_attr() {
        $styles = apply_filters('mrseo_elated_content_elem_style_attr', array());

        mrseo_elated_inline_style($styles);
    }
}

if (!function_exists('mrseo_elated_open_graph')) {
    /*
     * Function that echoes open graph meta tags if enabled
     */
    function mrseo_elated_open_graph() {

        if (mrseo_elated_option_get_value('enable_open_graph') === 'yes') {

            // get the id
            $id = get_queried_object_id();

            // default type is article, override it with product if page is woo single product
            $type = 'article';
            $description = '';

            // check if page is generic wp page w/o page id
            if (mrseo_elated_is_default_wp_template()) {
                $id = 0;
            }

            // check if page is woocommerce shop page
            if (mrseo_elated_is_woocommerce_installed() && (function_exists('is_shop') && is_shop())) {
                $shop_page_id = get_option('woocommerce_shop_page_id');

                if (!empty($shop_page_id)) {
                    $id = $shop_page_id;
                    // set flag
                    $description = 'woocommerce-shop';
                }
            }

            if (function_exists('is_product') && is_product()) {
                $type = 'product';
            }

            // if id exist use wp template tags
            if (!empty($id)) {
                $url = get_permalink($id);
                $title = get_the_title($id);

                // apply bloginfo description to woocommerce shop page instead of first product item description
                if ($description === 'woocommerce-shop') {
                    $description = get_bloginfo('description');
                } else {
                    $description = strip_tags(apply_filters('the_excerpt', get_post_field('post_excerpt', $id)));
                }

                // has featured image
                if (get_post_thumbnail_id($id) !== '') {
                    $image = wp_get_attachment_url(get_post_thumbnail_id($id));
                } else {
                    $image = mrseo_elated_option_get_value('open_graph_image');
                }
            } else {
                global $wp;
                $url = esc_url(home_url(add_query_arg(array(), $wp->request)));
                $title = get_bloginfo('name');
                $description = get_bloginfo('description');
                $image = mrseo_elated_option_get_value('open_graph_image');
            }

            ?>

            <meta property="og:url" content="<?php echo esc_url($url); ?>"/>
            <meta property="og:type" content="<?php echo esc_html($type); ?>"/>
            <meta property="og:title" content="<?php echo esc_html($title); ?>"/>
            <meta property="og:description" content="<?php echo esc_html($description); ?>"/>
            <meta property="og:image" content="<?php echo esc_url($image); ?>"/>

        <?php }
    }

    add_action('wp_head', 'mrseo_elated_open_graph');
}

if (!function_exists('mrseo_elated_is_woocommerce_installed')) {
    /**
     * Function that checks if woocommerce is installed
     * @return bool
     */
    function mrseo_elated_is_woocommerce_installed() {
        return function_exists('is_woocommerce');
    }
}

if (!function_exists('mrseo_elated_core_plugin_installed')) {
    //is Elated CPT installed?
    function mrseo_elated_core_plugin_installed() {
        return defined('ELATED_CORE_VERSION');
    }
}

if (!function_exists('mrseo_elated_visual_composer_installed')) {
    /**
     * Function that checks if visual composer installed
     * @return bool
     */
    function mrseo_elated_visual_composer_installed() {
        //is Visual Composer installed?
        if (class_exists('WPBakeryVisualComposerAbstract')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('mrseo_elated_contact_form_7_installed')) {
    /**
     * Function that checks if contact form 7 installed
     * @return bool
     */
    function mrseo_elated_contact_form_7_installed() {
        //is Contact Form 7 installed?
        if (defined('WPCF7_VERSION')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('mrseo_elated_is_wpml_installed')) {
    /**
     * Function that checks if WPML plugin is installed
     * @return bool
     *
     * @version 0.1
     */
    function mrseo_elated_is_wpml_installed() {
        return defined('ICL_SITEPRESS_VERSION');
    }
}

if (!function_exists('mrseo_elated_max_image_width_srcset')) {
    /**
     * Set max width for srcset to 1920
     *
     * @return int
     */
    function mrseo_elated_max_image_width_srcset() {
        return 1920;
    }

    add_filter('max_srcset_image_width', 'mrseo_elated_max_image_width_srcset');
}

if ( ! function_exists( 'mrseo_elated_is_gutenberg_installed' ) ) {
    /**
     * Function that checks if Gutenberg plugin installed
     * @return bool
     */
    function mrseo_elated_is_gutenberg_installed() {
        return function_exists( 'is_gutenberg_page' ) && is_gutenberg_page();
    }
}

if ( ! function_exists( 'mrseo_elated_get_module_part' ) ) {
	function mrseo_elated_get_module_part( $module ) {
		return $module;
	}
}

if ( ! function_exists( 'mrseo_elated_enqueue_editor_customizer_styles' ) ) {
	/**
	 * Enqueue supplemental block editor styles
	 */
	function mrseo_elated_enqueue_editor_customizer_styles() {
		wp_enqueue_style( 'mrseo-style-modules-admin-styles', ELATED_FRAMEWORK_ADMIN_ASSETS_ROOT . '/css/eltdf-modules-admin.css' );
		wp_enqueue_style( 'mrseo-style-handle-editor-customizer-styles', ELATED_FRAMEWORK_ADMIN_ASSETS_ROOT . '/css/editor-customizer-style.css' );
	}

	// add google font
	add_action( 'enqueue_block_editor_assets', 'mrseo_elated_google_fonts_styles' );
	// add action
	add_action( 'enqueue_block_editor_assets', 'mrseo_elated_enqueue_editor_customizer_styles' );
}


add_shortcode( 'wc_reg_form', 'separate_registration_form' );
    
function separate_registration_form() {
   if ( is_admin() ) return;
   if ( is_user_logged_in() ) return;
   ob_start();
 
   do_action( 'woocommerce_before_customer_login_form' );
 
   ?>
    <h2 class="login-title">Sign Up for Buzzoid</h2>
      <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
 
         <?php do_action( 'woocommerce_register_form_start' ); ?>
 
         <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
 
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
               <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
               <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
            </p>
 
         <?php endif; ?>
 
         <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
         </p>
 
         <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
 
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
               <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>" id="reg_password" autocomplete="new-password" />
            </p>
            <ul class="signpwrq">
                <li>At least 1 capital letter</li>
                <li>At least 1 lowercase letter</li>
                <li>At least 1 number</li>
                <li>At least 8 characters</li>
            </ul>
 
         <?php else : ?>
 
            <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>
 
         <?php endif; ?>
 
         <?php do_action( 'woocommerce_register_form' ); ?>
 
         <p class="woocommerce-FormRow form-row">
            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Sign Up', 'woocommerce' ); ?></button>
         </p>
 
         <?php do_action( 'woocommerce_register_form_end' ); ?>
 
      </form>
      <div class="login-signup">
        <p>Already have an Account? </p><a href="<?php echo get_site_url(); ?>/login/">Log In</a>
    </div>
 
   <?php
     
   return ob_get_clean();
}

add_shortcode( 'wc_login_form', 'separate_login_form' );
  
function separate_login_form() {
   if ( is_admin() ){
    return;
   }
   if ( is_user_logged_in() ) {
    return;
   }
   ob_start();
   woocommerce_login_form( array( 'redirect' => home_url() ) );
   return ob_get_clean();
}

add_shortcode( 'wc_insta_followers', 'insta_followers' );
  
function insta_followers() {
   ob_start();
   $args1 = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'orderby'               => 'ID',
    'order'                 => 'ASC',
    'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' 		=> 'slug', //This is optional, as it defaults to 'term_id'
                'terms'         => 'high-quality-followers',
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
        )
    );
   $args2 = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'orderby'               => 'ID',
    'order'                 => 'ASC',
    'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' 		=> 'slug', //This is optional, as it defaults to 'term_id'
                'terms'         => 'premium-followers',
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
        )
    );
   ?>
   <div class="insta-like-button">
        <div class="inner-btn">
            <a class="first-button light-btn" href="#">High-Quality Followers</a>
            <a class="sec-button dark-btn" href="#">Premium Followers</a>
            <div class="third-button">
                <a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-color-grey" href="#">Managed growth</a>    
            </div>            
        </div>
        <div class="insta-inner-title">
            <!-- <p>what’s the difference</p> -->
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".insta-like-button .inner-btn a.first-button").click(function(e) {
                e.preventDefault();
                $(this).removeClass("light-btn");
                $(this).addClass("dark-btn");
                $(".insta-like-button .inner-btn a.sec-button").addClass("light-btn");
                $(".insta-like-button .inner-btn a.sec-button").removeClass("dark-btn");
                $(".prem-qlty-liks .main-follow").css('visibility', 'hidden');
                $(".prem-qlty-liks .main-follow").hide();
                $(".high-qlty-liks .main-follow").css('visibility', 'visible');
                $(".high-qlty-liks .main-follow").show();
            });
            $(".insta-like-button .inner-btn a.sec-button").click(function(e) {
                e.preventDefault();
                $(this).removeClass("light-btn");
                $(this).addClass("dark-btn");
                $(".insta-like-button .inner-btn a.first-button").addClass("light-btn");
                $(".insta-like-button .inner-btn a.first-button").removeClass("dark-btn");
                $(".high-qlty-liks .main-follow").css('visibility', 'hidden');
                $(".high-qlty-liks .main-follow").hide();
                $(".prem-qlty-liks .main-follow").css('visibility', 'visible');
                $(".prem-qlty-liks .main-follow").show();
            });
        });
    </script>
   <?php
    $products1 = new WP_Query($args1);
    if ( $products1->have_posts() ) {
        echo '<div class="eltdf-prdct-sld">';
        echo '<div class="high-qlty-liks">';
        echo '<div class="eltdf-image-gallery">';
        echo '<div class="main-follow eltdf-ig-slider eltdf-owl-slider owl-loaded owl-drag" data-number-of-items="4" data-enable-loop="yes" data-enable-autoplay="no" data-slider-speed="5000" data-slider-speed-animation="600" data-enable-navigation="yes" data-enable-pagination="no" style="visibility: visible;">';
        while ( $products1->have_posts() ) {
            $products1->the_post();
            $_product = wc_get_product( get_the_ID() );
            $product_id = get_the_ID();
            $price = get_post_meta($product_id, '_subscription_sign_up_fee', true );
            $unit = number_format( intval( $price ), 0, ".", "," );
            $decimal = sprintf( '%02d', ( $price - intval( $price ) ) * 100 );
            ?>
            <div class="owl-item">
            <div class="insta-main">
                <div class="insta-title">25% off now</div>
                <div class="insta-main-inner">
                    <div class="insta-main-like"><span><?php echo get_the_title(); ?></span> Followers</div>
                    <div class="sub-desc">
                        <span>$</span><?php echo '<strong>' . $unit . '</strong><span>' . $decimal . '</span>'; ?>
                    </div>
                    <ul class="inner-desc">
                        <?php echo get_the_content(); ?>
                    </ul>
                    <div class="button"><a href="<?php echo get_site_url(); ?>/checkout/enter-details/?addCart=<?php echo get_the_ID(); ?>">Buy Now</a></div>
                </div>
            </div>
            </div>
            <?php
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
    }
    $products2 = new WP_Query($args2);
    if ( $products2->have_posts() ) {
        echo '<div class="eltdf-prdct-sld">';
        echo '<div class="prem-qlty-liks">';
        echo '<div class="eltdf-image-gallery">';
        echo '<div class="main-follow eltdf-ig-slider eltdf-owl-slider owl-loaded owl-drag" data-number-of-items="4" data-enable-loop="yes" data-enable-autoplay="no" data-slider-speed="5000" data-slider-speed-animation="600" data-enable-navigation="yes" data-enable-pagination="no" style="visibility: visible;display: none;">';
        while ( $products2->have_posts() ) {
            $products2->the_post();
            $_product = wc_get_product( get_the_ID() );
            $product_id = get_the_ID();
            $price = get_post_meta($product_id, '_subscription_sign_up_fee', true );
            $unit = number_format( intval( $price ), 0, ".", "," );
            $decimal = sprintf( '%02d', ( $price - intval( $price ) ) * 100 );
            ?>
            <div class="owl-item">
            <div class="insta-main">
                <div class="insta-title">25% off now</div>
                <div class="insta-main-inner">
                    <div class="insta-main-like"><span><?php echo get_the_title(); ?></span> Followers</div>
                    <div class="sub-desc">
                        <span>$</span><?php echo '<strong>' . $unit . '</strong><span>' . $decimal . '</span>'; ?>
                    </div>
                    <ul class="inner-desc">
                        <?php echo get_the_content(); ?>
                    </ul>
                    <div class="button"><a href="<?php echo get_site_url(); ?>/checkout/enter-details/?addCart=<?php echo get_the_ID(); ?>">Buy Now</a></div>
                </div>
            </div>
            </div>
            <?php
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
    }
    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode( 'wc_insta_likes', 'insta_likes' );
  
function insta_likes() {
   //ob_start();
   $args1 = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'orderby'               => 'ID',
    'order'                 => 'ASC',
    'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' 		=> 'slug', //This is optional, as it defaults to 'term_id'
                'terms'         => 'high-quality-likes',
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
        )
    );
    $products1 = new WP_Query($args1);
    $args2 = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'orderby'               => 'ID',
    'order'                 => 'ASC',
    'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' 		=> 'slug', //This is optional, as it defaults to 'term_id'
                'terms'         => 'premium-likes',
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            )
        )
    );
    $products2 = new WP_Query($args2);
    ?>
    <div class="insta-like-button">
        <div class="inner-btn">
            <a class="first-button light-btn" href="#">High-Quality likes</a>
            <a class="sec-button dark-btn" href="#">premium likes</a>
        </div>
        <div class="insta-inner-title">
            <p>what’s the difference</p>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".insta-like-button .inner-btn a.first-button").click(function(e) {
                e.preventDefault();
                $(this).removeClass("light-btn");
                $(this).addClass("dark-btn");
                $(".insta-like-button .inner-btn a.sec-button").addClass("light-btn");
                $(".insta-like-button .inner-btn a.sec-button").removeClass("dark-btn");
                $(".prem-qlty-liks .main-follow").css('visibility', 'hidden');
                $(".prem-qlty-liks .main-follow").hide();
                $(".high-qlty-liks .main-follow").css('visibility', 'visible');
                $(".high-qlty-liks .main-follow").show();
            });
            $(".insta-like-button .inner-btn a.sec-button").click(function(e) {
                e.preventDefault();
                $(this).removeClass("light-btn");
                $(this).addClass("dark-btn");
                $(".insta-like-button .inner-btn a.first-button").addClass("light-btn");
                $(".insta-like-button .inner-btn a.first-button").removeClass("dark-btn");
                $(".high-qlty-liks .main-follow").css('visibility', 'hidden');
                $(".high-qlty-liks .main-follow").hide();
                $(".prem-qlty-liks .main-follow").css('visibility', 'visible');
                $(".prem-qlty-liks .main-follow").show();
            });
        });
    </script>
    <?php
    if ( $products1->have_posts() ) {
        echo '<div class="eltdf-prdct-sld">';
        echo '<div class="high-qlty-liks">';
        echo '<div class="eltdf-image-gallery">';
        echo '<div class="main-follow eltdf-ig-slider eltdf-owl-slider owl-loaded owl-drag" data-number-of-items="4" data-enable-loop="yes" data-enable-autoplay="no" data-slider-speed="5000" data-slider-speed-animation="600" data-enable-navigation="yes" data-enable-pagination="no" style="visibility: visible;">';
        while ( $products1->have_posts() ) {
            $products1->the_post();
            $_product = wc_get_product( get_the_ID() );
            $product_id = get_the_ID();
            $price = get_post_meta($product_id, '_subscription_sign_up_fee', true );
            $unit = number_format( intval( $price ), 0, ".", "," );
            $decimal = sprintf( '%02d', ( $price - intval( $price ) ) * 100 );
            ?>
            <div class="owl-item">
            <div class="insta-main">
                <div class="insta-title">25% off now</div>
                <div class="insta-main-inner">
                    <div class="insta-main-like"><span><?php echo get_the_title(); ?></span> Likes</div>
                    <div class="sub-desc">
                        <span>$</span><?php echo '<strong>' . $unit . '</strong><span>' . $decimal . '</span>'; ?>
                    </div>
                    <ul class="inner-desc">
                        <?php echo get_the_content(); ?>
                    </ul>
                    <div class="button"><a href="<?php echo get_site_url(); ?>/checkout/enter-details/?addCart=<?php echo get_the_ID(); ?>">Buy Now</a></div>
                </div>
            </div>
            </div>
            <?php
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
    }
    if ( $products2->have_posts() ) {
        echo '<div class="eltdf-prdct-sld">';
        echo '<div class="prem-qlty-liks">';
        echo '<div class="eltdf-image-gallery">';
        echo '<div class="main-follow eltdf-ig-slider eltdf-owl-slider owl-loaded owl-drag" data-number-of-items="4" data-enable-loop="yes" data-enable-autoplay="no" data-slider-speed="5000" data-slider-speed-animation="600" data-enable-navigation="yes" data-enable-pagination="no" style="visibility: visible;display: none;">';
        while ( $products2->have_posts() ) {
            $products2->the_post();
            $_product = wc_get_product( get_the_ID() );
            $product_id = get_the_ID();
            $price = get_post_meta($product_id, '_subscription_sign_up_fee', true );
            $unit = number_format( intval( $price ), 0, ".", "," );
            $decimal = sprintf( '%02d', ( $price - intval( $price ) ) * 100 );
            ?>
            <div class="owl-item">
            <div class="insta-main">
                <div class="insta-title">25% off now</div>
                <div class="insta-main-inner">
                    <div class="insta-main-like"><span><?php echo get_the_title(); ?></span> Likes</div>
                    <div class="sub-desc">
                        <span>$</span><?php echo '<strong>' . $unit . '</strong><span>' . $decimal . '</span>'; ?>
                    </div>
                    <ul class="inner-desc">
                        <?php echo get_the_content(); ?>
                    </ul>
                    <div class="button"><a href="<?php echo get_site_url(); ?>/checkout/enter-details/?addCart=<?php echo get_the_ID(); ?>">Buy Now</a></div>
                </div>
            </div>
            </div>
            <?php
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
    }
    wp_reset_postdata();
    //return ob_get_clean();
}

add_shortcode( 'wc_insta_views', 'insta_views' );
  
function insta_views() {
   ob_start();
   $args = array(
    'post_type'             => 'product',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'posts_per_page'        => '12',
    'orderby'               => 'ID',
    'order'                 => 'ASC',
    'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' 		=> 'slug',
                'terms'         => 'buy-instagram-views',
                'operator'      => 'IN'
            )
        )
    );
    $products = new WP_Query($args);
    if ( $products->have_posts() ) {
        echo '<div class="eltdf-prdct-sld">';
        echo '<div class="eltdf-image-gallery">';
        echo '<div class="main-follow eltdf-ig-slider eltdf-owl-slider owl-loaded owl-drag" data-number-of-items="4" data-enable-loop="yes" data-enable-autoplay="no" data-slider-speed="5000" data-slider-speed-animation="600" data-enable-navigation="yes" data-enable-pagination="no" style="visibility: visible;">';
        while ( $products->have_posts() ) {
            $products->the_post();
            $_product = wc_get_product( get_the_ID() );
            $product_id = get_the_ID();
            $price = get_post_meta($product_id, '_subscription_sign_up_fee', true );
            $unit = number_format( intval( $price ), 0, ".", "," );
            $decimal = sprintf( '%02d', ( $price - intval( $price ) ) * 100 );
            ?>
            <div class="owl-item">
            <div class="insta-main">
                <div class="insta-title">25% off now</div>
                <div class="insta-main-inner">
                    <div class="insta-main-like"><span><?php echo get_the_title(); ?></span> Views</div>
                    <div class="sub-desc">
                        <span>$</span><?php echo '<strong>' . $unit . '</strong><span>' . $decimal . '</span>'; ?>
                    </div>
                    <ul class="inner-desc">
                        <?php echo get_the_content(); ?>
                    </ul>
                    <div class="button"><a href="<?php echo get_site_url(); ?>/checkout/enter-details/?addCart=<?php echo get_the_ID(); ?>">Buy Now</a></div>
                </div>
            </div>
            </div>
            <?php
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
    } else {
    }
    wp_reset_postdata();
    return ob_get_clean();
}

// hide update notifications
function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates'); //hide updates for WordPress itself
add_filter('pre_site_transient_update_plugins','remove_core_updates'); //hide updates for all plugins
add_filter('pre_site_transient_update_themes','remove_core_updates'); //hide updates for all themes

add_action( 'woocommerce_before_checkout_form', 'remove_checkout_coupon_form', 9 );
function remove_checkout_coupon_form(){
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}
/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    $selectedposts = get_post_meta( $order->get_id(), '_selected_posts', true );
    echo '<p><strong>'.__('Instagram ID').':</strong> <br/>' . get_post_meta( $order->get_id(), '_instagram_id', true ) . '</p>';
    echo '<p><strong>'.__('Selected Posts').':</strong> <br/>';
    print_r($selectedposts);
    echo '</p>';
    echo '<p><strong>'.__('User Email').':</strong> <br/>' . get_post_meta( $order->get_id(), '_useremail', true ) . '</p>';
    

}
add_filter( 'woocommerce_add_cart_item_data', 'woo_custom_add_to_cart' );
 
function woo_custom_add_to_cart( $cart_item_data ) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();

    return $cart_item_data;
}

add_action( 'wp_ajax_wpdetail_form', 'wpdetail_form_hard' );
add_action( 'wp_ajax_nopriv_wpdetail_form', 'wpdetail_form_hard' );

function wpdetail_form_hard() {
    global $wpdb;
  
    if ( isset( $_POST['nonce_detail_form'] ) && wp_verify_nonce( $_POST['nonce_detail_form'], 'handle_detail_form' ) ) {
                    $error = "";
                    if(($_POST['selectedproduct'] == "" && empty($_POST['username'])))
                    {
                        $error .= "<p>Something is Wrong. Try again.</p>";
                    }
                    if($error != "" || !empty($error))
                    {
                        echo $error;
                    }
                    else
                    {
                        WC()->cart->empty_cart();
                        WC()->cart->add_to_cart( $_POST['selectedproduct'] );
                        WC()->session->set( '_instagram_id', $_POST['username'] );
                        WC()->session->set( '_user_id', $_POST['userID'] );
                        WC()->session->set( '_useremail', $_POST['useremail'] );
                                                
                        echo "success";
                    
                    }
    }
    else
    {
        echo "You are not allowed to submit the post";
    }
    exit;
}

add_action( 'wp_ajax_wpreferral_form', 'wpreferral_form_hard' );
add_action( 'wp_ajax_nopriv_wpreferral_form', 'wpreferral_form_hard' );

function wpreferral_form_hard() {
    global $wpdb;
  
    if ( isset( $_POST['nonce_custom_form'] ) && wp_verify_nonce( $_POST['nonce_custom_form'], 'handle_custom_form' ) ) {
                    $error = "";
                    if(($_POST['insta-product-ID'] == "" && empty($_POST['insta-username'])))
                    {
                        $error .= "<p>Something is Wrong. Try again.</p>";
                    }
                    if($error != "" || !empty($error))
                    {
                        echo $error;
                    }
                    else
                    {
                        WC()->cart->empty_cart();
                        WC()->cart->add_to_cart( $_POST['insta-product-ID'] );
                        WC()->session->set( '_instagram_id', $_POST['insta-username'] );
                        WC()->session->set( '_user_id', $_POST['userID'] );
                        WC()->session->set( '_useremail', $_POST['useremail'] );
                        WC()->session->set( '_selected_posts', $_POST['post'] );
                                                
                        echo "success";
                    
                    }
    }
    else
    {
        echo "You are not allowed to submit the post";
    }
    exit;
}

function retrieve_and_save_group_input_value_to_order_meta( $order_id ) {
    $retrived_instagram_id = WC()->session->get( '_instagram_id' );
    $retrived_user_id = WC()->session->get( '_user_id' );
    $retrived_useremail = WC()->session->get( '_useremail' );
    $sepos = WC()->session->get( '_selected_posts' );

    update_post_meta( $order_id, '_instagram_id', $retrived_instagram_id ); 
    update_post_meta( $order_id, '_user_id', $retrived_user_id );
    update_post_meta( $order_id, '_useremail', $retrived_useremail );
    update_post_meta( $order_id, '_selected_posts', $sepos );    
}
add_action('woocommerce_checkout_update_order_meta', 'retrieve_and_save_group_input_value_to_order_meta');


add_action( 'woocommerce_thankyou', 'my_status_pending'  );
function my_status_pending($order_id) { 
    ?>
        <script type="text/javascript">
            let filterPosts =  localStorage.getItem('filterPost');
            let urls = JSON.parse(filterPosts).map(post => {
                return `https://instagram.com/p/${post.shortcode}/`;
            }).join(", ");
            jQuery.ajax({
                    type: "POST",
                    data: { action: 'filteredPost', posts: filterPosts, urls: urls,'orderId': <?php echo $order_id;?> },
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',  
                    success: function(response) {
                        if(response){
                            localStorage.removeItem('filterPost');
                        }
                    },
                    error: function(response) {
                        // jQuery("#referal_form2 .submit-loading").css('display', 'none');
                    }
                });
        </script>
    <?php
}


add_action( 'wp_ajax_filteredPost', 'filteredPost' );
add_action( 'wp_ajax_nopriv_filteredPost', 'filteredPost' );

function filteredPost() {
    $final_urls = [];
    $posts = $_REQUEST['posts'];
    $order_id = $_REQUEST['orderId'];
    $urls = $_REQUEST['urls'];
    update_post_meta( $order_id, 'metadata', sanitize_text_field($posts));
    update_post_meta( $order_id, 'post_urls', sanitize_text_field($urls));
    exit;
}

//The Following registers an api route with multiple parameters. 
add_action( 'rest_api_init', 'add_custom_users_api');
 
function add_custom_users_api(){
    register_rest_route( 'instagram/v1', '/api', array(
        'methods' => 'GET',
        'callback' => 'get_instagram_post',
    ));

    register_rest_route( 'instagram/v1', '/login', array(
        'methods' => 'GET',
        'callback' => 'instagram_login',
    ));
}

//Customize the callback to your liking
function get_instagram_post($data){
    //get users post
    $url = 'https://www.instagram.com/graphql/query/?query_id=17888483320059182';
    $response = wp_remote_get($url, array(
        'variables'     => json_decode($data['variables'])
    ));
    if( is_array($response) ) {
        return $response;
    }
}

function instagram_login($data){
    $url = 'https://www.instagram.com/'.$data['username'].'/?__a=1';
    $response = wp_remote_get($url);
    if( is_array($response) ) {
        return $response;
    }
    return $url;
}

