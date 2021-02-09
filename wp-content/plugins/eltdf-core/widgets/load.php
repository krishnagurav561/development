<?php

if(!function_exists('mrseo_elated_load_widget_class')) {
	/**
	 * Loades widget class file.
	 */
	function mrseo_elated_load_widget_class(){
		include_once 'widget-class.php';
	}

	add_action('mrseo_elated_before_options_map', 'mrseo_elated_load_widget_class');
}

if(!function_exists('mrseo_elated_load_widgets')) {
	/**
	 * Loades all widgets by going through all folders that are placed directly in widgets folder
	 * and loads load.php file in each. Hooks to mrseo_elated_after_options_map action
	 */
	function mrseo_elated_load_widgets() {

		if ( mrseo_elated_core_plugin_installed() ) {
			foreach (glob(ELATED_FRAMEWORK_ROOT_DIR . '/modules/widgets/*/load.php') as $widget_load) {
				include_once $widget_load;
			}
		}

		include_once 'widget-loader.php';
	}

	add_action('mrseo_elated_before_options_map', 'mrseo_elated_load_widgets');
}