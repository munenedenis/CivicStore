<?php if (et_get_option('flexible_integration_single_top') <> '' && et_get_option('flexible_integrate_singletop_enable') == 'on') echo (et_get_option('flexible_integration_single_top')); ?>
	
<article id="post-<?php the_ID(); ?>" <?php post_class('entry clearfix'); ?>>
	<div class="post-content">
		<?php 
			$index_postinfo = et_get_option('flexible_postinfo2');
			if ( $index_postinfo ){
				echo '<p class="meta-info">';
				et_postinfo_meta( $index_postinfo, et_get_option('flexible_date_format'), esc_html__('0 comments','Flexible'), esc_html__('1 comment','Flexible'), '% ' . esc_html__('comments','Flexible') );
				echo '</p>';
			}
		?>
		<?php the_content(); ?>
		<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Flexible').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<?php edit_post_link(esc_attr__('Edit this page','Flexible')); ?>
	</div> 	<!-- end .post-content -->
</article> <!-- end .entry -->

<?php if (et_get_option('flexible_integration_single_bottom') <> '' && et_get_option('flexible_integrate_singlebottom_enable') == 'on') echo(et_get_option('flexible_integration_single_bottom')); ?>
	
<?php 
	if ( et_get_option('flexible_468_enable') == 'on' ){
		if ( et_get_option('flexible_468_adsense') <> '' ) echo( et_get_option('flexible_468_adsense') );
		else { ?>
		   <a href="<?php echo esc_url(et_get_option('flexible_468_url')); ?>"><img src="<?php echo esc_url(et_get_option('flexible_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
<?php 	}    
	}
?>

<?php 
	if ( 'on' == et_get_option('flexible_show_postcomments') ) comments_template('', true);
?>