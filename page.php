<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs', 'page'); ?>

<div id="content-area" class="clearfix">
	<div id="left-area">
		<?php get_template_part('loop', 'page'); ?>
		<?php if ( 'on' == et_get_option('flexible_show_pagescomments') ) comments_template('', true); ?>
	</div> <!-- end #left_area -->

	<?php get_sidebar(); ?>
</div> 	<!-- end #content-area -->
	
<?php get_footer(); ?>