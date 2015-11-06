<?php
/*
Plugin Name: Tour
Version: 1.1
Description: Tour item manager
Author: KaptinLin
*/
if (!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_PLUGIN_URL') )
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
if (!defined('WP_PLUGIN_DIR') )
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

if (!class_exists('Plugin_Tour')) {
Class Plugin_Tour {
	public function __construct() {
		$name = dirname(plugin_basename(__FILE__));

		
		$this->pluginurl = plugins_url($name) . "/";
		$this->pluginpath = WP_PLUGIN_DIR . "/$name/";

		$this->types();
		$this->shortcode();
		add_filter( 'template_include', array( $this, 'template_loader' ) );

		if(is_admin()){
			$this->metabox();
		}	
	}
	
	public function types() {
		/*require_once ($this->pluginpath . '/framework/tour.php');
		
		$tour = new Plugin_Post_Type_Tour();
		$tour->init();  */
		
	
	}

	public function metabox() {
		require_once ($this->pluginpath . '/framework/includes/metaboxes.php');
		require_once ($this->pluginpath . '/framework/metabox.php');
	
		$metabox = new Plugin_Metabox_Tour();
	
	}

	public function shortcode(){
		require_once ($this->pluginpath . '/framework/shortcodes.php');
	}

	public function template_loader( $template ) {
		$find = array();
		$file = '';

		if ( is_single() && get_post_type() == 'tour' ) {
			$file 	= 'single-tour.php';
			$find[] = $file;
			$find[] = $this->pluginpath . '/templates/' . $file;
		}
	
		if ( $file ) {
			$template = locate_template( $find );
			if ( ! $template ) $template = $this->pluginpath . '/templates/' . $file;
		}

		return $template;
	}
}
}


if (class_exists('Plugin_Tour')) {
	$plugin_tour = new Plugin_Tour();
}


add_action('admin_enqueue_scripts', 'metabox_plugin_admin_init');
function metabox_plugin_admin_init() {
	 wp_enqueue_style('metabox-custom', plugins_url('/templates/style.css', __FILE__)) ;
	
	 
}



/*PRODUCT POST TYPE*/
$products_page_title = 'tour';
$products_page_permalink = 'tour';


$products_page_content = array(

		'post_type' => 'page',

		'post_title' => $products_page_title,

		'post_name' => $products_page_permalink,

		'post_status' => 'publish',

		'post_content' => '<ul><li>This page was auto-generated to display all posts in the Tours custom post type</li><li>Tours function like posts and have two taxonomies - Product Categories (categories) and Ingredients (tags)</li><li>A list of both taxonomy terms is automatically generated within the sidebar</li><li>This page (products) uses the products.php page template</li><li><strong>Start adding products and away you go!</strong></li></ul>',

		'post_author' => 1,

);

// Check if the products page exists
$products_page_data = get_page_by_title($products_page_title);

$products_page_id = $products_page_data->ID;

// Create products page if it does't exist
if(!isset($products_page_data)){

	wp_insert_post($products_page_content);

	$products_page_data = get_page_by_title($products_page_title);

	$products_page_id = $products_page_data->ID;

	update_post_meta($products_page_id, '_wp_page_template','tour.php');

}


// Return the current taxonomy title
function current_tax_title() {

	echo get_queried_object()->name;

}

// Return the products page ID
function page_products() {

	$products_page_data = get_page_by_title('tour');

	$products_page_id = $products_page_data->ID;

	return $products_page_id;

}



// Test if we're in the products section (useful for highlighting navigation)
function is_products() {

	return (is_page('tour') || is_singular('tour') || is_tax('product_ingredient') || is_tax('tour_category'));

}


add_action('init', '_init_product_post_type');



function _init_product_post_type() {


	register_taxonomy(

	'tour_category',

	array( 'tour' ),

	array(

	'labels' => array(

	'name' => __( 'Tour Categories' ),

	'singular_name' => __( 'Tour Category' ),

	'search_items' => __( 'Search Product Categories' ),

	'popular_items' => __( 'Popular Product Categories' ),

	'all_items' => __( 'All Product Categories' ),

	'parent_item' => __( 'Parent Tour Category' ),

	'parent_item_colon' => __( 'Parent Tour Category:' ),

	'edit_item' => __( 'Edit Tour Category' ),

	'update_item' => __( 'Update Tour Category' ),

	'add_new_item' => __( 'Add New Tour Category' ),

	'new_item_name' => __( 'New Tour Category' ),

	),

	'public' => true,

	'show_in_nav_menus' => true,

	'show_ui' => true,

	'publicly_queryable' => true,

	'exclude_from_search' => false,

	'hierarchical' => true,

	'query_var' => true,

	'rewrite' => array( 'slug' => 'tour/tour-category', 'with_front' => false ),

	)

	);

	// Create post type (products i.e. posts)

	register_post_type( 'tour',

	array(

	'capability_type' => 'post',

	'hierarchical' => false,

	'public' => true,

	'show_ui' => true,

	'publicly_queryable' => true,

	'exclude_from_search' => false,

	'menu_position' => 40,

	'labels' => array(

	'name' => __( 'Tours' ),

	'singular_name' => __( 'Tour' ),

	'add_new' => __( 'Add New' ),

	'add_new_item' => __( 'Add New tour' ),

	'edit' => __( 'Edit tour' ),

	'edit_item' => __( 'Edit tour' ),

	'new_item' => __( 'New tour' ),

	'view' => __( 'View tour' ),

	'view_item' => __( 'View tour' ),

	'search_items' => __( 'Search tour' ),

	'not_found' => __( 'No Tours found' ),

	'not_found_in_trash' => __( 'No Tours found in Trash' )

	),

	'has_archive' => true,

	'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),

	'query_var' => true,

	'rewrite' => array( 'slug' => 'tour/%tour_category%', 'with_front' => false ),

	)

	);



	// Make permalinks for Tours pretty (add Tour Category to URL)

	// -------------------------------------------------------------

	add_filter('post_type_link', 'product_permalink_filter_function', 1, 3);

	function product_permalink_filter_function( $post_link, $id = 0, $leavename = FALSE ) {

		if ( strpos('%tour_category%', $post_link) === 'FALSE' ) {

			return $post_link;

		}

		$post = get_post($id);

		if ( !is_object($post) || $post->post_type != 'tour' ) {

			return $post_link;

		}

		// this calls the term to be added to the URL

		$terms = wp_get_object_terms($post->ID, 'tour_category');

		if ( !$terms ) {

			return str_replace('tour/%tour_category%/', '', $post_link);

		}

		return str_replace('%tour_category%', $terms[0]->slug, $post_link);

	}


	flush_rewrite_rules();

	



}



add_action( 'init', 'wpse16902_init' );

function wpse16902_init() {

	$GLOBALS['wp_rewrite']->use_verbose_page_rules = true;

}



add_filter( 'page_rewrite_rules', 'wpse16902_collect_page_rewrite_rules' );

function wpse16902_collect_page_rewrite_rules( $page_rewrite_rules )

{

	$GLOBALS['wpse16902_page_rewrite_rules'] = $page_rewrite_rules;

	return array();

}



add_filter( 'rewrite_rules_array', 'wspe16902_prepend_page_rewrite_rules' );

function wspe16902_prepend_page_rewrite_rules( $rewrite_rules )

{

	return $GLOBALS['wpse16902_page_rewrite_rules'] + $rewrite_rules;

}