<?php
/**
 * The Template for displaying all single tour.
 */
$post_id = get_queried_object_id();

$layout = get_post_meta($post_id, '_layout',true);
if(empty($layout)){
	$layout = 'right';
}

get_header();
echo theme_generator('introduce',$post_id);?>
<div id="page">
	<div class="inner <?php if($layout=='right'):?>right_sidebar<?php endif;?><?php if($layout=='left'):?>left_sidebar<?php endif;?>">
		<div id="main">
			<?php echo theme_generator('breadcrumbs',$post_id);?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post();
		$image_src = theme_get_image_src(array('type'=>'attachment_id','value'=>get_post_thumbnail_id()), array(285, 150));
 ?>
		<div class="content" <?php post_class(); ?>>
			<div class="one_half">
				<?php echo '<img width="285" height="150" src="'.$image_src.'" alt="'.get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true).'" />';?>
			</div>
			<div class="one_half last">
				<?php the_content(); ?>
			</div>
			<div class="clear"></div>
			
			<div class="divider_padding"></div>
			
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'theme_front' ), 'after' => '</div>' ) ); ?>
			<?php edit_post_link(__('Edit', 'theme_front'),'<footer><p class="entry_edit">','</p></footer>'); ?>
			<div class="clearboth"></div>
		</div>
<?php endwhile; // end of the loop.?>
		</div>
		<?php if($layout != 'full') get_sidebar(); ?>
		<div class="clearboth"></div>
	</div>
	<div id="page_bottom"></div>
</div>
<?php get_footer(); ?>