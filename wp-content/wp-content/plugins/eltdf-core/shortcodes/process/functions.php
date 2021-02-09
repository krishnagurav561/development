<?php

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortcode_Eltdf_Process_Holder extends WPBakeryShortCodesContainer {}
    class WPBakeryShortcode_Eltdf_Process extends WPBakeryShortCodesContainer {}
}

if(!function_exists('eltdf_core_add_process_holder_shortcodes')) {
    function eltdf_core_add_process_holder_shortcodes($shortcodes_class_name) {
        $shortcodes = array(
            'ElatedCore\CPT\Shortcodes\ProcessHolder\ProcessHolder',
            'ElatedCore\CPT\Shortcodes\Process\Process'
        );

        $shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);

        return $shortcodes_class_name;
    }

    add_filter('eltdf_core_filter_add_vc_shortcode', 'eltdf_core_add_process_holder_shortcodes');
}

if( !function_exists('eltdf_core_set_process_icon_class_name_for_vc_shortcodes') ) {
	/**
	 * Function that set custom icon class name for process shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function eltdf_core_set_process_icon_class_name_for_vc_shortcodes($shortcodes_icon_class_array) {
		$shortcodes_icon_class_array[] = '.icon-wpb-process-holder';
		$shortcodes_icon_class_array[] = '.icon-wpb-process-item';
		
		return $shortcodes_icon_class_array;
	}
	
	add_filter('eltdf_core_filter_add_vc_shortcodes_custom_icon_class', 'eltdf_core_set_process_icon_class_name_for_vc_shortcodes');
}