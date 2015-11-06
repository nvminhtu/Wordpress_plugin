
<?php 
$readcount=1;
	/* nvmt: Template for showing cityguides list with specific area */
		$terms = get_the_terms( $post->ID, 'areas' );
			if ( $terms && ! is_wp_error( $terms ) ) : 
				$areas = array();
					foreach ( $terms as $term ) { $areas[] = $term->slug; }
						$att_area=$areas[0]; // Get 1 area - the first value ?>
<?php endif; ?>

<?php
		//The name of taxonomy Type
		if($typecat=="things-to-see") { $typename ="See";$typeclass="-see"; }
		else if($typecat=="things-to-do") {$typename ="Do";$typeclass="-do"; } 
		else { $typename = "Eat";$typeclass="-eat";}
		// The Query 
		$args = array(
					'post_type' => 'cityguides',
					'tax_query' => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'areas',
							'field' => 'slug',
							'terms' => $att_area
						),
						array(
							'taxonomy' => 'type',
							'field' => 'slug',
							'terms' => $typecat,
						)
					)
			);
		$query = new WP_Query( $args );
		// The Loop
		$order=1;
		if ( $query->have_posts() ) {
				echo '<div class="city_widget_wrap">';
				echo '<h1 class="city_see">Must '.$typename.'</h1>';
				echo '<ul class="posts_list">';
				while ( $query->have_posts() ) 
				{
					global $post;
					$query->the_post();
					$title = get_the_title();
					
					if($order<=$readcount) { echo '<li>'; } else { echo '<li id="listshow'.$typeclass.'" style="display: none">'; }
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
					echo '<div class="readmore'.$typeclass.'">View more...</div>'; 
					echo '<div class="readless'.$typeclass.'" style="display: none">View less...</div>'; 
				}
			
				if($typecat=="things-to-see")
				{
				?>
					<script>
						jQuery(".readmore-see").click(function () {
							jQuery("li#listshow-see").show("slow");
							jQuery(".readmore-see").hide();
							jQuery(".readless-see").show();
						});
						jQuery(".readless-see").click(function () {
							jQuery("li#listshow-see").hide();
							jQuery(".readmore-see").show();
							jQuery(".readless-see").hide();
						});
					</script>
				<?php
				 }
				 else if($typecat=="things-to-eat")
				 {
				 ?>	<script>
						jQuery(".readmore-eat").click(function () {
							jQuery("li#listshow-eat").show("slow");
							jQuery(".readmore-eat").hide();
							jQuery(".readless-eat").show();
						});
						jQuery(".readless-eat").click(function () {
							jQuery("li#listshow-eat").hide();
							jQuery(".readmore-eat").show();
							jQuery(".readless-eat").hide();
						});
					</script>
				<?php }
				 else
				 { ?>
					<script>
						jQuery(".readmore-do").click(function () {
							jQuery("li#listshow-do").show("slow");
							jQuery(".readmore-do").hide();
							jQuery(".readless-do").show();
						});
						jQuery(".readless-do").click(function () {
							jQuery("li#listshow-do").hide();
							jQuery(".readmore-do").show();
							jQuery(".readless-do").hide();
						});
					</script>
				<?php }
				 
				 
		} else {
				// no posts found
		}
		/* Restore original Post Data */
		wp_reset_postdata();
		
?>