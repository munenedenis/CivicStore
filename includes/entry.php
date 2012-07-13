<article id="post-<?php the_ID(); ?>" <?php post_class('entry clearfix'); ?>>
	<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	
	<?php
		$thumb = '';
		$width = apply_filters('et_blog_image_width',640);
		$height = apply_filters('et_blog_image_height',320);
		$classtext = '';
		$titletext = get_the_title();
		$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Blogimage');
		$thumb = $thumbnail["thumb"];
	?>
	<?php if ( '' != $thumb && 'on' == et_get_option('flexible_thumbnails_index') ) { ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
			</a>	
		</div> 	<!-- end .post-thumbnail -->
	<?php } ?>

	<div class="post-content">
		<?php 
			$index_postinfo = et_get_option('flexible_postinfo1');
			if ( $index_postinfo ){
				echo '<p class="meta-info">';
				et_postinfo_meta( $index_postinfo, et_get_option('flexible_date_format'), esc_html__('0 comments','Flexible'), esc_html__('1 comment','Flexible'), '% ' . esc_html__('comments','Flexible') );
				echo '</p>';
			}
			
			if ( 'on' == et_get_option('flexible_blog_style') ) the_content('');
			else echo '<p>' . truncate_post(360,false) . '</p>'; 
		?>
		<a href="<?php the_permalink(); ?>" class="readmore"><?php esc_html_e( 'Read More', 'Flexible' ); ?></a>
	</div> 	<!-- end .post-content -->
</article> <!-- end .entry -->