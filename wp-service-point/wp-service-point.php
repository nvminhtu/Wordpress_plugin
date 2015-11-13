<?php
/**
 * Plugin Name: WP Service Point Plugin
 * Description: Plugin quản lý service point
 * Version: 1.0
 * Author: ORO VIETNAM
 * License: GPLv2 or later
 */
?>
<?php 
  if(!class_exists('Service_Point_Plugin')) {
    class Service_Point_Plugin {
      
      public function __construct() {
        //register action
        add_action('init',array(&$this, 'init'));
      }
      
      public function init() {
        $this->cpt_service_point();
        $this->meta_service_point();
      }

      /**
       * cpt_service_point : create custom post type for service point
       * @return custom post type
       */
      public function cpt_service_point() {
        $labels = array(
          'name'               => _x( 'Service points', 'post type general name', 'service-point-plugin' ),
          'singular_name'      => _x( 'Service point', 'post type singular name', 'service-point-plugin' ),
          'menu_name'          => _x( 'Service points', 'admin menu', 'service-point-plugin' ),
          'name_admin_bar'     => _x( 'Service point', 'add new on admin bar', 'service-point-plugin' ),
          'add_new'            => _x( 'Add New', 'Service point', 'service-point-plugin' ),
          'add_new_item'       => __( 'Add New Service point', 'service-point-plugin' ),
          'new_item'           => __( 'New Service point', 'service-point-plugin' ),
          'edit_item'          => __( 'Edit Service point', 'service-point-plugin' ),
          'view_item'          => __( 'View Service point', 'service-point-plugin' ),
          'all_items'          => __( 'All Service points', 'service-point-plugin' ),
          'search_items'       => __( 'Search Service points', 'service-point-plugin' ),
          'parent_item_colon'  => __( 'Parent Service points:', 'service-point-plugin' ),
          'not_found'          => __( 'No Service points found.', 'service-point-plugin' ),
          'not_found_in_trash' => __( 'No Service points found in Trash.', 'service-point-plugin' )
        );

        $args = array(
          'labels'             => $labels,
          'description'        => __( 'Description.', 'service-point-plugin' ),
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'show_in_menu'       => true,
          'query_var'          => true,
          'rewrite'            => array( 'slug' => 'service-point' ),
          'capability_type'    => 'post',
          'has_archive'        => true,
          'hierarchical'       => false,
          'menu_position'      => 5,
          'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'service-point', $args );
      }

      /**
       * cpt_service_point : create custom metaboxes for service point
       * @return custom metaboxes field in admin panel
       */
      public function meta_service_point() {
        include_once(plugin_dir_path( __FILE__ ). 'metabox/metabox-functions.php');
      }

    }
  }

  /**
   * load in the same time when plugin load
   * @return Loading custom class
   */
  function wp_servicepoint_load() {
    global $wp_servicepoint;
    $wp_servicepoint = new Service_Point_Plugin();
  }
  add_action( 'plugins_loaded', 'wp_servicepoint_load' );

   /**
   * update action when save post in wordpress
   * @return Loading custom class
   */
  function my_project_updated_send_email() {
    $message = "Testing sendmail";
    $subject = "Confirmation for project";
    // Send email to admin.
    wp_mail( 'nvminhtu@gmail.com', $subject, $message );
  } 
  add_action( 'save_post', 'my_project_updated_send_email' );


   /**
   * update action when save post in wordpress
   * @return example: replace a string by a string
   */
  function acme_update_post( $post_id ) {
    // First we read the title and encode it
    $title = get_the_title( $post_id );
    // Next, we look to see if a certain word is present in the title
    if ( 0 < strpos( $title, "point" ) ) {
      // We unhook this action to prevent an infinite loop
      remove_action( 'save_post', 'acme_update_post' );
      // Update the title so that it doesn't include point
      $title = str_ireplace( "point", "", $title );
      $args = array(
        'ID'              => $post_id,
        'post_title'      => $title
      );
      wp_update_post( $args );
      // Now hook the action
      add_action( 'save_post', 'acme_update_post' );
    }
  }
  add_action( 'save_post', 'acme_update_post' );


?>

