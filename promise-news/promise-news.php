<?php
/**
 * Plugin Name: Promise News
 * Plugin URI: 
 * Description: News Format
 * Version: 1.0
 * Author: ORO VIETNAM
 * Author URI: 
 * License: GPLv2 or later
 */
?>
<?php 
 /**
 * Registers a custom post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function prefix_register_post_type()
{
  register_post_type(
    'prefix_portfolio',
    array(
      'labels'        => array(
        'name'               => __('Portfolio', 'text_domain'),
        'singular_name'      => __('Portfolio', 'text_domain'),
        'menu_name'          => __('Portfolio', 'text_domain'),
        'name_admin_bar'     => __('Portfolio Item', 'text_domain'),
        'all_items'          => __('All Items', 'text_domain'),
        'add_new'            => _x('Add New', 'prefix_portfolio', 'text_domain'),
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
        'excerpt',
        'custom-fields',
      ),
      'taxonomies'    => array(
        'prefix_portfolio_categories',
      ),
      'has_archive'   => true,
      'rewrite'       => array(
        'slug' => 'portfolio',
      ),
    )
  );
}

add_action('init', 'prefix_register_post_type');

/**
 * Registers a custom taxonomy.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function prefix_register_taxonomy()
{
  register_taxonomy(
    'prefix_portfolio_categories',
    array(
      'prefix_portfolio',
    ),
    array(
      'labels'            => array(
        'name'              => _x('Categories', 'prefix_portfolio', 'text_domain'),
        'singular_name'     => _x('Category', 'prefix_portfolio', 'text_domain'),
        'menu_name'         => __('Categories', 'text_domain'),
        'all_items'         => __('All Categories', 'text_domain'),
        'edit_item'         => __('Edit Category', 'text_domain'),
        'view_item'         => __('View Category', 'text_domain'),
        'update_item'       => __('Update Category', 'text_domain'),
        'add_new_item'      => __('Add New Category', 'text_domain'),
        'new_item_name'     => __('New Category Name', 'text_domain'),
        'parent_item'       => __('Parent Category', 'text_domain'),
        'parent_item_colon' => __('Parent Category:', 'text_domain'),
        'search_items'      => __('Search Categories', 'text_domain'),
      ),
      'show_admin_column' => true,
      'hierarchical'      => true,
      'rewrite'           => array(
        'slug' => 'portfolio/category',
      ),
    )
  );
}

add_action('init', 'prefix_register_taxonomy', 0);


function prefix_flush_rewrite_rules()
{
  flush_rewrite_rules();
}

add_action('after_switch_theme', 'prefix_flush_rewrite_rules');
?>