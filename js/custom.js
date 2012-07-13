(function($){
	var $blog_grid_item = $('#blog-grid .blog-item'),
		$portfolio_grid = $('#portfolio-grid'),
		$portfolio_items = $portfolio_grid.find('.portfolio-item'),
		$et_mobile_nav_button = $('#mobile_nav'),
		$main_menu = $('ul.nav'),
		$featured = $('#featured'),
		et_container_width = $('#container').innerWidth(),
		$cloned_nav,
		et_slider,
		$et_ajax_portfolio_container;

	$(document).ready(function(){
		var $comment_form = jQuery('form#commentform');
	
		$main_menu.superfish({ 
			delay:       300,                            // one second delay on mouseout 
			animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
			speed:       'fast',                          // faster animation speed 
			autoArrows:  true,                           // disable generation of arrow mark-up 
			dropShadows: false                            // disable drop shadows 
		});
		
		$('.et_media, #left-area').fitVids();
		
		$portfolio_grid.before( '<div id="et_ajax_portfolio_container"><a id="et_close_ajax" href="#"></a></div>' );
		$et_ajax_portfolio_container = $('#et_ajax_portfolio_container');
		
		$('body').delegate( '#portfolio-grid a', 'click', function(){
			var $this_link = $(this),
				$portfolio_item = $this_link.closest('.portfolio-item'),
				project_id = $portfolio_item.data('project_id'),
				$new_element,
				ajax_height = 480;
				
			if ( $this_link.closest('.wp-pagenavi').length ) return;
				
			if ( ! $et_ajax_portfolio_container.hasClass('et_ajax_container_active') ) {
				if ( et_container_width == 728 ) ajax_height = 367;
				if ( et_container_width == 440 ) ajax_height = 352;
				if ( et_container_width == 280 ) ajax_height = 224;
				
				$et_ajax_portfolio_container.animate({'height':ajax_height},500, function(){
					$(this).css('height','auto').addClass('et_ajax_container_active');
				});
			}
				
			if ( $portfolio_item.hasClass('et_active_item') ) return false;
			
			if ( ! $( '#et_ajax_project_' + project_id ).length ){
				$.ajax({
					type: "POST",
					url: etsettings.ajaxurl,
					data:
					{
						action 			: 'et_show_ajax_project',
						et_project_id	: project_id
					},
					success: function(data){
						$new_element = $( '<div id="et_ajax_project_' + project_id + '" data-et_project_id="' + project_id + '" class="et_ajax_project clearfix">' + data + '</div>' ).hide();
						$et_ajax_portfolio_container.append( $new_element );
						et_apply_flexslider( $new_element );
						
						et_display_ajax_project( project_id );
					}
				});
			} else {
				et_display_ajax_project( project_id );
			}
			
			$('#portfolio-grid .portfolio-item').removeClass( 'et_active_item' );
			$portfolio_item.addClass('et_active_item').find('img').fadeTo(0,0.7);
			
			$('#portfolio-grid .portfolio-item').not('.et_active_item').find('img').fadeTo(500, 1);
			
			return false;
		} );
		
		$('body').delegate( '#et_close_ajax', 'click', function(){
			$et_ajax_portfolio_container.removeClass( 'et_ajax_container_active' ).animate( { height : 0 }, 500 );
			$et_ajax_portfolio_container.find( '.et_ajax_project:visible' ).animate( { opacity : 0 }, 500, function(){ $(this).css( 'display', 'none' ); } );
			$('#portfolio-grid .portfolio-item').removeClass( 'et_active_item' ).find('img').fadeTo(0,1);
			
			return false;
		} );
		
		$('body').delegate('#portfolio-grid .portfolio-item','hover', function( event ) {
			if( event.type === 'mouseenter' )  
				$(this).find('img').stop(true,true).fadeTo( 500, 0.7 );
			else
				if ( ! $(this).hasClass('et_active_item') ) $(this).find('img').stop(true,true).fadeTo( 500, 1 );
		} );
		
		function et_display_ajax_project( project_id ){
			if ( $et_ajax_portfolio_container.find('.et_ajax_project').filter(':visible').length ){
				$et_ajax_portfolio_container.find('.et_ajax_project').filter(':visible').hide();
			}
			$( '#et_ajax_project_' + project_id ).css( { 'display' : 'block', opacity : 0 } ).animate( { opacity : '1' }, 500 );
			$('html:not(:animated),body:not(:animated)').animate({ scrollTop: $et_ajax_portfolio_container.offset().top - 82 }, 500);
		}
		
		function et_apply_flexslider( element ){
			var $slider = element.find('.flexslider');
			
			$slider.flexslider( { slideshow: false, controlNav: false } );
			
			$slider.fitVids();
			
			$slider.find('iframe').each( function(){
				var this_src = $(this).attr('src') + '&amp;wmode=opaque';
				$(this).attr('src',this_src);
			} );
		}
		
		if ( $featured.length ){
			et_slider_settings = {
				slideshow: false,
				start: function(slider) {
					et_slider = slider;
				},
				controlNav: false
			}

			if ( $featured.hasClass('et_slider_auto') ) {
				var et_slider_autospeed_class_value = /et_slider_speed_(\d+)/g;
				
				et_slider_settings.slideshow = true;
				
				et_slider_autospeed = et_slider_autospeed_class_value.exec( $featured.attr('class') );
				
				et_slider_settings.slideshowSpeed = et_slider_autospeed[1];
			}
			
			if ( $featured.hasClass('et_slider_effect_slide') ){
				et_slider_settings.animation = 'slide';
			}
			
			et_slider_settings.pauseOnHover = true;
			
			$featured.flexslider( et_slider_settings );
		}
		
		$main_menu.clone().attr('id','mobile_menu').removeClass().appendTo( $et_mobile_nav_button );
		$cloned_nav = $et_mobile_nav_button.find('> ul');
		$cloned_nav.find('span.menu_slide').remove().end().find('span.main_text').removeClass();
		
		$et_mobile_nav_button.click( function(){
			if ( $(this).hasClass('closed') ){
				$(this).removeClass( 'closed' ).addClass( 'opened' );
				$cloned_nav.slideDown( 500 );
			} else {
				$(this).removeClass( 'opened' ).addClass( 'closed' );
				$cloned_nav.slideUp( 500 );
			}
			return false;
		} );
		
		$et_mobile_nav_button.find('a').click( function(event){
			event.stopPropagation();
		} );
		
		$comment_form.find('input, textarea').each(function(index,domEle){
			var $et_current_input = jQuery(domEle),
				$et_comment_label = $et_current_input.siblings('label'),
				et_comment_label_value = $et_current_input.siblings('label').text();
			if ( $et_comment_label.length ) {
				$et_comment_label.hide();
				if ( $et_current_input.siblings('span.required') ) { 
					et_comment_label_value += $et_current_input.siblings('span.required').text();
					$et_current_input.siblings('span.required').hide();
				}
				$et_current_input.val(et_comment_label_value);
			}
		}).bind('focus',function(){
			var et_label_text = jQuery(this).siblings('label').text();
			if ( jQuery(this).siblings('span.required').length ) et_label_text += jQuery(this).siblings('span.required').text();
			if (jQuery(this).val() === et_label_text) jQuery(this).val("");
		}).bind('blur',function(){
			var et_label_text = jQuery(this).siblings('label').text();
			if ( jQuery(this).siblings('span.required').length ) et_label_text += jQuery(this).siblings('span.required').text();
			if (jQuery(this).val() === "") jQuery(this).val( et_label_text );
		});

		$comment_form.find('input#submit').click(function(){
			if (jQuery("input#url").val() === jQuery("input#url").siblings('label').text()) jQuery("input#url").val("");
		});
		
		if ( $('ul.et_disable_top_tier').length ) $("ul.et_disable_top_tier > li > ul").prev('a').attr('href','#');
		
		if ( $('body.single-project .flexslider').length ){
			$('body.single-project .flexslider').flexslider( { slideshow: false, controlNav: false } ).find('iframe').each( function(){
				var this_src = $(this).attr('src') + '&amp;wmode=opaque';
				$(this).attr('src',this_src);
			} );
		}
		
		et_activate_quicksand();
		function et_activate_quicksand(){
			var $et_sort_list = $('#et_portfolio_sort_links'),
				$et_sort_link = $et_sort_list.find('a'),
				$portfolio_grid_clone = $portfolio_grid.clone();  

			$et_sort_link.click(function(){
				var et_filter,
					$items;
				
				$et_sort_list.find('li').removeClass('active');
				et_filter = $(this).data('categories_id').split(' ');  

				$(this).parent().addClass('active');  

				if ( et_filter == 'all') $items = $portfolio_grid_clone.find('>div');
				else $items = $portfolio_grid_clone.find('>div[data-project_category~=' + et_filter + ']');

				$portfolio_grid.quicksand( $items, { 
					duration: 800,
					easing: 'easeInOutQuad',
					adjustHeight: 'dynamic',
					enhancement : function(){
						var $active_ajax_project = $et_ajax_portfolio_container.find('.et_ajax_project:visible'),
							$active_project;
						
						if ( $active_ajax_project.length ){
							$active_project = $('#portfolio-grid .portfolio-item[data-project_id=' + $active_ajax_project.data('et_project_id') + ']');
							if ( $active_project.length && ! $active_project.hasClass('et_active_item') ) $active_project.addClass('et_active_item').find('img').fadeTo( 0, 0.7 );
						}
					}
				} );
				
				return false;
			});
		}
	});

	$(window).load( function(){
		et_columns_height_fix();
	} );
	
	function et_columns_height_fix(){
		var home_blog_post_min_height = 0;
	
		$blog_grid_item.css( 'minHeight', 0 );
		if ( et_container_width < 440 ) return;
	
		$blog_grid_item.each( function(){
			var this_height = $(this).height();
			
			if ( home_blog_post_min_height < this_height ) home_blog_post_min_height = this_height;
		} ).each( function(){
			$(this).css( 'minHeight', home_blog_post_min_height );
		} );
	}
	
	$(window).resize( function(){
		if ( et_container_width != $('#container').innerWidth() ){
			et_container_width = $('#container').innerWidth();
			et_columns_height_fix();
			if ( ! $featured.is(':visible') ) et_slider.pause();
			
			$('.et_shortcode_slide_active').each( function(){
				var $this_shortcode_slide = $(this);
				
				$this_shortcode_slide.parent().css( 'height', $this_shortcode_slide.innerHeight() );
			} );
		}
	} );
})(jQuery)