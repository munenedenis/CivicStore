<?php 
add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		global $themename, $shortname, $et_store_options_in_one_row;
		$themename = 'Flexible';
		$shortname = 'flexible';
		$et_store_options_in_one_row = true;
	
		require_once(TEMPLATEPATH . '/epanel/custom_functions.php'); 

		require_once(TEMPLATEPATH . '/includes/functions/comments.php'); 

		require_once(TEMPLATEPATH . '/includes/functions/sidebars.php'); 

		load_theme_textdomain('Flexible',get_template_directory().'/lang');

		require_once(TEMPLATEPATH . '/epanel/options_flexible.php');

		require_once(TEMPLATEPATH . '/epanel/core_functions.php'); 

		require_once(TEMPLATEPATH . '/epanel/post_thumbnails_flexible.php');
		
		include(TEMPLATEPATH . '/includes/widgets.php');
		
		require_once(TEMPLATEPATH . '/includes/additional_functions.php');
		
		add_action( 'init', 'et_register_main_menus' );
		
		add_action( 'wp_enqueue_scripts', 'et_load_flexible_scripts' );
		
		add_action( 'wp_enqueue_scripts', 'et_add_google_fonts' );
		
		add_action( 'wp_head', 'et_add_viewport_meta' );
		
		add_action( 'et_header_menu', 'et_add_mobile_navigation' );
		
		add_action('init', 'et_portfolio_posttype_register');
		
		add_action( 'init', 'et_create_portfolio_taxonomies', 0 );
		
		add_action( 'pre_get_posts', 'et_home_posts_query' );
		
		add_action('et_header_top','et_flexible_control_panel');
		
		add_action( 'wp_head', 'et_set_bg_properties' );
		
		add_action( 'wp_head', 'et_set_font_properties' );
		
		add_action( 'wp_ajax_nopriv_et_show_ajax_project', 'et_show_ajax_project' );
		
		add_action( 'wp_ajax_et_show_ajax_project', 'et_show_ajax_project' );
		
		add_filter( 'wp_page_menu_args', 'et_add_home_link' );
		
		add_filter( 'et_get_additional_color_scheme', 'et_remove_additional_stylesheet' );
	}
}

function et_register_main_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu', 'Flexible' )
		)
	);
}

function et_add_home_link( $args ) {
	// add Home link to the custom menu WP-Admin page
	$args['show_home'] = true;
	return $args;
}

