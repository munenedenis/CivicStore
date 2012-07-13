<?php get_header(); ?>

<?php if ( 'on' == et_get_option('flexible_featured', 'on') && is_home() ) get_template_part( 'includes/featured', 'home' ); ?>

<?php if ( 'on' == et_get_option('flexible_quote','on') ) { ?>
	<div id="quote">
		<h2><?php echo esc_html( et_get_option('flexible_quote_heading') ); ?></h2>
		<p><?php echo esc_html( et_get_option('flexible_quote_text') ); ?></p>
	</div> <!-- end #quote -->
<?php } ?>

<?php if ( 'on' == et_get_option('flexible_display_recentwork_section','on') ) { ?>
	<section id="portfolio" class="clearfix">
		<h1 class="section-title"><?php esc_html_e( 'Design', 'Flexible' ); ?></h1>
		<span class="section-tagline"><?php esc_html_e( 'Recent work', 'Flexible' ); ?></span>
		
		<a href="<?php echo esc_url( et_get_option( 'flexible_more_work_url', '#' ) ); ?>" class="more"><?php esc_html_e( 'More work &raquo;', 'Flexible' ); ?></a>
		
		<?php
			$portfolio_args = array(
				'post_type' => 'project',
				'showposts' => (int) et_get_option('flexible_homepage_numposts_projects'),
				'tax_query' => array(
					array(
						'taxonomy' => 'project_category',
						'field' => 'id',
						'terms' => (array) et_get_option('flexible_homepage_exlcats_projects'),
						'operator' => 'NOT IN'
					)
				)
			);
		
			$categories = get_terms( 'project_category', array( 'exclude' => array( et_get_option('flexible_homepage_exlcats_projects') ) ) );
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
			$portfolio_query = new WP_Query( apply_filters( 'et_home_portfolio_args', $portfolio_args ) );
			while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();			
				get_template_part( 'includes/entry', 'portfolio' );
			endwhile; 
			wp_reset_postdata(); 
		?>
		</div> <!-- end #portfolio-grid -->
	</section> <!-- end #portfolio -->
<?php } ?>

<?php if ( 'on' == et_get_option('flexible_display_fromblog_section','on') && ( 'false' == et_get_option('flexible_blog_style','false') ) ) { ?>
	<section id="blog" class="clearfix">
		<h1 class="section-title"><?php esc_html_e( 'Blog', 'Flexible' ); ?></h1>
		<span class="section-tagline"><?php esc_html_e( 'Recent news', 'Flexible' ); ?></span>
		
		<a href="<?php echo esc_url( et_get_option( 'flexible_more_posts_url', '#' ) ); ?>" class="more"><?php esc_html_e( 'More posts &raquo;', 'Flexible' ); ?></a>
		
		<div id="blog-grid">
			<?php $i = 0; ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php 
					$i++;
					$last_class = ( $i % 3 == 0 ) ? ' last' : '';
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('blog-item' . $last_class); ?>>
					<span class="date"><?php echo get_the_time( 'M' ); ?><strong><?php echo get_the_time( 'd' ); ?></strong></span>

					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="meta-info"><?php printf( __('Posted on %1$s by %2$s', 'Flexible'), get_the_time( apply_filters( 'et_home_post_date_format', 'M j' ) ), et_get_the_author_posts_link() ); ?></p>
					<p><?php truncate_post(180); ?></p>
				</article> <!-- end .blog-item -->
			<?php endwhile; else : ?>
				<article id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'Flexible' ); ?></h1>
				</article><!-- end #post-0 -->
			<?php endif; ?>
		</div> <!-- end #blog-grid -->
	</section> <!-- end #blog -->
<?php } ?>

<?php if ( 'on' == et_get_option('flexible_blog_style') ) { ?>
	<div id="content-area" class="clearfix">
		<div id="left-area">		
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php get_template_part('includes/entry', 'index'); ?>
			<?php
			endwhile;
				if (function_exists('wp_pagenavi')) { wp_pagenavi(); }
				else { get_template_part('includes/navigation','entry'); }
			else:
				get_template_part('includes/no-results','entry');
			endif; ?>
		</div> 	<!-- end #left-area -->
		<?php get_sidebar(); ?>
	</div> 	<!-- end #content-area -->
<?php } ?>
	
<?php get_footer(); ?>