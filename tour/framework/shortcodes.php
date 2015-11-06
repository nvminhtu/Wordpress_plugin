<?php

/* tour Shortcode --------------------------------------------------------------------------------------------------------------- */

function theme_shortcode_tour($atts) {

	extract(shortcode_atts(array(

		'count' => '',

		'posts_per_page' => '50',

		'thumbnail' => 'true',

		'extra' => 'desc',

		'cat' => '',

		'posts' => '',

		'author' => '',

		'offset' => 0,

		'title_length' => '',

		'desc_length' => '80',

		'exclude_ids' => '',

	), $atts));

	$cache = wp_cache_get('shortcode_tour', 'shortcode');

	if ( ! is_array( $cache ) )

		$cache = array();

	$cache_id = md5(serialize($atts));

	if ( isset( $cache[ $cache_id ] ) ) {

		return $cache[ $cache_id ];

	}

	global $wp_filter;

	$the_content_filter_backup = $wp_filter['the_content'];

	$query = array('post_type'=> 'tour','showposts' => $count, 'nopaging' => 0, 'posts_per_page' => $posts_per_page, 'post_status' => 'publish', 'post__not_in' => array($exclude_ids) , 'ignore_sticky_posts' => 1);

	if($cat != ''){

		global $wp_version;

		if(version_compare($wp_version, "3.1", '>=')){

			$query['tax_query'] = array(

				array(

					'taxonomy' => 'tour_category',

					'field' => 'slug',

					'terms' => explode(',', $cat)

				)

			);

		}else{

			$query['taxonomy'] = 'tour_category';

			$query['term'] = $cat;

		}

	}

	if($posts){

		$query['post__in'] = explode(',',$posts);

	}

	if($author){

		$query['author'] = $author;

	}

	if($extra == 'both'){

		$extra = array('time','desc');

	}else{

		$extra = array($extra);

	}

	if((int)$offset != 0){

		$query['offset'] = (int)$offset;

	}

	$r = new WP_Query($query);

	$output = '';

	if ($r->have_posts()){

		$output = '<div class="posttype_wrap tour_wrap">';

		$output .= '<div class="posttype_wrap_inner tour_widget_wrap">';

		$output .= '<ul class="posts_list">';

		while ($r->have_posts()){

			$r->the_post();

			$output .= '<li>';

			if($thumbnail!='false'){

				if (has_post_thumbnail() ){

					$output .= '<a class="thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';

					$output .= get_the_post_thumbnail(get_the_ID(),array(210,115),array('title'=>get_the_title(),'alt'=>get_the_title()));

					$output .= '</a>';

				}

			}

			$output .= '<div class="post_extra_info">';

			$title = get_the_title();

			if((int)$title_length){

				$title = theme_strcut($title,$title_length,'...');

			}

			$output .= '<h3 class="title_h3_widget"><a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.$title.'</a></h3>';

			if(in_array('time', $extra)){

				$output .= '<time datetime="'.get_the_time('Y-m-d').'">'.get_the_date().'</time>';

			}

			if(in_array('desc', $extra)){

				global $post;

				$excerpt = $post->post_excerpt;

				$excerpt = apply_filters('get_the_excerpt', $excerpt);

				$output .= '<p>'.wp_html_excerpt($excerpt,$desc_length).'...</p>';

			}

			$output .= '</div>';

			$output .= '<div class="clearboth"></div>';

			$output .= '</li>';

		}

		$output .= '</ul>';

		$output .= '</div>';

		$output .= '</div>';

	} 

	wp_reset_query();

	$wp_filter['the_content'] = $the_content_filter_backup;

	$cache[$cache_id] = $output;

	wp_cache_set('shortcode_tour', $cache, 'shortcode');

	return $output;

}

add_shortcode('tour', 'theme_shortcode_tour');







