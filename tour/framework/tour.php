<?php
class Plugin_Post_Type_Tour {
	public $post_type = 'tour';
	public $post_type_taxonomy = 'tour_category';

	function __construct($post_type = 'tour') {
		$this->post_type = $post_type;
	}

	function init(){
		add_action('init',array(&$this, 'register'));
	}

	function register(){
		$this->register_post_type();
		$this->register_taxonomy();
	}

	function register_post_type() {
		register_post_type($this->post_type, array(
			'labels' => array(
				'name' => _x('Tour items', 'post type general name', 'theme_admin' ),
				'singular_name' => _x('Tour Item', 'post type singular name', 'theme_admin' ),
				'add_new' => _x('Add New', 'tour', 'theme_admin' ),
				'add_new_item' => __('Add New Tour Item', 'theme_admin' ),
				'edit_item' => __('Edit Tour Item', 'theme_admin' ),
				'new_item' => __('New Tour Item', 'theme_admin' ),
				'view_item' => __('View Tour Item', 'theme_admin' ),
				'search_items' => __('Search Tour Items', 'theme_admin' ),
				'not_found' =>  __('No tour item found', 'theme_admin' ),
				'not_found_in_trash' => __('No tour items found in Trash', 'theme_admin' ), 
				'parent_item_colon' => '',
				'menu_name' => __('Tour items', 'theme_admin' ),
			),
			'singular_label' => __('tour', 'theme_admin' ),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			//'menu_position' => 20,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title', 'author', 'editor', 'excerpt', 'thumbnail', 'comments', 'page-attributes'),
			'has_archive' => false,
			'rewrite' => array( 'slug' => 'tour', 'with_front' => true, 'pages' => true, 'feeds'=>false ),
			'query_var' => false,
			'can_export' => true,
			'show_in_nav_menus' => true,
		));
	}

	function register_taxonomy(){
		//register taxonomy for portfolio
		register_taxonomy($this->post_type_taxonomy,$this->post_type,array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Tour Categories', 'taxonomy general name', 'theme_admin' ),
				'singular_name' => _x( 'Tour Category', 'taxonomy singular name', 'theme_admin' ),
				'search_items' =>  __( 'Search Categories', 'theme_admin' ),
				'popular_items' => __( 'Popular Categories', 'theme_admin' ),
				'all_items' => __( 'All Categories', 'theme_admin' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Tour Category', 'theme_admin' ), 
				'update_item' => __( 'Update Tour Category', 'theme_admin' ),
				'add_new_item' => __( 'Add New Tour Category', 'theme_admin' ),
				'new_item_name' => __( 'New Tour Category Name', 'theme_admin' ),
				'separate_items_with_commas' => __( 'Separate Tour category with commas', 'theme_admin' ),
				'add_or_remove_items' => __( 'Add or remove portfolio category', 'theme_admin' ),
				'choose_from_most_used' => __( 'Choose from the most used portfolio category', 'theme_admin' ),
				'menu_name' => __( 'Categories', 'theme_admin' ),
			),
			'public' => false,
			'show_in_nav_menus' => false,
			'show_ui' => true,
			'show_tagcloud' => false,
			'query_var' => false,
			'rewrite' => false,
		));
	}
}
