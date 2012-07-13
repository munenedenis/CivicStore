<?php get_header(); ?>
	
<?php get_template_part('includes/breadcrumbs', 'index'); ?>
<div id="content-area" class="clearfix">
	<h1 class="page_title"><?php single_term_title(); ?></h1>
	<div id="portfolio-grid">	
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'includes/entry', 'portfolio' ); ?>
		<?php endwhile; ?>
			<?php 
				if (function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { get_template_part('includes/navigation','portfolio'); }
			?>
		<?php else : ?>
			<?php get_template_part( 'includes/no-results' ); ?>
		<?php endif; ?>
	</div> <!-- end #portfolio-grid -->
</div> 	<!-- end #content-area -->
	
<?php get_footer(); ?>