<?php
/**
 * Plugin Name: Delete Attachment
 * Plugin URI: 
 * Description: Plugin hỗ trợ việc xóa toàn bộ attachment
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: 
 * License: GPLv2 or later
 */
?>
<?php 
  
  define( 'CSV_PLUGIN_URL', plugin_dir_path(__FILE__ ) );
  

  if(!class_exists('Delete_Attachment')) {
    class Delete_Attachment {
      
      /**
       * [__construct description]
       */
      public function __construct() {
        //add_action('save_post', array($this,'delete_attachment'));
        add_action('save_post', array($this,'delete_attachment'));
      }
      
      /**
       * [delet_attachment write data from wordpress to CSV]
       * @param  [type] $post_id [description]
       * @return [type]          [description]
       */
      public function delete_attachment( $post ) {
        global $wpdb;

        //$ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_parent = $post_id AND post_type = 'attachment'");
        $ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment'");
        foreach ( $ids as $id )
          wp_delete_attachment($id);
      }
    }
  }

  // Call Admin functions to edit admin panel
  if(class_exists('Delete_Attachment')) {
    $Delete_Attachment = new Delete_Attachment();
  }

?>