<?php 
	/* nvmt: Template for showing cityguides list with specific area */
		$terms = get_the_terms( $post->ID, 'areas' );
			if ( $terms && ! is_wp_error( $terms ) ) : 
				$areas = array();
					foreach ( $terms as $term ) { $areas[] = $term->slug; }
						$att_area=$areas[0]; // Get 1 area - the first value ?>
<?php endif; ?>
<?php
		// The Query 
		$args = array(
					'post_type' => 'hotel',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'areas',
							'field' => 'slug',
							'terms' => $att_area
						)
					)
			);
		$query = new WP_Query( $args );
		// The Loop
		if ( $query->have_posts() ) {
				$i=1;
				while ( $query->have_posts() ) 
				{
					global $post;
					$query->the_post();
					$title = get_the_title();
					if($i%4==0) { $last=" last"; }else {$last="";}
					echo '<div class="one_fourth'.$last.'">';
				
					if (has_post_thumbnail() ){
						echo '<a href="'.get_permalink().'" title="'.get_the_title().'">';
						echo get_the_post_thumbnail(get_the_ID(),array(304,77),array('title'=>get_the_title(),'alt'=>get_the_title()));
						echo '</a>';
					}
					echo '<div class="clear"></div>';
					echo '<h4 class="hotel-title"><a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.$title.'</a></h4>';
					$hotel_star = get_post_meta($post->ID, '_hotel_star_rating', true);
					if($hotel_star=="3 Stars") { $star_img="img/hotels/3-stars.png";} else if($hotel_star=="4 Stars") { $star_img="img/hotels/4-stars.png";} else{$star_img="img/hotels/5-stars.png";} 
					echo '<img src="' . plugins_url($star_img, __FILE__ ) . '" > '; 
					echo '<div class="clear"></div>';
					echo '</div>';
					$i++;
				}
				
				
		} else {
				// no posts found
		}
		/* Restore original Post Data */
		wp_reset_postdata();
?>
<div style="clear:both"></div>							