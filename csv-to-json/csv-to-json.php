<?php
/*
  Plugin Name: Convert CSV To JSON
  Plugin URI: 
  Description: converting csv files to json format
  Version: 1.0
  Author: ORO VIETNAM
  Author URI: 
  License: GPL2
*/

if(!class_exists('Convert_to_JSON')) {
	class Convert_to_JSON {

		public function __construct() {
      require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Convert_To_JSON_Settings = new Convert_To_JSON_Settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		}

		public static function activate() {
			
		}

		public static function deactivate() {
			
		}

		function plugin_settings_link($links) {
			$settings_link = '<a href="options-general.php?page=convert-to-json">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
  }
}

if(class_exists('Convert_to_JSON')) {
	register_activation_hook(__FILE__, array('Convert_to_JSON', 'activate'));
	register_deactivation_hook(__FILE__, array('Convert_to_JSON', 'deactivate'));

	$convert_to_json = new Convert_to_JSON();
}