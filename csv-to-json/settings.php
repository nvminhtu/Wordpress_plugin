<?php
if(!class_exists('Convert_to_JSON_Settings')) {
	class Convert_to_JSON_Settings {
		public function __construct() {
			add_action('admin_init', array(&$this, 'admin_init'));
      add_action('admin_menu', array(&$this, 'add_menu'));
		}
		public function admin_init() {
    	register_setting('wp_plugin_template-group', 'setting_a');

    	add_settings_section(
    	    'wp_plugin_template-section', 
    	    'WP Plugin Template Settings', 
    	    array(&$this, 'settings_section_wp_plugin_template'), 
    	    'wp_plugin_template'
    	);
    	
    	// add your setting's fields
      add_settings_field(
          'wp_plugin_template-setting_a', 
          'Setting A', 
          array(&$this, 'settings_field_input_text'), 
          'wp_plugin_template', 
          'wp_plugin_template-section',
          array(
              'field' => 'setting_a'
          )
      );
    }
        
    public function settings_section_wp_plugin_template() {
        // Think of this as help text for the section.
        echo 'These settings do things for the WP Plugin Template.';
    }
    
    public function settings_field_input_text($args) {
        // Get the field name from the $args array
        $field = $args['field'];
        // Get the value of this setting
        $value = get_option($field);
        // echo a proper input type="text"
        echo sprintf('<input type="file" name="$field" id="$field"  multiple="false" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu() {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'WP Plugin Template Settings', 
        	    'WP Plugin Template', 
        	    'manage_options', 
        	    'wp_plugin_template', 
        	    array(&$this, 'plugin_settings_page')
        	);
        }

        public function plugin_settings_page() {
        	if(!current_user_can('manage_options')) {
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        }
    }
}
