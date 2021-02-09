<?php

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortcode_Eltdf_Angled_Section extends WPBakeryShortCodesContainer {}
}

if(!function_exists('eltdf_core_add_angled_section_shortcodes')) {
	function eltdf_core_add_angled_section_shortcodes($shortcodes_class_name) {
		$shortcodes = array(
			'ElatedCore\CPT\Shortcodes\AngledSection\AngledSection',
		);
		
		$shortcodes_class_name = array_merge($shortcodes_class_name, $shortcodes);
		
		return $shortcodes_class_name;
	}
	
	add_filter('eltdf_core_filter_add_vc_shortcode', 'eltdf_core_add_angled_section_shortcodes');
}

if( !function_exists('eltdf_core_set_angled_section_icon_class_name_for_vc_shortcodes') ) {
	/**
	 * Function that set custom icon class name for angled section shortcode to set our icon for Visual Composer shortcodes panel
	 */
	function eltdf_core_set_angled_section_icon_class_name_for_vc_shortcodes($shortcodes_icon_class_array) {
		$shortcodes_icon_class_array[] = '.icon-wpb-angled-section';
		
		return $shortcodes_icon_class_array;
	}
	
	add_filter('eltdf_core_filter_add_vc_shortcodes_custom_icon_class', 'eltdf_core_set_angled_section_icon_class_name_for_vc_shortcodes');
}