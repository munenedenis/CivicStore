<?php 
/*
Template Name: Search Page
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
				
				<div id="et-search" class="responsive">
					<div id="et-search-inner" class="clearfix">
						<p id="et-search-title"><span><?php esc_html_e('search this website','Flexible'); ?></span></p>
						<form action="<?php echo home_url(); ?>" method="get" id="et_search_form">
							<div id="et-search-left">
								<p id="et-search-word"><input type="text" id="et-searchinput" name="s" value="<?php esc_attr_e('search this site...','Flexible'); ?>" /></p>
																
								<p id="et_choose_posts"><label><input type="checkbox" id="et-inc-posts" name="et-inc-posts" /> <?php esc_html_e('Posts','Flexible'); ?></label></p>
								<p id="et_choose_pages"><label><input type="checkbox" id="et-inc-pages" name="et-inc-pages" /> <?php esc_html_e('Pages','Flexible'); ?></label></p>
								<p id="et_choose_date">
									<select id="et-month-choice" name="et-month-choice">
										<option value="no-choice"><?php esc_html_e('Select a month','Flexible'); ?></option>
										<?php 
											global $wpdb, $wp_locale;
											
											$selected = '';
											$query = "SELECT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC";
											
											$arcresults = $wpdb->get_results($query);
																												
											foreach ( (array) $arcresults as $arcresult ) {
												if ( isset($_POST['et-month-choice']) && ( $_POST['et-month-choice'] == ($arcresult->year . $arcresult->month) ) ) {
													$selected = ' selected="selected"';
												}
												echo "<option value='{$arcresult->year}{$arcresult->month}'{$selected}>{$wp_locale->get_month($arcresult->month)}" . ", {$arcresult->year}</option>";
												if ( $selected <> '' ) $selected = '';
											}
										?>
									</select>
								</p>
							
								<p id="et_choose_cat"><?php wp_dropdown_categories('show_option_all=Choose a Category&show_count=1&hierarchical=1&id=et-cat&name=et-cat'); ?></p>
							</div> <!-- #et-search-left -->
							
							<div id="et-search-right">
								<input type="hidden" name="et_searchform_submit" value="et_search_proccess" />
								<input class="et_search_submit" type="submit" value="<?php esc_attr_e('Submit','Flexible'); ?>" id="et_search_submit" />
							</div> <!-- #et-search-right -->
						</form>
					</div> <!-- end #et-search-inner -->
				</div> <!-- end #et-search -->
				
				<div class="clear"></div>
					
				<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Flexible').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php edit_post_link(esc_attr__('Edit this page','Flexible')); ?>
			</div> 	<!-- end .post-content -->
		</article> <!-- end .entry -->
	</div> <!-- end #left_area -->

	<?php if ( ! $fullwidth ) get_sidebar(); ?>
</div> 	<!-- end #content-area -->

<?php get_footer(); ?>