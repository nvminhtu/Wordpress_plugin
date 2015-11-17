<?php
/**
 * Plugin Name: Import custom post type data
 * Plugin URI: 
 * Description: Hỗ trợ import data từ CSV vào Custom post type
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: 
 * License: GPLv2 or later
 */
?>
<?php 
  
  define( 'CSV_PLUGIN_URL', plugin_dir_path(__FILE__));
  
  if(!class_exists('Import_CSV_To_CPT')) {
    class Import_CSV_To_CPT {
      /**
       * [__construct description]
       */
      public function __construct() {
        add_action('init',array(&$this, 'cpt_service_point'));
        add_action('admin_notices', array($this,'update_interface'));
        add_action('admin_init', array($this,'import_csv'));
        add_action( 'save_post', array($this,'my_save_post_function', 10, 3 ));
      }
      public function cpt_service_point() {
        register_post_type(
          'service-point',
          array(
            'labels'        => array(
              'name'               => __('Service Point', 'service_point_domain'),
              'singular_name'      => __('Service Point', 'service_point_domain'),
              'menu_name'          => __('Service Point', 'service_point_domain'),
              'name_admin_bar'     => __('Service Point Item', 'service_point_domain'),
              'all_items'          => __('All Items', 'service_point_domain'),
              'add_new'            => _x('Add New', 'Service Point', 'service_point_domain'),
              'add_new_item'       => __('Add New Item', 'service_point_domain'),
              'edit_item'          => __('Edit Item', 'service_point_domain'),
              'new_item'           => __('New Item', 'service_point_domain'),
              'view_item'          => __('View Item', 'service_point_domain'),
              'search_items'       => __('Search Items', 'service_point_domain'),
              'not_found'          => __('No items found.', 'service_point_domain'),
              'not_found_in_trash' => __('No items found in Trash.', 'service_point_domain'),
              'parent_item_colon'  => __('Parent Items:', 'service_point_domain'),
            ),
            'public'        => true,
            'menu_position' => 5,
            'supports'      => array(
              'title',
              'editor',
              'thumbnail',
              'excerpt'
            ),
            'has_archive'   => true,
            'rewrite'       => array(
              'slug' => 'service-point',
            ),
          )
        );
      }
      /**
       * [update_interface update button for admin]
       * @return [none] [button interface]
       */
      public function update_interface() {
        echo "<div class='updated'>";
        echo "<p>";
        echo "To insert the posts into the database, click the button to the right.";
        echo "<a class='button button-primary' style='margin:0.25em 1em' href='{$_SERVER["REQUEST_URI"]}&insert_sitepoint_posts'>Insert Posts</a>";
        echo "</p>";
        echo "</div>"; 
      }

      /**
       * [import_csv read csv and parse them to database]
       * @return [type] [description]
       */
      public function import_csv() {
        
        global $wpdb;
        $file_src = "data/dummy.csv";

        //Step 1: check action when we can import data & setup fields
        if ( ! isset( $_GET["insert_sitepoint_posts"] ) ) {
          return;
        }

        $arr_fields = array(
          'custom-field' => 'sitepoint_post_attachment',
          'custom-post-type' => 'service-point'
        );

        //Step 2: Get data from CSV and parse to array
        $posts = $this->parse_csv_to_array($file_src);

        //Step 3: Parse data to sql query and insert to database
        foreach ( $posts as $post ) {

          $post_exists = $this->check_post_exists($post["title"], $wpdb, $arr_fields);
          // If the post exists, skip and go to next
          if ($post_exists) {
            continue;
          }
          
          $post["id"] = wp_insert_post( array(
            "post_title" => $post["title"],
            "post_content" => $post["content"],
            "post_type" => $arr_fields["custom-post-type"],
            "post_status" => "publish"
          ));

          // Set attachment meta and insert URL to custom fields
          $uploads_dir = wp_upload_dir();

          $attachment = array();
          $attachment["path"] = "{$uploads_dir["baseurl"]}/sitepoint-attachments/{$post["attachment"]}";
          $attachment["file"] = wp_check_filetype( $attachment["path"] );
          $attachment["name"] = basename( $attachment["path"], ".{$attachment["file"]["ext"]}" );

          // Replace post attachment data
          $post["attachment"] = $attachment;

          // Insert attachment into media library
          $post["attachment"]["id"] = wp_insert_attachment( array(
            "guid" => $post["attachment"]["path"],
            "post_mime_type" => $post["attachment"]["file"]["type"],
            "post_title" => $post["attachment"]["name"],
            "post_content" => "",
            "post_status" => "inherit"
          ));

          // Generate the metadata for the attachment, and update the database record.
          $attach_data = wp_generate_attachment_metadata( $post["attachment"]["id"], $attachment["path"] );
          wp_update_attachment_metadata( $post["attachment"]["id"], $attach_data );

          // Update post's custom field (use with ACF plugin)
          update_field( $arr_fields["custom-field"], $post["attachment"]["id"], $post["id"] );
          
        }
      }

      /**
       * [parse_csv_to_array description]
       * @param  [type] $file_src [description]
       * @return [type]           [description]
       */
      public function parse_csv_to_array($file_src ="") {
        $data = array();
        $errors = array();

        $file = CSV_PLUGIN_URL.$file_src;
        // Check permission
        if ( ! is_readable( $file ) ) {
          chmod( $file, 0744 );
        }

        // Check if file is writable, then open it in 'read only' mode
        if ( is_readable( $file ) && $_file = fopen( $file, "r" ) ) {
          $post = array();
          $header = fgetcsv( $_file );
          while ( $row = fgetcsv( $_file ) ) {
            foreach ( $header as $i => $key ) {
              $post[$key] = $row[$i];
            }
            $data[] = $post;
          }
          fclose( $_file );
        } else {
          $errors[] = "File '$file' could not be opened. Check the file's permissions to make sure it's readable by your server.";
        }


        if ( ! empty( $errors ) ) {
          // errors message for notice
        }
        return $data;
      }

      /**
       * [check_post_exists description]
       * @param  [type] $title      [description]
       * @param  [type] $wpdb       [description]
       * @param  [type] $arr_fields [description]
       * @return [type]             [description]
       */
      public function check_post_exists($title, $wpdb, $arr_fields) {
        $posts = $wpdb->get_col(
         "SELECT post_title 
          FROM {$wpdb->posts} 
          WHERE post_type = '{$arr_fields["custom-post-type"]}'"
        );
        // Check if the passed title exists in array
        return in_array( $title, $posts );
      }

      public function my_save_post_function( $post_ID, $post, $update ) {
        $msg = 'Is this un update? ';
        $msg .= $update ? 'Yes.' : 'No.';
        wp_die( $msg );
      }
    }
  }

  if(class_exists('Import_CSV_To_CPT')) {
    $Import_CSV_To_CPT = new Import_CSV_To_CPT();
    add_action('plugins_loaded', $Import_CSV_To_CPT->__construct());
  }

?>