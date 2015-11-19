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
        //add_action('init',array(&$this, 'cpt_service_point'));
        add_action('admin_notices', array($this,'update_interface'));
        add_action('admin_init', array($this,'import_csv'));
        
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
        $file_src = "data/promise-service-point.csv";

        //Step 1: check action when we can import data & setup fields
        if ( ! isset( $_GET["insert_sitepoint_posts"] ) ) {
          return;
        }

        $arr_fields = array(
          'address' => 'field_5645a37030278',
          'address2' => 'field_5645a5f930279',
          'tel' => 'field_5645a6483027a',
          'fax' => 'field_5645a7093027b',
          'category' => 'field_5645a7d0dae73',
          'prefecture' => 'field_5645a933dae74',
          'district' => 'field_5645a9efdae75',
          'update_status' => 'field_564ad7bbe6140',
          'pic_appearance' => 'field_5645aa88bd7c6',
          'pic_map' => 'field_5645aac3bd7c7',
          'type' => 'field_5645ab2f596df',
          'lat' => 'field_5645ab7e596e0',
          'lng' => 'field_5645abb3596e1',
          'custom-post-type' => 'service-point'
        );

        //Step 2: Get data from CSV and parse to array
        $posts = $this->parse_csv_to_array($file_src);

        //Step 3: Parse data to sql query and insert to database
        foreach ( $posts as $post ) {

          $post_exists = $this->check_post_exists($post["id"], $wpdb, $arr_fields);
          // If the post exists, skip and go to next
          if ($post_exists) {
            continue;
          }
          
          $post["id"] = wp_insert_post( array(
            "post_title" => $post["id"],
            "post_type" => $arr_fields["custom-post-type"],
            "post_status" => "publish"
          ));

          
          // Set attachment meta and insert URL to custom fields
          $attach_pic_appearance = $this->attach_to_media($post["pic_appearance"]);
          $attach_pic_map = $this->attach_to_media($post["pic_map"]);
          
          update_field( $arr_fields["address"], $post["address"], $post["id"] );
          update_field( $arr_fields["address2"], $post["address2"], $post["id"] );
          update_field( $arr_fields["tel"], $post["tel"], $post["id"] );
          update_field( $arr_fields["fax"], $post["fax"], $post["id"] );
          update_field( $arr_fields["category"], $post["category"], $post["id"] );
          update_field( $arr_fields["prefecture"], $post["prefecture"], $post["id"] );
          update_field( $arr_fields["district"], $post["district"], $post["id"] );
          update_field( $arr_fields["update_status"], $post["update_status"], $post["id"] );
          update_field( $arr_fields["pic_appearance"], $attach_pic_appearance, $post["id"] );
          update_field( $arr_fields["pic_map"], $attach_pic_map, $post["id"] );
          update_field( $arr_fields["type"], $post["type"], $post["id"] );
          update_field( $arr_fields["lat"], $post["lat"], $post["id"] );
          update_field( $arr_fields["lng"], $post["lng"], $post["id"] );
          
        }
      }
      /**
       * [attach_to_media create media post type for fields which need be uploaded]
       * @param  [type] $custom_field [para from custom field which we need import]
       * @return [id]               [id of attachment file]
       */
      public function attach_to_media($custom_field) {
        
        $uploads_dir = wp_upload_dir();
        if($custom_field!='') {
          $attachment = array();
          $attachment["path"] = "{$uploads_dir["baseurl"]}/2015/11/{$custom_field}";
          $attachment["file"] = wp_check_filetype( $attachment["path"] );
          $attachment["name"] = basename( $attachment["path"], ".{$attachment["file"]["ext"]}" );

          // Replace post attachment data
          $post["attachment"] = $attachment;

          // Insert attachment into media library
          $attach_media = array(
            'post_mime_type' => $post["attachment"]["file"]["type"],
            'post_title' => $post["attachment"]["name"],
            'post_content' => '',
            'post_status' => 'inherit'
          );
        }
        
        $post["attachment"]["id"] = wp_insert_attachment($attach_media, $post["attachment"]["path"]);
        wp_update_attachment_metadata( $post["attachment"]["id"], $post["attachment"]["path"] );

        return $post["attachment"]["id"];
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

    }
  }

  if(class_exists('Import_CSV_To_CPT')) {
    $Import_CSV_To_CPT = new Import_CSV_To_CPT();
    add_action('plugins_loaded', $Import_CSV_To_CPT->__construct());
  }

?>