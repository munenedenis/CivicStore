<?php 
/*
Template Name: Gallery Page
*/
?>
<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : (bool) $et_ptemplate_settings['et_fullwidthpage'];

$gallery_cats = isset( $et_ptemplate_settings['et_ptemplate_gallerycats'] ) ? $et_ptemplate_settings['et_ptemplate_gallerycats'] : array();
$et_ptemplate_gallery_perpage = isset( $et_ptemplate_settings['et_ptemplate_gallery_perpage'] ) ? (int) $et_ptemplate_settings['et_ptemplate_gallery_perpage'] : 12;
?>

<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs', 'page'); ?>

<div id="content-area" class="clearfix<?php if ( $fullwidth ) echo ' fullwidth'; ?>">
	<div id="left-area">
		<article id="post-<?php the_ID(); ?>" <?php post_class('entry clearfix'); ?>>
			<h1 class="page_title"><?php the_title(); ?></h1>

			<?php
				$thumb = '';
				$width = apply_filters('et_blog_image_width',640);
				$height = apply_filters('et_blog_image_height',320);
				$classtext = '';
				$titletext = get_the_title();
				$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Blogimage');
				$thumb = $thumbnail["thumb"];
			?>
			<?php if ( '' != $thumb && 'on' == et_get_option('flexible_page_thumbnails') ) { ?>
				<div class="post-thumbnail">
					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>	
				</div> 	<!-- end .post-thumbnail -->
			<?php } ?>
			
			<div class="post-content">
				<?php the_content(); ?>
				
				<div id="et_pt_gallery" class="clearfix responsive">
					<?php $gallery_query = ''; 
					if ( !empty($gallery_cats) ) $gallery_query = '&cat=' . implode(",", $gallery_cats);
					else echo '<!-- gallery category is not selected -->'; ?>
					<?php 
						$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
					?>
					<?php query_posts("showposts=$et_ptemplate_gallery_perpage&paged=" . $et_paged . $gallery_query); ?>
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<?php $width = 207;
						$height = 136;
						$titletext = get_the_title();

						$thumbnail = get_thumbnail($width,$height,'portfolio',$titletext,$titletext,true,'Portfolio');
						$thumb = $thumbnail["thumb"]; ?>
						
						<div class="et_pt_gallery_entry">
							<div class="et_pt_item_image">
								<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, 'portfolio'); ?>
								<span class="overlay"></span>
								
								<a class="zoom-icon fancybox" title="<?php the_title(); ?>" rel="gallery" href="<?php echo($thumbnail['fullpath']); ?>"><?php esc_html_e('Zoom in','Flexible'); ?></a>
								<a class="more-icon" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more','Flexible'); ?></a>
							</div> <!-- end .et_pt_item_image -->
						</div> <!-- end .et_pt_gallery_entry -->
						
					<?php endwhile; ?>
						<div class="page-nav clearfix">
							<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
							else { ?>
								 <?php get_template_part('includes/navigation'); ?>
							<?php } ?>
						</div> <!-- end .entry -->
					<?php else : ?>
						<?php get_template_part('includes/no-results'); ?>
					<?php endif; wp_reset_query(); ?>
				</div> <!-- end #et_pt_gallery -->
				
				<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Flexible').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php edit_post_link(esc_attr__('Edit this page','Flexible')); ?>
			</div> 	<!-- end .post-content -->
		</article> <!-- end .entry -->
	</div> <!-- end #left_area -->

	<?php if ( ! $fullwidth ) get_sidebar(); ?>
</div> 	<!-- end #content-area -->

<?php get_footer(); ?>