<?php
/**
 * Plugin Name: AJAX Load Posts
 * Plugin URI: 
 * Description: Load more post with Ajax
 * Version: 0.1
 * Author: Tu Nguyen
 * Author URI: 
 */
 
 /**
  * Initialization. Add our script if needed on this page.
  */
 function pcode_init() {
 	global $wp_query;
 
 	// Add code to index pages.
 	if( !is_singular() ) {	
 		// Queue JS and CSS
 		wp_enqueue_script(
 			'pcode-load-posts',
 			plugin_dir_url( __FILE__ ) . 'js/load-posts.js',
 			array('jquery'),
 			'1.0',
 			true
 		);
 		
 		wp_enqueue_style(
 			'pcode-style',
 			plugin_dir_url( __FILE__ ) . 'css/style.css',
 			false,
 			'1.0',
 			'all'
 		);
 		
 		// What page are we on? And what is the pages limit?
 		$max = $wp_query->max_num_pages;
    $numPosts = $wp_query->$found_posts;
 		$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
 		
 		// Add some parameters for the JS.
 		wp_localize_script(
 			'pcode-load-posts',
 			'pcode',
 			array(
 				'startPage' => $paged,
 				'maxPages' => $max,
        'numPosts' => $numPosts,
 				'nextLink' => next_posts($max, false)
 			)
 		);
 	}
 }
 add_action('template_redirect', 'pcode_init');
 
 ?>