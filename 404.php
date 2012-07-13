<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs', 'index'); ?>

<div id="content-area" class="clearfix">
	<div id="left-area">		
		<?php get_template_part('includes/no-results','404'); ?>
	</div> 	<!-- end #left-area -->
	<?php get_sidebar(); ?>
</div> 	<!-- end #content-area -->
	
<?php get_footer(); ?>