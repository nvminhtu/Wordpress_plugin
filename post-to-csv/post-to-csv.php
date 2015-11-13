<?php
/**
 * Plugin Name: Post to CSV
 * Plugin URI: http://it.phuotky.net
 * Description: Plugin hỗ trợ update post vào CSV
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: http://phuotky.com
 * License: GPLv2 or later
 */
?>
<?php 
  if(!class_exists('POST_TO_CSV')) {
    class POST_TO_CSV {
      
      public function __construct() {
        //register action
        ///add_action('init',array(&$this, 'init'));
        //add_action('edit_form_after_title', array($this,'mystoryparrent'));
        add_action('save_post', array($this,'save_mystory'));
      }
      
      public function init() {
        //$this->cpt_story();
      }

      /**
       * load in the same time when plugin load
       * @return Loading custom class
       */
      public function POST_TO_CSV() {
        //constructor function
      }

      public function save_mystory( $post_id ) {
          // $story = isset( $_POST['parent'] ) ? get_page_by_title($_POST['parent'], 'OBJECT', 'post') : false ;
          // print_r($story);
          // if ( ! wp_is_post_revision( $post_id ) && $story ){
          //   remove_action('save_post', 'save_mystory');
          //   $postdata = array(
          //     'ID' => $_POST['ID'],
          //     'post_parent' => $story->ID
          //   );
          // wp_update_post( $postdata );
          define( 'CSV_PLUGIN_URL', plugin_dir_path(__FILE__ ) );
          $list = array (
                array('aaa', 'bbb', 'ccc', 'dddd'),
                array('123', '456', '789'),
                array('"aaa"', '"bbb"')
            );
              $uri = CSV_PLUGIN_URL.'files'.'/promise-service-point.csv';
            $fp = fopen($uri, 'w');

            foreach ($list as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

          add_action('save_post', 'save_mystory');
        
      }
    }
  }

  if(class_exists('POST_TO_CSV')) {
    $POST_TO_CSV = new POST_TO_CSV();
    add_action('plugins_loaded',$POST_TO_CSV->POST_TO_CSV());
  }
?>