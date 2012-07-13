<?php 
	$thumb = '';
	$width = apply_filters( 'et_project_image_width', 240 );
	$height = apply_filters( 'et_project_image_height', 240 );
	$classtext = '';
	$titletext = get_the_title();
	$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Portfolio');
	$thumb = $thumbnail["thumb"]; 
?>
<?php
	$project_category = '';
	if ( is_page_template('template-page-projects.php') || is_home() ) {
		$terms = get_the_terms( $post->ID, 'project_category' );
		
		if ( $terms ){
			$i = 0;
			foreach ( $terms as $term ){
				$i++;
				$project_category .= ( 1 != $i ? ' ' : '' ) . 'project_cat_' . $term->term_taxonomy_id;
			}
			$project_category = 'data-id="id-' . get_the_ID() . '" data-project_category="' . $project_category . '" ';
		}
	}
?>
<div class="portfolio-item" <?php echo $project_category; ?>data-project_id="<?php echo esc_attr( get_the_ID() ); ?>">
	<a href="<?php the_permalink(); ?>">
		<?php
			if ( '' != $thumb ) {
				print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext);
			} else {
				$media = get_post_meta( $post->ID, '_et_used_images', true );
				if ( $media ){
					foreach( (array) $media as $et_media ){
						if ( is_numeric( $et_media ) ) {
							$et_fullimage_array = wp_get_attachment_image_src( $et_media, 'full' );
							if ( $et_fullimage_array ){
								$et_fullimage = $et_fullimage_array[0];
								echo '<img src="' . esc_url( et_new_thumb_resize( et_multisite_thumbnail($et_fullimage ), $width, $height, '', true ) ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" />';
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
</div>