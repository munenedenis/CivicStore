<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<nav id="nav-single">
						<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentyeleven' ); ?></h3>
						
						<span class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?></span>
						<span class="nav-next"><?php next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?></span>
					</nav><!-- #nav-single -->

					<?php get_template_part( 'content', 'single' ); 
									
						//check for metas and print
						$androidurl=get_post_meta($post->ID, 'field_inputtext__1', true);
						if($androidurl!='')
						{
						echo '<a href="'.get_post_meta($post->ID, 'field_inputtext__1', true).'">Android App URL</a>';
						echo "<br>";
						}			
						$iosurl=get_post_meta($post->ID, 'field_inputtext__3', true);
						if($iosurl!='')
						{
						echo '<a href="'.get_post_meta($post->ID, 'field_inputtext__3', true).'">iOS App URL</a>';
						echo "<br>";
						}
						$weburl=get_post_meta($post->ID, 'field_inputtext__2', true);
						if($weburl!='')
						{
						echo '<a href="'.get_post_meta($post->ID, 'field_inputtext__2', true).'">Web App URL</a>';
						echo "<br>";
						}			
						//check if type is story
					
						$output=get_post_meta($post->ID, 'field_checkbox__1', true);
						if (in_array("4", $output)) {
						    echo "Type: Story";
						}
						
						
			
											
										?>
					<?php comments_template( '', true ); ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>