<?php
/**
 * Plugin Name: Upload CSV file
 * Plugin URI: 
 * Description: upload csv files for service point
 * Version: 1.0
 * Author: ORO VIETNAM
 * Author URI: 
 * License: GPL2
*/
define( 'CSV_PLUGIN_URL', plugin_dir_path(__FILE__ ) );

if(!class_exists('Service_Point')) {
	class Service_Point {

		public function __construct() {
      require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Service_Point_Settings = new Service_Point_Settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
			add_action( 'admin_enqueue_scripts', array( $this, 'plugin_assets') );
		}

		public static function activate() {
			//do action after activiting plugin
		}

		public static function deactivate() {
			//do action after deactiviting plugin	
		}

		public function plugin_assets() {
			wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ). 'templates/css/admin-style.css', false, '1.0.0' );
      wp_enqueue_style( 'custom_wp_admin_css' );
		}

		public function plugin_settings_link($links) {
			$settings_link = '<a href="options-general.php?page=manage-service-point">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
  }
}

if(class_exists('Service_Point')) {
	register_activation_hook(__FILE__, array('Service_Point', 'activate'));
	register_deactivation_hook(__FILE__, array('Service_Point', 'deactivate'));

	$Service_Point = new Service_Point();
}