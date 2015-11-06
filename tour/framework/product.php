<?php



/**

 * BEGIN CUSTOM POST TYPE - productS

 *

 * products have been created as non-hierarchical and are an exact replica of posts (chronological)

 * This is a more typical implementation of a custom post type

 * The post type contains two custom taxonomies - Ingredients and Product Categories

 * Ingredients acts as tags, Product Categories act as categories

 * We ditch the archive-product.php page template in favour of creating our own page called products

 * On the products page we assign the products.php page template to call our custom posts

 * Permalinks are clean and contain the Product Category e.g. /products/indian/product-name

 * Product Category taxonomy permalinks must contain a category base e.g. /products/type/indian

 * Ingredients taxonomy permalinks must contain a tag base e.g. /products/with/paprika

 */



/**

 * FOR DEMO ONLY



 * This section automatially creates the products page and assigns the correct template (products.php)

 */

$products_page_title = 'products';

$products_page_permalink = 'products';



// Define products page content

// -------------------------------------------------------------

$products_page_content = array(

		'post_type' => 'page',

		'post_title' => $products_page_title,

		'post_name' => $products_page_permalink,

		'post_status' => 'publish',

		'post_content' => '<ul><li>This page was auto-generated to display all posts in the products custom post type</li><li>products function like posts and have two taxonomies - Product Categories (categories) and Ingredients (tags)</li><li>A list of both taxonomy terms is automatically generated within the sidebar</li><li>This page (products) uses the products.php page template</li><li><strong>Start adding products and away you go!</strong></li></ul>',

		'post_author' => 1,

);



// Check if the products page exists

// -------------------------------------------------------------

$products_page_data = get_page_by_title($products_page_title);

$products_page_id = $products_page_data->ID;





// Create products page if it does't exist

// -------------------------------------------------------------

if(!isset($products_page_data)){

	wp_insert_post($products_page_content);

	$products_page_data = get_page_by_title($products_page_title);

	$products_page_id = $products_page_data->ID;

	update_post_meta($products_page_id, '_wp_page_template','products.php');

}



/**

 * productS HELPER FUNCTIONS

 *

 * These are theme functions used within the products custom post type

 */

// Return the current taxonomy title

// -------------------------------------------------------------

function current_tax_title() {

	echo get_queried_object()->name;

}



// Return the products page ID

// -------------------------------------------------------------

function page_products() {

	$products_page_data = get_page_by_title('products');

	$products_page_id = $products_page_data->ID;

	return $products_page_id;

}



// Test if we're in the products section (useful for highlighting navigation)

// -------------------------------------------------------------

function is_products() {

	return (is_page('products') || is_singular('product') || is_tax('product_ingredient') || is_tax('product_category'));

}



/**

 * CREATE CUSTOM POST TYPE - product

 *

 * We define the post type name in singular form (product)

 * products are chronological and function like posts

 * Also creates the two custom taxonomies

 */

add_action('init', '_init_product_post_type');



function _init_product_post_type() {



	// Create taxonomy (Product Categories i.e. categories)

	// -------------------------------------------------------------

	register_taxonomy(

	'product_category',

	array( 'product' ),

	array(

	'labels' => array(

	'name' => __( 'Product Categories' ),

	'singular_name' => __( 'Product Category' ),

	'search_items' => __( 'Search Product Categories' ),

	'popular_items' => __( 'Popular Product Categories' ),

	'all_items' => __( 'All Product Categories' ),

	'parent_item' => __( 'Parent Product Category' ),

	'parent_item_colon' => __( 'Parent Product Category:' ),

	'edit_item' => __( 'Edit Product Category' ),

	'update_item' => __( 'Update Product Category' ),

	'add_new_item' => __( 'Add New Product Category' ),

	'new_item_name' => __( 'New Product Category' ),

	),

	'public' => true,

	'show_in_nav_menus' => true,

	'show_ui' => true,

	'publicly_queryable' => true,

	'exclude_from_search' => false,

	'hierarchical' => true,

	'query_var' => true,

	// this sets the taxonomy view URL (must have category base i.e. /type)

	// this can be any depth e.g. food/cooking/products/type

	'rewrite' => array( 'slug' => 'products/product-category', 'with_front' => false ),

	)

	);





	// Create post type (products i.e. posts)

	// -------------------------------------------------------------

	register_post_type( 'product',

	array(

	'capability_type' => 'post',

	'hierarchical' => false,

	'public' => true,

	'show_ui' => true,

	'publicly_queryable' => true,

	'exclude_from_search' => false,

	'menu_position' => 40,

	'labels' => array(

	'name' => __( 'Products' ),

	'singular_name' => __( 'product' ),

	'add_new' => __( 'Add New' ),

	'add_new_item' => __( 'Add New product' ),

	'edit' => __( 'Edit product' ),

	'edit_item' => __( 'Edit product' ),

	'new_item' => __( 'New product' ),

	'view' => __( 'View product' ),

	'view_item' => __( 'View product' ),

	'search_items' => __( 'Search product' ),

	'not_found' => __( 'No products found' ),

	'not_found_in_trash' => __( 'No products found in Trash' )

	),

	'has_archive' => true,

	'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),

	'query_var' => true,

	// this sets where the products section lives and contains a tag to insert the Product Category in URL below

	// this can be any depth e.g. food/cooking/products/%product_categorys%

	'rewrite' => array( 'slug' => 'products/%product_category%', 'with_front' => false ),

	)

	);



	// Make permalinks for products pretty (add Product Category to URL)

	// -------------------------------------------------------------

	add_filter('post_type_link', 'product_permalink_filter_function', 1, 3);

	function product_permalink_filter_function( $post_link, $id = 0, $leavename = FALSE ) {

		if ( strpos('%product_category%', $post_link) === 'FALSE' ) {

			return $post_link;

		}

		$post = get_post($id);

		if ( !is_object($post) || $post->post_type != 'product' ) {

			return $post_link;

		}

		// this calls the term to be added to the URL

		$terms = wp_get_object_terms($post->ID, 'product_category');

		if ( !$terms ) {

			return str_replace('products/%product_category%/', '', $post_link);

		}

		return str_replace('%product_category%', $terms[0]->slug, $post_link);

	}



	// -------------------------------------------------------------

	// FIX - Makes permalinks work!

	// This must come at the end of your LAST custom post type

	// REMOVE after development (when everything's working!)

	// -------------------------------------------------------------

	flush_rewrite_rules();

	// -------------------------------------------------------------



}



/*

 * FIX - Make custom post type pagination work!

*

* Taken from http://wordpress.stackexchange.com/a/16929/9244

* Solves issue with /page/2/ of custom post types giving a 404 error

* remove if you're not using the products post type or not paginating

*/

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