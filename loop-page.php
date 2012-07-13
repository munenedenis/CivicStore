<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>				
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
			<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Flexible').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			<?php edit_post_link(esc_attr__('Edit this page','Flexible')); ?>
		</div> 	<!-- end .post-content -->
	</article> <!-- end .entry -->
<?php endwhile; // end of the loop. ?>