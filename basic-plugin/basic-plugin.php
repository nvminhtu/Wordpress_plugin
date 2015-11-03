<?php
/**
 * Plugin Name: Shortcode Download Plugin
 * Plugin URI: http://it.phuotky.net
 * Description: Plugin cho việc tạo shortcode cho 1 button download bất kỳ
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: http://phuotky.com
 * License: GPLv2 or later
 */
?>
<?php 
  if(!class_exists('Download_Shortcode_Plugin')) {
    class Download_Shortcode_Plugin {
      function __construct() {
        if(!function_exists('add_shortcode')) {
          return;
        }
        add_shortcode( 'dls' , array(&$this, 'dl_button_func') );
      }

      function dl_button_func($atts = array(), $content = null) {
        extract(shortcode_atts(array('name' => 'World'), $atts));
        return '<div><p>download button '.$name.'!!!</p></div>';
      }
    }
  }

  function dls_load() {
    global $dls;
    $dls = new Download_Shortcode_Plugin();
  }
  add_action( 'plugins_loaded', 'dls_load' );

?>