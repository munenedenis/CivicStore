<?php 
	$featured_slider_class = '';
	if ( 'on' == et_get_option('flexible_slider_auto') ) $featured_slider_class .= ' et_slider_auto et_slider_speed_' . et_get_option('flexible_slider_autospeed');
	if ( 'slide' == et_get_option('flexible_slider_effect') ) $featured_slider_class .= ' et_slider_effect_slide';
?>
<div id="featured" class="flexslider<?php echo $featured_slider_class; ?>">
	<ul class="slides">
	<?php
		global $ids;
		
		$ids = array();

		$featured_cat = et_get_option('flexible_feat_cat');
		$featured_cat_term = get_term_by( 'name', $featured_cat, 'project_category' );

		$featured_num = et_get_option('flexible_featured_num');
		
		if (et_get_option('flexible_use_pages','false') == 'false') {
			if ( 'on' == et_get_option('flexible_use_posts','false') )
				$featured_query = new WP_Query( array(
					'showposts' => $featured_num,
					'cat' => get_catId( et_get_option('flexible_feat_posts_cat') )
				) );
			else				
				$featured_query = new WP_Query( array(
					'post_type' => 'project',
					'showposts' => $featured_num,
					'tax_query' => array(
						array(
							'taxonomy' => 'project_category',
							'field' => 'id',
							'terms' => (array) $featured_cat_term->term_id,
							'operator' => 'IN'
						)
					)
				) );
		} else { 
			global $pages_number;
			
			if (et_get_option('flexible_feat_pages') <> '') $featured_num = count(et_get_option('flexible_feat_pages'));
			else $featured_num = $pages_number;
			
			$featured_query = new WP_Query(array
							('post_type' => 'page',
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'post__in' => (array) et_get_option('flexible_feat_pages'),
							'showposts' => (int) $featured_num)
						);
		}
		
		while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
			<li class="slide">					
				<a href="<?php echo esc_url( get_permalink() ); ?>">							
					<?php
						$width = apply_filters( 'slider_image_width', 960 );
						$height = apply_filters( 'slider_image_height', 360 );
						$title = get_the_title();
						$thumbnail = get_thumbnail($width,$height,'',$title,$title,false,'Featured');
						$thumb = $thumbnail["thumb"];
						
						if ( '' != $thumb ) {
							print_thumbnail($thumb, $thumbnail["use_timthumb"], $title, $width, $height, '');
						} else {
							$media = get_post_meta( $post->ID, '_et_used_images', true );
							if ( $media ){
								foreach( (array) $media as $et_media ){
									if ( is_numeric( $et_media ) ) {
										$et_fullimage_array = wp_get_attachment_image_src( $et_media, 'full' );
										if ( $et_fullimage_array ){
											$et_fullimage = $et_fullimage_array[0];
											echo '<img src="' . esc_url( et_new_thumb_resize( et_multisite_thumbnail($et_fullimage ), $width, $height, '', true ) ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" alt="' . esc_attr( $title ) . '" />';
										}
										break;
									} else {
										continue;
									}
								}
							}
						}
					?>
				</a>
			</li>
	<?php
			$ids[]= $post->ID;
		endwhile; wp_reset_postdata();
	?>
	</ul>
</div> <!-- end #featured -->