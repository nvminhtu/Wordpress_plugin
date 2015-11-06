

<?php 

$readcount=1;

	/* nvmt: Template for showing destination list with specific area */

		$terms = get_the_terms( $post->ID, 'areas' );

			if ( $terms && ! is_wp_error( $terms ) ) : 

				$areas = array();

					foreach ( $terms as $term ) { $areas[] = $term->slug; }

						$att_area=$areas[0]; // Get 1 area - the first value ?>

<?php endif; ?>



<?php

		// The Query 

		$args = array(

					'post_type' => 'destination',

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

		$order=1;

		if ( $query->have_posts() ) {

				echo '<div class="destination_widget_wrap">';

				echo '<h1 class="destination_title">Related destinations</h1>';

				echo '<ul class="posts_list">';

				while ( $query->have_posts() ) 

				{

					global $post;

					$query->the_post();

					$title = get_the_title();

					

					if($order<=$readcount) { echo '<li>'; } else { echo '<li id="listshow" style="display: none">'; }

						if (has_post_thumbnail() ){

							echo '<a class="city_thumbnail" href="'.get_permalink().'" title="'.get_the_title().'">';

							echo get_the_post_thumbnail(get_the_ID(),array(304,153),array('title'=>get_the_title(),'alt'=>get_the_title()));

							echo '</a>';

						}

						//description

						$excerpt = $post->post_excerpt;$desc_length =200;

						$excerpt = apply_filters('get_the_excerpt', $excerpt);

						echo '<h3 class="city_widget_title"><a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.$title.'</a></h3>';

						echo '<p>'.wp_html_excerpt($excerpt,$desc_length).'...</p>';

						echo '</li>';

					

					

					$order++;

				}

				echo '</ul>';

				echo '</div>';

				if($order>$readcount) 

				{

					echo '<div class="readmore">View more...</div>'; 

					echo '<div class="readless" style="display: none">View less...</div>'; 

				}

			
?>
			

					<script>

					/*	jQuery(".readmore-eat").click(function () {

							jQuery("li#listshow-eat").show("slow");

							jQuery(".readmore-eat").hide();

							jQuery(".readless-eat").show();

						});

						jQuery(".readless-eat").click(function () {

							jQuery("li#listshow-eat").hide();

							jQuery(".readmore-eat").show();

							jQuery(".readless-eat").hide();

						});*/

					</script>

				<?php

				 

				 

		} else {

				// no posts found

		}

		wp_reset_postdata();

		

?>