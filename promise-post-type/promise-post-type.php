<?php
/**
 * Plugin Name: Promise Post type
 * Plugin URI: 
 * Description: Add news post type
 * Version: 1.0
 * Author: ORO VIETNAM
 * Author URI: 
 * License: GPLv2 or later
 */
?>
<?php 
if(!class_exists('Promise_Post_Type')) {
  class Promise_Post_Type {

    public function __construct() {
      add_action('init', array( $this, 'news_register_post_type'));
    }

    public function news_register_post_type() {
      register_post_type(
        'news',
        array(
          'labels'        => array(
            'name'               => __('News', 'text_domain'),
            'singular_name'      => __('News', 'text_domain'),
            'menu_name'          => __('News', 'text_domain'),
            'name_admin_bar'     => __('News Item', 'text_domain'),
            'all_items'          => __('All Items', 'text_domain'),
            'add_new'            => _x('Add New', 'news', 'text_domain'),
            'add_new_item'       => __('Add New Item', 'text_domain'),
            'edit_item'          => __('Edit Item', 'text_domain'),
            'new_item'           => __('New Item', 'text_domain'),
            'view_item'          => __('View Item', 'text_domain'),
            'search_items'       => __('Search Items', 'text_domain'),
            'not_found'          => __('No items found.', 'text_domain'),
            'not_found_in_trash' => __('No items found in Trash.', 'text_domain'),
            'parent_item_colon'  => __('Parent Items:', 'text_domain'),
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
            'slug' => 'news',
          ),
        )
      );
    }
  }
}

if(class_exists('Promise_Post_Type')) {
  $Promise_Post_Type = new Promise_Post_Type();
}