function et_load_flexible_scripts(){
	if ( !is_admin() ){
		$template_dir = get_template_directory_uri();
		
		wp_enqueue_script('superfish', $template_dir . '/js/superfish.js', array('jquery'), '1.0', true);
		wp_enqueue_script('easing', $template_dir . '/js/jquery.easing.1.3.js', array('jquery'), '1.0', true);
		wp_enqueue_script('flexslider', $template_dir . '/js/jquery.flexslider-min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('fitvids', $template_dir . '/js/jquery.fitvids.js', array('jquery'), '1.0', true);
		wp_enqueue_script('quicksand', $template_dir . '/js/jquery.quicksand.js', array('jquery'), '1.0', true);
		wp_enqueue_script('custom_script', $template_dir . '/js/custom.js', array('jquery'), '1.0', true);
		wp_localize_script( 'custom_script', 'etsettings', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		$admin_access = apply_filters( 'et_showcontrol_panel', current_user_can('switch_themes') );
		if ( $admin_access && et_get_option('flexible_show_control_panel') == 'on' ) {
			wp_enqueue_script('et_colorpicker', $template_dir . '/epanel/js/colorpicker.js', array('jquery'), '1.0', true);
			wp_enqueue_script('et_eye', $template_dir . '/epanel/js/eye.js', array('jquery'), '1.0', true);
			wp_enqueue_script('et_cookie', $template_dir . '/js/jquery.cookie.js', array('jquery'), '1.0', true);
			wp_enqueue_script('et_control_panel', $template_dir . '/js/et_control_panel.js', array('jquery'), '1.0', true);
			wp_localize_script( 'et_control_panel', 'et_control_panel', apply_filters( 'et_control_panel_settings', array( 'theme_folder' => $template_dir ) ) );
		}
	}
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
}

function et_add_google_fonts(){
	wp_enqueue_style( 'google_font_open_sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,700&amp;subset=latin,latin-ext,cyrillic' );
}

function et_add_viewport_meta(){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />';
}

function et_add_mobile_navigation(){
	echo '<a href="#" id="mobile_nav" class="closed">' . '<span></span>' . esc_html__( 'Navigation Menu', 'Flexible' ) . '</a>';
}

function et_remove_additional_stylesheet( $stylesheet ){
	global $default_colorscheme;
	return $default_colorscheme;
}

function et_create_portfolio_taxonomies(){
	$labels = array(
		'name' => _x( 'Categories', 'taxonomy general name', 'Flexible' ),
		'singular_name' => _x( 'Category', 'taxonomy singular name', 'Flexible' ),
		'search_items' =>  __( 'Search Categories', 'Flexible' ),
		'all_items' => __( 'All Categories', 'Flexible' ),
		'parent_item' => __( 'Parent Category', 'Flexible' ),
		'parent_item_colon' => __( 'Parent Category:', 'Flexible' ),
		'edit_item' => __( 'Edit Category', 'Flexible' ), 
		'update_item' => __( 'Update Category', 'Flexible' ),
		'add_new_item' => __( 'Add New Category', 'Flexible' ),
		'new_item_name' => __( 'New Category Name', 'Flexible' ),
		'menu_name' => __( 'Category', 'Flexible' )
	);

	register_taxonomy('project_category',array('project'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => apply_filters( 'et_portfolio_category_rewrite_args', array( 'slug' => 'portfolio' ) )
	));
}

function et_portfolio_posttype_register() {
	$labels = array(
		'name' => _x('Projects', 'post type general name','Flexible'),
		'singular_name' => _x('Project', 'post type singular name','Flexible'),
		'add_new' => _x('Add New', 'project item','Flexible'),
		'add_new_item' => __('Add New Project','Flexible'),
		'edit_item' => __('Edit Project','Flexible'),
		'new_item' => __('New Project','Flexible'),
		'all_items' => __('All Projects','Flexible'),
		'view_item' => __('View Project','Flexible'),
		'search_items' => __('Search Projects','Flexible'),
		'not_found' =>  __('Nothing found','Flexible'),
		'not_found_in_trash' => __('Nothing found in Trash','Flexible'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => apply_filters( 'et_portfolio_posttype_rewrite_args', array( 'slug' => 'project', 'with_front' => false ) ),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail','excerpt','comments','revisions','custom-fields')
	);
 
	register_post_type( 'project' , $args );
}

function et_home_posts_query( $query = '' ) {
	global $paged;
	if ( ! is_home() || ! is_a( $query, 'WP_Query' ) || ! $query->is_main_query() ) return;
		
	$query->set( 'posts_per_page', et_get_option( 'flexible_homepage_posts', '6' ) );
	
	$exclude_categories = et_get_option( 'flexible_exlcats_recent', false );
	if ( $exclude_categories ) $query->set( 'category__not_in', $exclude_categories );
}

if ( ! function_exists( 'et_list_pings' ) ){
	function et_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php }
}

if ( ! function_exists( 'et_get_the_author_posts_link' ) ){
	function et_get_the_author_posts_link(){
		global $authordata, $themename;
		
		$link = sprintf(
			'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
			get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
			esc_attr( sprintf( __( 'Posts by %s', $themename ), get_the_author() ) ),
			get_the_author()
		);
		return apply_filters( 'the_author_posts_link', $link );
	}
}

if ( ! function_exists( 'et_get_comments_popup_link' ) ){
	function et_get_comments_popup_link( $zero = false, $one = false, $more = false ){
		global $themename;
		
		$id = get_the_ID();
		$number = get_comments_number( $id );

		if ( 0 == $number && !comments_open() && !pings_open() ) return;
		
		if ( $number > 1 )
			$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', $themename) : $more);
		elseif ( $number == 0 )
			$output = ( false === $zero ) ? __('No Comments',$themename) : $zero;
		else // must be one
			$output = ( false === $one ) ? __('1 Comment', $themename) : $one;
			
		return '<span class="comments-number">' . '<a href="' . esc_url( get_permalink() . '#respond' ) . '">' . apply_filters('comments_number', $output, $number) . '</a>' . '</span>';
	}
}

if ( ! function_exists( 'et_postinfo_meta' ) ){
	function et_postinfo_meta( $postinfo, $date_format, $comment_zero, $comment_one, $comment_more ){
		global $themename;
		
		$postinfo_meta = '';
		
		if ( in_array( 'author', $postinfo ) ){
			$postinfo_meta .= ' ' . esc_html__('by',$themename) . ' ' . et_get_the_author_posts_link();
		}
			
		if ( in_array( 'date', $postinfo ) )
			$postinfo_meta .= ' ' . esc_html__('on',$themename) . ' ' . get_the_time( $date_format );
			
		if ( in_array( 'categories', $postinfo ) )
			$postinfo_meta .= ' ' . esc_html__('in',$themename) . ' ' . get_the_category_list(', ');
			
		if ( in_array( 'comments', $postinfo ) )
			$postinfo_meta .= ' | ' . et_get_comments_popup_link( $comment_zero, $comment_one, $comment_more );
			
		if ( '' != $postinfo_meta ) $postinfo_meta = __('Posted',$themename) . ' ' . $postinfo_meta;	
			
		echo $postinfo_meta;
	}
}

function et_show_ajax_project(){
	global $wp_embed;
	
	$project_id = (int) $_POST['et_project_id'];
	
	$portfolio_args = array(
		'post_type' => 'project',
		'p' => $project_id
	);
	$portfolio_query = new WP_Query( apply_filters( 'et_ajax_portfolio_args', $portfolio_args ) );
	while ( $portfolio_query->have_posts() ) : $portfolio_query->the_post();
		global $post;
		$width = apply_filters( 'et_ajax_media_width', 600 );
		$height = apply_filters( 'et_ajax_media_height', 480 );
		
		$media = get_post_meta( $post->ID, '_et_used_images', true );
		echo '<div class="et_media">';
			if ( $media ){
				echo '<div class="flexslider"><ul class="slides">';
				foreach( (array) $media as $et_media ){
					echo '<li class="slide">';
					
					if ( is_numeric( $et_media ) ) {
						$et_fullimage_array = wp_get_attachment_image_src( $et_media, 'full' );
						if ( $et_fullimage_array ){
							$et_fullimage = $et_fullimage_array[0];
							echo '<img src="' . esc_url( et_new_thumb_resize( et_multisite_thumbnail($et_fullimage ), $width, $height, '', true ) ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" />';
						}
					} else {
						$video_embed = $wp_embed->shortcode( '', $et_media );

						$video_embed = preg_replace('/<embed /','<embed wmode="transparent" ',$video_embed);
						$video_embed = preg_replace('/<\/object>/','<param name="wmode" value="transparent" /></object>',$video_embed); 
						$video_embed = preg_replace("/height=\"[0-9]*\"/", "height={$height}", $video_embed);
						$video_embed = preg_replace("/width=\"[0-9]*\"/", "width={$width}", $video_embed);
						
						echo $video_embed;								
					}
					echo '</li>';
				}
				echo '</ul></div>';
			} else {
				$thumb = '';
				$classtext = '';
				$titletext = get_the_title();
				$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Ajaximage');
				$thumb = $thumbnail["thumb"];
				echo '<a href="'. esc_url( get_permalink() ) . '">';
					print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext);
				echo '</a>';
			}
		echo '</div> <!-- end .et_media -->';
		
		echo 	'<div class="et_media_description">' . 
					'<h2 class="title">' . '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . '</h2>' .
					truncate_post( 560, false );

		echo '</div> <!-- end .et_media_description -->';
		
		echo '<a class="more" href="' . get_permalink() . '">' . __( 'More info &raquo;', 'Flexible' ) . '</a>';
	endwhile; 
	wp_reset_postdata();
	
	die();
}


function et_flexible_control_panel(){
	global $themename;
	
	$admin_access = apply_filters( 'et_showcontrol_panel', current_user_can('switch_themes') );
	if ( !$admin_access ) return;
	if ( et_get_option('flexible_show_control_panel') <> 'on' ) return;
	global $et_bg_texture_urls, $et_google_fonts; ?>
	<div id="et-control-panel">
		<div id="control-panel-main">
			<a id="et-control-close" href="#"></a>
			<div id="et-control-inner">
				<h3 class="control_title"><?php esc_html_e('Example Colors',$themename); ?></h3>
				<a href="#" class="et-control-colorpicker" id="et-control-background"></a>
				
				<div class="clear"></div>
				
				<?php 
					$sample_colors = array( '6a8e94', '8da49c', 'b0b083', '859a7c', 'c6bea6', 'b08383', 'a4869d', 'f5f5f5', '4e4e4e', '556f6a', '6f5555', '6f6755' );
					for ( $i=1; $i<=12; $i++ ) { ?>
						<a class="et-sample-setting" id="et-sample-color<?php echo $i; ?>" href="#" rel="<?php echo esc_attr($sample_colors[$i-1]); ?>" title="#<?php echo esc_attr($sample_colors[$i-1]); ?>"><span class="et-sample-overlay"></span></a>
				<?php } ?>
				<p><?php esc_html_e('or define your own in ePanel',$themename); ?></p>
				
				<h3 class="control_title"><?php esc_html_e('Texture Overlays',$themename); ?></h3>
				<div class="clear"></div>
				
				<?php 
					$sample_textures = $et_bg_texture_urls;
					for ( $i=1; $i<=count($et_bg_texture_urls); $i++ ) { ?>
						<a title="<?php echo esc_attr($sample_textures[$i-1]); ?>" class="et-sample-setting et-texture" id="et-sample-texture<?php echo $i; ?>" href="#" rel="bg<?php echo $i+1; ?>"><span class="et-sample-overlay"></span></a>
				<?php } ?>
				
				<p><?php esc_html_e('or define your own in ePanel',$themename); ?></p>
				
				<?php 
					$google_fonts = $et_google_fonts;
					$font_setting = 'Open+Sans';
					$body_font_setting = 'Open+Sans';
					if ( isset( $_COOKIE['et_flexible_header_font'] ) ) $font_setting = $_COOKIE['et_flexible_header_font'];
					if ( isset( $_COOKIE['et_flexible_body_font'] ) ) $body_font_setting = $_COOKIE['et_flexible_body_font'];
				?>
				
				<h3 class="control_title"><?php esc_html_e('Fonts',$themename); ?></h3>
				<div class="clear"></div>
				
				<label for="et_control_header_font"><?php esc_html_e('Header',$themename); ?>
					<select name="et_control_header_font" id="et_control_header_font">
						<?php foreach( $google_fonts as $google_font ) { ?>
							<?php $encoded_value = urlencode($google_font); ?>
							<option value="<?php echo esc_attr($encoded_value); ?>" <?php selected( $font_setting, $encoded_value ); ?>><?php echo esc_html($google_font); ?></option>
						<?php } ?>
					</select>
				</label>
				<a href="#" class="et-control-colorpicker et-font-control" id="et-control-headerfont_bg"></a>
				<div class="clear"></div>
				
				<label for="et_control_body_font"><?php esc_html_e('Body',$themename); ?>
					<select name="et_control_body_font" id="et_control_body_font">
						<?php foreach( $google_fonts as $google_font ) { ?>
							<?php $encoded_value = urlencode($google_font); ?>
							<option value="<?php echo esc_attr($encoded_value); ?>" <?php selected( $body_font_setting, $encoded_value ); ?>><?php echo esc_html($google_font); ?></option>
						<?php } ?>
					</select>
				</label>
				<a href="#" class="et-control-colorpicker et-font-control" id="et-control-bodyfont_bg"></a>
				<div class="clear"></div>
				
			</div> <!-- end #et-control-inner -->
		</div> <!-- end #control-panel-main -->
	</div> <!-- end #et-control-panel -->
<?php
}

function et_set_bg_properties(){
	global $et_bg_texture_urls;
	
	$bgcolor = '';
	$bgcolor = ( isset( $_COOKIE['et_flexible_bgcolor'] ) && et_get_option('flexible_show_control_panel') == 'on' ) ? $_COOKIE['et_flexible_bgcolor'] : et_get_option('flexible_bgcolor');
	
	$bgtexture_url = '';
	$bgimage_url = '';
	if ( et_get_option('flexible_bgimage') == '' ) {
		if ( isset( $_COOKIE['et_flexible_texture_url'] ) && et_get_option('flexible_show_control_panel') == 'on' ) $bgtexture_url =  $_COOKIE['et_flexible_texture_url'];
		else {
			$bgtexture_url = et_get_option('flexible_bgtexture_url');
			if ( $bgtexture_url == 'Default' ) $bgtexture_url = '';
			else $bgtexture_url = get_bloginfo('template_directory') . '/images/control_panel/body-bg' . ( array_search( $bgtexture_url, $et_bg_texture_urls )+2 ) . '.png';
		}
	} else {
		$bgimage_url = et_get_option('flexible_bgimage');
	}
	
	$style = '';
	$style .= '<style type="text/css">';
	if ( $bgcolor <> '' ) $style .= 'body { background-color: #' . esc_attr($bgcolor) . '; }';
	if ( $bgtexture_url <> '' ) $style .= 'body { background-image: url(' . esc_attr($bgtexture_url) . '); }';
	if ( $bgimage_url <> '' ) $style .= 'body { background-image: url(' . esc_attr($bgimage_url) . '); background-position: top center; background-repeat: no-repeat; }';
	$style .= '</style>';
	
	if ( $bgcolor <> '' || $bgtexture_url <> '' || $bgimage_url <> '' ) echo $style;
}

function et_set_font_properties(){
	$font_style = '';
	$font_color = '';
	$font_family = '';
	$font_color_string = '';
	
	if ( isset( $_COOKIE['et_flexible_header_font'] ) && et_get_option('flexible_show_control_panel') == 'on' ) $et_header_font =  $_COOKIE['et_flexible_header_font'];
	else {
		$et_header_font = et_get_option('flexible_header_font');
	}
	
	if ( $et_header_font == 'Open+Sans' || $et_header_font == 'Open Sans' ) $et_header_font = '';
	
	if ( isset( $_COOKIE['et_flexible_header_font_color'] ) && et_get_option('flexible_show_control_panel') == 'on' ) 	
		$et_header_font_color =  $_COOKIE['et_flexible_header_font_color'];
	else 
		$et_header_font_color = et_get_option('flexible_header_font_color');
	
	if ( $et_header_font <> '' || $et_header_font_color <> '' ) {
		$et_header_font_id = strtolower( str_replace( '+', '_', $et_header_font ) );
		$et_header_font_id = str_replace( ' ', '_', $et_header_font_id );
		
		if ( $et_header_font <> '' ) { 
			$font_style .= "<link id='" . esc_attr($et_header_font_id) . "' href='" . esc_url( "http://fonts.googleapis.com/css?family=" . $et_header_font ) . "' rel='stylesheet' type='text/css' />";
			$font_family = "font-family: '" . esc_html(str_replace( '+', ' ', $et_header_font )) . "', Arial, sans-serif !important; ";
		}
		
		if ( $et_header_font_color <> '' ) {
			$font_color_string = "color: #" . esc_html($et_header_font_color) . "; ";
		}
		
		$font_style .= "<style type='text/css'>h1, h2, h3, h4, h5, h6 { ". $font_family .  " }</style>";
		$font_style .= "<style type='text/css'>h1, h2, h3, h4, h5, h6 { ". esc_html($font_color_string) .  " }
		</style>";
		
		echo $font_style;
	}
	
	$font_style = '';
	$font_color = '';
	$font_family = '';
	$font_color_string = '';
	
	if ( isset( $_COOKIE['et_flexible_body_font'] ) && et_get_option('flexible_show_control_panel') == 'on' ) $et_body_font =  $_COOKIE['et_flexible_body_font'];
	else {
		$et_body_font = et_get_option('flexible_body_font');
	}
	
	if ( $et_body_font == 'Open+Sans' ) $et_body_font = '';
	
	if ( isset( $_COOKIE['et_flexible_body_font_color'] ) && et_get_option('flexible_show_control_panel') == 'on' ) 	
		$et_body_font_color =  $_COOKIE['et_flexible_body_font_color'];
	else 
		$et_body_font_color = et_get_option('flexible_body_font_color');
	
	if ( $et_body_font <> '' || $et_body_font_color <> '' ) {
		$et_body_font_id = strtolower( str_replace( '+', '_', $et_body_font ) );
		$et_body_font_id = str_replace( ' ', '_', $et_body_font_id );
		
		if ( $et_body_font <> '' ) { 
			$font_style .= "<link id='" . esc_attr($et_body_font_id) . "' href='" . esc_url( "http://fonts.googleapis.com/css?family=" . $et_body_font ) . "' rel='stylesheet' type='text/css' />";
			$font_family = "font-family: '" . esc_html(str_replace( '+', ' ', $et_body_font )) . "', Arial, sans-serif !important; ";
		}
		
		if ( $et_body_font_color <> '' ) {
			$font_color_string = "color: #" . esc_html($et_body_font_color) . "; ";
		}
		
		$font_style .= "<style type='text/css'>body { ". $font_family .  " }</style>";
		$font_style .= "<style type='text/css'>body { ". esc_html($font_color_string) .  " }</style>";
		
		echo $font_style;
	}
} ?>