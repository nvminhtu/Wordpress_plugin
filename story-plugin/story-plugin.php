<?php
/**
 * Plugin Name: Story Plugin
 * Plugin URI: http://it.phuotky.net
 * Description: Plugin hỗ trợ việc post truyện trên website
 * Version: 1.0
 * Author: Tu Nguyen
 * Author URI: http://phuotky.com
 * License: GPLv2 or later
 */
?>
<?php 
  if(!class_exists('Story_Plugin')) {
    class Story_Plugin {
      
      public function __construct() {
        //register action
        add_action('init',array(&$this, 'init'));

      }
      
      public function init() {
        $this->cpt_story();
      }

      
      public function cpt_story() {
        $labels = array(
          'name'               => _x( 'Storys', 'post type general name', 'story-plugin' ),
          'singular_name'      => _x( 'story', 'post type singular name', 'story-plugin' ),
          'menu_name'          => _x( 'Storys', 'admin menu', 'story-plugin' ),
          'name_admin_bar'     => _x( 'story', 'add new on admin bar', 'story-plugin' ),
          'add_new'            => _x( 'Add New', 'Story', 'story-plugin' ),
          'add_new_item'       => __( 'Add New Story', 'story-plugin' ),
          'new_item'           => __( 'New story', 'story-plugin' ),
          'edit_item'          => __( 'Edit story', 'story-plugin' ),
          'view_item'          => __( 'View story', 'story-plugin' ),
          'all_items'          => __( 'All Storys', 'story-plugin' ),
          'search_items'       => __( 'Search Storys', 'story-plugin' ),
          'parent_item_colon'  => __( 'Parent Storys:', 'story-plugin' ),
          'not_found'          => __( 'No Storys found.', 'story-plugin' ),
          'not_found_in_trash' => __( 'No Storys found in Trash.', 'story-plugin' )
        );

        $args = array(
          'labels'             => $labels,
          'description'        => __( 'Description.', 'story-plugin' ),
          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'show_in_menu'       => true,
          'query_var'          => true,
          'rewrite'            => array( 'slug' => 'story' ),
          'capability_type'    => 'post',
          'has_archive'        => true,
          'hierarchical'       => false,
          'menu_position'      => 5,
          'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'story', $args );
      }
    }
  }

  /**
   * create connection between chapter and story post
   * @return show post title in dropdown list
   */
  
 function mystoryparrent( $post_data = false ) {
    $scr = get_current_screen();
    $value = '';
    if ( $post_data ) {
    $t = get_post($post_data);
    $a = get_post($t->post_parent);
    $value = $a->post_title;
    }
    if ($scr->id == 'story')
    echo '<label>Thuộc truyện: <input type="text" name="parent" value="'.$value.'" /></label> (Tên của cuốn truyện gốc)<br /><br />';
  }
  add_action( 'edit_form_after_title', 'mystoryparrent' );

   /**
   * create connection between chapter and story post
   * @return show post title in dropdown list
   */
  add_action( 'save_post', 'save_mystory' );
  function save_mystory( $post_id ) {
      $story = isset( $_POST['parent'] ) ? get_page_by_title($_POST['parent'], 'OBJECT', 'post') : false ;
      if ( ! wp_is_post_revision( $post_id ) && $story ){
      remove_action('save_post', 'save_mystory');
      $postdata = array(
      'ID' => $_POST['ID'],
      'post_parent' => $story->ID
      );
      wp_update_post( $postdata );
      add_action('save_post', 'save_mystory');
    }
  }
  /**
   * get dropdown list in frontend
   * @return show post title in dropdown list
   */
  
  /**
   * load in the same time when plugin load
   * @return Loading custom class
   */
  function dls_load() {
    global $dls;
    $dls = new Story_Plugin();
    
  }
  add_action( 'plugins_loaded', 'dls_load' );

  // function get_dropdown_part( $id ) {
  //    global $post, $wpdb;
  //    $query = $wpdb->get_results(sprintf('select * from %s where post_type = \'%s\' and post_parent = %d and post_status = \'%s\'  order by post_date asc', $wpdb->posts, 'story', $id, 'publish'));
  //    if ($query) {
  //    echo '<form id="selectpart">
  //    <select name="part" onchange="window.location.href = (this.options[this.selectedIndex].value)"><option value="">- Chọn tập -</option>';
  //    foreach ( $query as $k ) {
  //    $uri = get_permalink($k->ID);
  //    if ( ! preg_match('/.*page-[0-9].*/', $uri))
  //    echo '<option value="'.$uri.'">'.$k->post_title .'</option>';
  //    }
  //    echo '</select></form>';
  //    }
  // }


?>