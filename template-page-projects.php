<?php 
/*
Template Name: Filterable Portfolio
*/
?>
<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs', 'page'); ?>

<h1 class="page_title"><?php the_title(); ?></h1>

<div id="content-area" class="fullwidth clearfix">
	<div class="post-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Flexible').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			<?php edit_post_link(esc_attr__('Edit this page','Flexible')); ?>
		<?php endwhile; ?>
	</div> 	<!-- end .post-content -->
	<?php
		$et_ptemplate_settings = array();
		$et_ptemplate_settings = get_post_meta($post->ID,'et_ptemplate_settings',true);
		$et_ptemplate_projectcats = isset( $et_ptemplate_settings['et_ptemplate_projectcats'] ) ? (array) $et_ptemplate_settings['et_ptemplate_projectcats'] : array();

		$portfolio_args = array(
			'post_type' => 'project',
			'showposts' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => $et_ptemplate_projectcats,
					'operator' => 'IN'
				)
			)
		);
		if ( empty( $et_ptemplate_projectcats ) )  unset ( $portfolio_args['tax_query'] );
		
		$portfolio_query = new WP_Query( apply_filters( 'et_home_portfolio_args', $portfolio_args ) );
		
		$categories = get_terms( 'project_category', array( 'include' => $et_ptemplate_projectcats ) );
		if ( $categories ){
			echo '<ul id="et_portfolio_sort_links">';
				echo '<li class="active">' . '<a href="#" data-categories_id="all">' . __( 'All', 'Flexible' ) . '</a>' . '</li>';
				foreach ( $categories as $category ){
					echo '<li>' . '<a href="#" data-categories_id="' . esc_attr( 'project_cat_' . $category->term_taxonomy_id ) . '">' . esc_html( $category->name ) . '</a>' . '</li>';
				}
			echo '</ul>';
		}
	?>
	<div id="portfolio-grid" class="clearfix">
	<?php
		while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();			
			get_template_part( 'includes/entry', 'portfolio' );
		endwhile; 
		wp_reset_postdata(); 
	?>
	</div> <!-- end #portfolio-grid -->
</div> 	<!-- end #content-area -->

<?php get_footer(); ?>