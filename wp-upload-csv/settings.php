<?php
if(!class_exists('Service_Point_Settings')) {
	class Service_Point_Settings {

    /**
     * [__construct init admin functions and admin menu]
     */
		public function __construct() {
			add_action('admin_init', array(&$this, 'admin_init'));
      add_action('admin_menu', array(&$this, 'add_menu'));
		}

    /**
     * [admin_init add option fields]
     * @return [section] [display fields in option pages]
     */
		public function admin_init() {
    	register_setting('csv-fields', 'upload');

    	add_settings_section(
  	    'manage-service-point-section', 
  	    'Upload CSV',
  	    array(&$this, 'settings_section_service_point'), 
  	    'manage-service-point'
    	);
    	
      add_settings_field(
        'csv-file-upload', 
        'CSV file Upload', 
        array(&$this, 'settings_field_input_file'), 
        'manage-service-point', 
        'manage-service-point-section',
        array(
          'field' => 'upload-csv'
        )
      );

    }
    
    /**
     * [settings_section_service_point guide message]
     * @return [text] [display text for guideline]
     */
    public function settings_section_service_point() {
      echo '<div class="message-box">
              Upload CSV files for service point
              <ul>
                <li>File should have extension is csv.</li>
                <li>Using below file upload for attaching csv file.</li>
                <li>Click submit after choose file.</li>
              </ul>
            <!-- /.message-box--></div>';
    }
    
    /**
     * [settings_field_input_file file display for option field]
     * @param  [type] $args [param from field]
     * @return [type]       [return input in form display]
     */
    public function settings_field_input_file($args) {
      $field = $args['field'];
      $value = get_option($field);
      echo sprintf('
          <input type="file" name="%s" id="%s">
          <input type="submit" class="button button-primary" value="Submit" name="csv-submit">
       ', $field, $field, $value);
    } 
    
    /**
     * [add_menu add menu to admin menu on the left]
     */
    public function add_menu() {
      add_menu_page(
        'Service Point Management',
        'Import Service Point',
        'manage_options',
        'manage-service-point',
        array(&$this, 'plugin_settings_page'),
        '',
        60);
    }

    /**
     * [plugin_settings_page add all layers to setting page]
     * @return [none] [incldue all layers and restrict the priviledge]
     */
    public function plugin_settings_page() {
    	if(!current_user_can('manage_options')) {
    		wp_die(__('You do not have sufficient permissions to access this page.'));
    	}
    	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
    }
  }
}
