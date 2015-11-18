<?php
/**
 * Plugin Name: Service Point Management
 * Plugin URI: 
 * Description: Plugin hỗ trợ update service point data vào CSV
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: 
 * License: GPLv2 or later
 */
?>
<?php 
  
  define( 'CSV_PLUGIN_URL', plugin_dir_path(__FILE__ ) );

  if(!class_exists('Manage_Service_Point')) {
    class Manage_Service_Point {
      
      /**
       * [__construct description]
       */
      public function __construct() {
        add_action('init',array(&$this, 'init'));
        add_action('save_post', array($this,'write_to_csv'));
        //add_filter('manage_projects_posts_columns', 'bs_projects_table_head');
      }
      
      /**
       * [init description]
       * @return [type] [description]
       */
      public function init() {
        $this->cpt_service_point();
      }

      /**
       * [cpt_service_point description]
       * @return [type] [description]
       */
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
       * [write_to_csv write data from wordpress to CSV]
       * @param  [type] $post_id [description]
       * @return [type]          [description]
       */
      public function write_to_csv( $post_id ) {
        $type = "service-point";
        if($type == $post->post_type) {
          return;
        } else {
          require_once(sprintf("%s/settings.php", dirname(__FILE__)));
          $sp_settings = new Service_Point_Settings();
        }
      }

      function bs_projects_table_head( $columns ) {

        if( get_current_user_id() == 1 ) {
            $columns['project_id']  = 'ID';
          }
          $columns['project_category']  = 'Project Category';
          $columns['off_site']  = 'Offsite Project?';
          $columns['project_date']  = 'Project Date';
          return $columns;

      }

      public function bs_projects_table_content( $column_name, $post_id ) {

        if( $column_name == 'project_id' ) {
            echo $post_id;
        }
        if( $column_name == 'project_category' ) {
            $project_category = get_post_meta( $post_id, 'project_category', true );
            echo $project_category;
        }
        if( $column_name == 'off_site' ) {
            $off_site = get_post_meta( $post_id, 'projects_off-site', true );
            if( $off_site == '1' ) { echo 'Yes'; } else { echo 'No'; }
        }
        if( $column_name == 'project_date' ) {
            $project_date = get_post_meta( $post_id, 'project_test_date', true );
            $date = DateTime::createFromFormat('Ymd', get_field('project_test_date'));
            echo $date->format('jS F, Y');
        }

      }
    }
  }

  if(class_exists('Manage_Service_Point')) {
    $Manage_Service_Point = new Manage_Service_Point();
    add_action('plugins_loaded', $Manage_Service_Point->__construct());
  }

?>