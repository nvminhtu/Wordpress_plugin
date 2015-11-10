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
        add_action('edit_form_after_title', array($this,'mystoryparrent'));
        add_action('save_post', array($this,'save_mystory'));
      }
      
      public function init() {
        $this->cpt_story();
      }

      /**
       * load in the same time when plugin load
       * @return Loading custom class
       */
      public function Story_Plugin() {
        //constructor function
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

      public function mystoryparrent( $post_data = false ) {

        // $simple_query = "SELECT * FROM $wpdb->posts LIMIT 0, 10";

        // $complex_query = "
        //   SELECT * FROM $wpdb->posts WHERE
        //   post_status = publish AND
        //   comment_count > 5 AND
        //   post_type = 'post' AND
        //   ID IN ( SELECT object_id FROM $wpdb->term_relationships WHERE
        //   term_taxonomy_id = 4 )
        // ";
        

        $scr = get_current_screen();
        // print_r($scr);
        $value = '';
        if ( $post_data ) {
          $t = get_post($post_data);
          $a = get_post($t->post_parent);
          $value = $a->post_title;
        }
        if ($scr->id == 'story')
          echo '<label>Thuộc truyện: <input type="text" name="parent" value="'.$value.'" /></label> (Tên của cuốn truyện gốc)<br /><br />';
      }

      public function save_mystory( $post_id ) {
          $story = isset( $_POST['parent'] ) ? get_page_by_title($_POST['parent'], 'OBJECT', 'post') : false ;
          print_r($story);
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
    }
  }

  if(class_exists('Story_Plugin')) {
    $Story_Plugin = new Story_Plugin();
    add_action('plugins_loaded',$Story_Plugin->Story_Plugin());
  }
?>