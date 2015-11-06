<?php
/**
 * The Template for displaying all single posts.
 */
$post_id = get_queried_object_id();
$blog_page = theme_get_option('blog','blog_page');
if($blog_page == $post_id){
	return load_template(THEME_DIR . "/template_blog.php");
}

$layout = theme_get_inherit_option($post_id, '_layout', 'blog','single_layout');
get_header();
echo theme_generator('introduce',$post_id);?>

<div id="page">
  <div class="inner right_sidebar"> <?php echo theme_generator('breadcrumbs',$post_id);?> <?php echo theme_generator('page_introduce',$post_id);?>
    <div id="main">
      <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
      <?php
/**
 * The default template for displaying content in single.php template
 */
$post_id = get_queried_object_id();
$featured_image_type = theme_get_option('blog', 'single_featured_image_type');
$effect=theme_get_option('blog','single_effect');
$layout = theme_get_inherit_option($post_id, '_layout', 'blog','single_layout');
$depart_from = get_post_meta($post_id, '_depart_from', true);
$depart_to = get_post_meta($post_id, '_depart_to', true);
if($featured_image_type == 'full' || $featured_image_type == 'below'){
	if ($layout == 'full'){
		$width = 960;
	} else {
		$width = 630;
	}
} else {
	$width = '';
}
?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('entry content entry_'.$featured_image_type); ?>>
        <header>
          <div class="tour_box">
			
			<div class="two_third">
            <?php if(!theme_get_inherit_option($post_id, '_show_in_header', 'blog','show_in_header')):?>
            <div class="entry_info">
              <h3><a href="<?php echo get_permalink() ?>" rel="bookmark" title="<?php printf( __("Permanent Link to %s", 'theme_front'), get_the_title() ); ?>">
                <?php the_title(); ?>
                </a></h3>
              <!--div class="entry_meta">
<!?php echo theme_generator('blog_meta'); ?>
			</div-->
            </div>
            <?php endif;?>
            <?php echo theme_generator('blog_featured_image',$featured_image_type,360,'',false,$effect,true); ?>
			</div>
			
			<div class="one_third last">
				<div class="social_share">
					<?php if( function_exists('ADDTOANY_SHARE_SAVE_KIT') ) { ADDTOANY_SHARE_SAVE_KIT(); } ?> 
				</div>
            <ul class="list10 list_color_gray">
              <li><span>Tour Code:</span> <?php echo get_post_meta($post_id, '_tour_code', true);?></li>
              <li><span>Depart:</span>
                <?php if(!empty($depart_from)):?>
                <a href="<?php echo get_permalink($depart_from);?>"><?php echo get_the_title($depart_from); ?></a>
                <?php endif;?>
                to
                <?php if(!empty($depart_to)):?>
                <a href="<?php echo get_permalink($depart_to);?>"><?php echo get_the_title($depart_to); ?></a>
                <?php endif;?>
              </li>
              <li><span>Price:</span> Request A Quote</li>
              <li><span>Type:</span> <?php echo get_post_meta($post_id, '_tour_type', true);?></li>
            </ul>
				<p class="center">
					<a class="button medium primary request-quote" style="background-color:#285844" href="http://trekkingsapa.com/request-quote/?request-quote=<?php the_title();  ?>">
					<span style="color:#fff;">Request quote</span>
					</a>
				</p>

			</div>
			<div class="clearboth"></div>
          </div>
        </header>
			<div class="tour_tab">
          <div class="tabs_container tabs_inited">
            <ul class="tabs">
              <li><a href="#">Overview</a></li>
              <li><a href="#">Itinerary</a></li>
              <li><a href="#">Tour &#038; Information</a></li>
            </ul>
            <div class="panes">
              <div class="pane">
                <?php the_content(); ?>
                <h3>Included Highlights</h3>
                <ul class="list4 list_color_red">
                  <?php 
						$days = get_post_meta($post_id, '_tour_days_count', true);
						for($i = 1; $i <= $days; $i++){
							echo '<li>Day '.$i.': '.get_post_meta($post_id, '_tour_day_'.$i.'_headline', true).'</li>';
						}
						?>
                </ul>
                <div class="divider"></div>
                <h3>Recent Photos of this Tour</h3>
                <?php //echo apply_filters("the_content", get_post_meta($post_id, '_tour_photo', true));
                  echo do_shortcode('[slideshow type="nivo" source="{s:tour}"][/slideshow] ');

                ?>

                <div class="divider"></div>
                <h3>Customer Reviews</h3>
                <?php //comments_template( '', true ); ?>
              </div>
              <div class="pane">
                <h3>Flexible Itinerary</h3>
                <?php 
						$days = get_post_meta($post_id, '_tour_days_count', true);
						for($i = 1; $i <= $days; $i++){
							echo '<h4 class="day_highlight">Day '.$i.': '.get_post_meta($post_id, '_tour_day_'.$i.'_headline', true).'</h4>';
							echo apply_filters("the_content", get_post_meta($post_id, '_tour_day_'.$i.'_content', true));
						}
						?>
              </div>
              <div class="pane"> 
                <?php
						$tour_trip_include = get_post_meta($post_id, '_tour_trip_include', true);
						if(!empty($tour_trip_include)){
							echo '<h3>Trip Included</h3>';
							echo apply_filters("the_content",$tour_trip_include);
						}
						$tour_trip_excluded = get_post_meta($post_id, '_tour_trip_excluded', true);
						if(!empty($tour_trip_excluded)){
							echo '<div class="divider"></div>';
							echo '<h3>Trip Excluded</h3>';
							echo apply_filters("the_content",$tour_trip_excluded);
						}
						$tour_trip_add_info = get_post_meta($post_id, '_tour_trip_add_info', true);
						if(!empty($tour_trip_excluded)){
							echo '<div class="divider"></div>';
							echo '<h3>Additional Information</h3>';
							echo apply_filters("the_content",$tour_trip_add_info);
						}
						?>
              </div>
            </div>
          </div>
		</div>
        <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'theme_front' ), 'after' => '</div>' ) ); ?>
		 <h3 style="color:#9B2E1A">Other tours you may be interested in</h3>
    <?php echo do_shortcode('[tour desc_length="200" posts_per_page="5" exclude_ids="'.$post_id.'"]'); ?>
		<!--related cruises-->
		
		
        <footer>
          <?php edit_post_link(__('Edit', 'theme_front'),'<p class="entry_edit">','</p>'); ?>
          <?php /*?>		<?php if(theme_get_inherit_option($post->ID,'_related_popular','blog','related_popular')):?>
		<div class="related_popular_wrap">
			<div class="one_half">
				<?php echo theme_generator('blog_related_posts');?>
			</div>
			<div class="one_half last">
				<?php echo theme_generator('blog_popular_posts');?>
			</div>
			<div class="clearboth"></div>
		</div>
		<?php endif;?><?php */?>
          <?php if(theme_get_option('blog','entry_navigation')):?>
          <nav class="entry_navigation">
            <div class="nav-previous">
              <?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'theme_front' ) . '</span> %title' ); ?>
            </div>
            <div class="nav-next">
              <?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'theme_front' ) . '</span>' ); ?>
            </div>
          </nav>
          <?php endif;?>
        </footer>
        <div class="clearboth"></div>
      </article>
      <?php endwhile; // end of the loop.?>
    </div>
    <?php if($layout != 'full') get_sidebar(); ?>
    <div class="clearboth"></div>
  </div>
</div>
<?php get_footer(); ?>
