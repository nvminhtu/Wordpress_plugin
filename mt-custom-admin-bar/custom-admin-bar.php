<?php
/**
 * Plugin Name: CUSTOM WP Admin bar
 * Plugin URI: 
 * Description: plugin help for customizing admin bar with options
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: 
 * License: GPL2
*/


if(!class_exists('CUSTOM_WP_ADMIN_BAR')) {
	class CUSTOM_WP_ADMIN_BAR {

		public function __construct() {
     	$plugin = plugin_basename(__FILE__);
			//add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
			//add_action( 'admin_enqueue_scripts', array( $this, 'plugin_assets') );
			add_action("admin_bar_menu", array( $this, 'new_toolbar_item'), 999);
			add_action("admin_bar_menu", array( $this, 'remove_update'), 999);
			add_action("admin_bar_menu", array( $this, 'remove_logo'), 999);
		}

		public static function activate() {
			//do action after activiting plugin
		}

		public static function deactivate() {
			//do action after deactiviting plugin	
		}

		public function new_toolbar_item($wp_admin_bar) {
			$wp_admin_bar->add_node(
				array(
				 "id"=>"parent_node_1",
				 "title"=>"Quick links",
				 "href"=>"#")
			);

	    $wp_admin_bar->add_group(
	    	array(
	    		"id"=>"group_1",
	    		"parent"=>"parent_node_1")
	    	);

	    $wp_admin_bar->add_group(
	    	array(
	    	"id"=>"group_2",
	     	"parent"=>"parent_node_1"));

	    $wp_admin_bar->add_node(
	    	array(
	    	"id"=>"child_node_1",
	     	"title"=>"Google Webmaster Tool",
	     	"href"=>"#",
	     	"parent"=>"group_1"));

	    $wp_admin_bar->add_node(
	    	array(
		    	"id"=>"child_node_2",
		     	"title"=>"Google Analytics Tool",
		     	"href"=>"#",
		     	"parent"=>"group_1"));

	    $wp_admin_bar->add_node(
	    	array(
		    	"id"=>"child_node_3",
		     	"title"=>"PHP documentation",
		     	"href"=>"#",
		     	"parent"=>"group_2"));

	    $wp_admin_bar->add_node(
	    	array(
		    	"id"=>"child_node_4",
		     	"title"=>"Wordpress codex",
	     		"href"=>"#", "parent"=>"group_2")
	    	);
		}

		public function remove_update($wp_admin_bar) {
			$updates_node = $wp_admin_bar->get_node( 'updates' );
			// Check if the 'updates' node exists
			if( $updates_node ) {
				$wp_admin_bar->remove_node( 'updates' );
			}
		}

		public function remove_logo($wp_admin_bar) {
			 $wp_admin_bar->remove_node("wp-logo");
		}
  }
}

if(class_exists('CUSTOM_WP_ADMIN_BAR')) {
	register_activation_hook(__FILE__, array('CUSTOM_WP_ADMIN_BAR', 'activate'));
	register_deactivation_hook(__FILE__, array('CUSTOM_WP_ADMIN_BAR', 'deactivate'));

	$CUSTOM_WP_ADMIN_BAR = new CUSTOM_WP_ADMIN_BAR();
}