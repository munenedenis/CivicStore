<?php 
/*
Template Name: Sitemap Page
*/
?>
<?php 
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
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
				
				<div id="sitemap" class="responsive">
					<div class="sitemap-col">
						<h2><?php esc_html_e('Pages','Flexible'); ?></h2>
						<ul id="sitemap-pages"><?php wp_list_pages('title_li='); ?></ul>
					</div> <!-- end .sitemap-col -->
					
					<div class="sitemap-col">
						<h2><?php esc_html_e('Categories','Flexible'); ?></h2>
						<ul id="sitemap-categories"><?php wp_list_categories('title_li='); ?></ul>
					</div> <!-- end .sitemap-col -->
					
					<div class="sitemap-col<?php if (!$fullwidth) echo ' last'; ?>">
						<h2><?php esc_html_e('Tags','Flexible'); ?></h2>
						<ul id="sitemap-tags">
							<?php $tags = get_tags();
							if ($tags) {
								foreach ($tags as $tag) {
									echo '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li> ';
								}
							} ?>
						</ul>
					</div> <!-- end .sitemap-col -->
					
					<?php if (!$fullwidth) { ?>
						<div class="clear"></div>
					<?php } ?>
					
					<div class="sitemap-col<?php if ($fullwidth) echo ' last'; ?>">
						<h2><?php esc_html_e('Authors','Flexible'); ?></h2>
						<ul id="sitemap-authors" ><?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?></ul>
					</div> <!-- end .sitemap-col -->
				</div> <!-- end #sitemap -->
				
				<div class="clear"></div>
				
				<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Flexible').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php edit_post_link(esc_attr__('Edit this page','Flexible')); ?>
			</div> 	<!-- end .post-content -->
		</article> <!-- end .entry -->
	</div> <!-- end #left_area -->

	<?php if ( ! $fullwidth ) get_sidebar(); ?>
</div> 	<!-- end #content-area -->

<?php get_footer(); ?>