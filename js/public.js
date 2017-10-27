jQuery('body').on('click','.cpb-more-button a', function( event ){ 
	event.preventDefault();
	
	var but = jQuery( this );
	
	var wrap = jQuery( this ).closest('.cpb-more-content');
	
	if ( wrap.hasClass('active') ){
		
		wrap.find('.cpb-more-content-continue').slideUp('fast');
		
		wrap.removeClass('active');
		
		but.find('span').html('Continue Reading');
		
	} else {
		
		wrap.find('.cpb-more-content-continue').slideDown('fast');
		
		wrap.addClass('active');
		
		but.find('span').html('Close X');
	
	} // end if
	});

var cahnrs_pagebuilder = {
	
	init: function(){
		
		cahnrs_pagebuilder.lightbox.init();	
		cahnrs_pagebuilder.layout.bind_events();
		cahnrs_pagebuilder.layout.column_css();
		cahnrs_pagebuilder.faq.init();
		cahnrs_pagebuilder.lists.init();
		cahnrs_pagebuilder.social.bind_events();
		cahnrs_pagebuilder.az_index.bind_events();
		
	}, // end init
	
	az_index:{
		
		bind_events:function(){
			
			jQuery('body').on('click','.cpb-az-index-wrap .cpb-az-index-nav .cpb-az-index-nav-item.has-items', function(){
				
				var c = jQuery(this);
				
				c.addClass('active').siblings().removeClass('active');
				
				var wrap = c.closest('.cpb-az-index-wrap');
				
				var next = wrap.find('.cpb-az-index-alpha-set').eq( c.index() );
				
				next.addClass('active').siblings().removeClass('active');
				
			});
			
		} // End bind_events
		
	},
	
	social:{
		
		bind_events:function(){
			
			jQuery('body').on('click','.cpb-social-icon',function(){
				
				var c = jQuery(this);
				
				c.addClass('active').siblings().removeClass('active');
				
				var wrap = c.closest('.cpb-social-item');
				
				var next = wrap.find('.cpb-social-content').eq( c.index() );
				
				next.addClass('active').siblings().removeClass('active');
				
			});
			
		} // End bind_events
		
	}, // End socail
	
	lists:{
		
		init:function(){
			
			cahnrs_pagebuilder.lists.bind_events();
			
			jQuery( '.list-style-drop-down li' ).each( function(){
				if ( jQuery( this ).children('ul').length ){
					
					jQuery( this ).addClass('is-drop-down');
					
				} // End if
			});
			
		},
		
		bind_events:function(){
			
			jQuery('body').on('click', 'li.is-drop-down', function(){ cahnrs_pagebuilder.lists.accordion( jQuery( this ) ); } );
			
		},
		
		accordion:function( list_item ){
			
			var submenu = list_item.children('ul');
			
			if ( submenu.length ){
				
				var item_class = 'open';
				
				if( submenu.is(':visible') ) {
					
					item_class = 'closed';
					
				} // End if
				
				submenu.closest('li').removeClass( 'closed open' );
				
				submenu.closest('li').addClass( item_class );
				
				submenu.slideToggle('fast');
				
			} // End if
			
		},
		
	},
	
	layout:{
		
		bind_events:function(){
			jQuery(window).resize( function(){ cahnrs_pagebuilder.layout.column_css(); });
		},
		
		column_css:function(){
			
			jQuery( '.row > .column, .cpb-row .cpb-column').each( function(){
				var c = jQuery( this );
				
				if ( ! c.hasClass('no-size') ) {
					c.removeClass('small medium large medium-small column-size-100 column-size-200 column-size-300 column-size-400 column-size-500 column-size-600 column-size-700 column-size-800 column-size-900 column-size-1000 column-size-1100 column-size-1200 column-size-1300');
					var w = c.width();
					var nw = ( Math.floor( (c.width()/100) ) * 100 );
					var cls = 'large';
					if ( w < 450 ){
						cls = 'small';
					} else if( w >= 450 && w < 600 ){
						cls = 'medium-small';
					} else if ( w >= 600 && w < 900 ){
						cls = 'medium';
					} // end if
					
					cls += ' column-size-' + nw;
					
					c.addClass( cls );
				
				} // End if
			});
			
		},
		
	},
	
	lightbox: {
		
		bg: false,
		
		frame_wrap:false,
		
		frame: false,
		
		iframe: false,
		
		init: function(){
			
			if ( jQuery('.as-lightbox').length > 0 ){
				
				cahnrs_pagebuilder.lightbox.add_lb();
				
				cahnrs_pagebuilder.lightbox.set_lb();
				
				jQuery('body').on('click' , '.as-lightbox a' , function( event ){  cahnrs_pagebuilder.lightbox.show_lb( jQuery( this ) , event ); })
				
				jQuery('body').on('click' , '.close-lb' , function( event ){  cahnrs_pagebuilder.lightbox.close_lb(); })  
				
				cahnrs_pagebuilder.lightbox.iframe.load( function(){ cahnrs_pagebuilder.lightbox.set_content_height() });
			
			} // end if
			
		}, // end init
		
		set_lb: function(){
			
			cahnrs_pagebuilder.lightbox.bg = jQuery('#pb-lb-bg');
				
			cahnrs_pagebuilder.lightbox.frame_wrap = jQuery('#pb-lb-frame-wrap');
			
			cahnrs_pagebuilder.lightbox.frame = jQuery('#pb-lb-frame');
			
			cahnrs_pagebuilder.lightbox.iframe = jQuery('#pb-lb-frame iframe');
			
		}, // end set_lb
		
		show_lb: function( ic , event ){
			
			event.preventDefault();
			
			jQuery('#pb-lb-bg').fadeIn('fast');
			
			cahnrs_pagebuilder.lightbox.set_height();
			
			var par = ic.closest('.cpb-item');
			
			var url = par.data('requesturl');
			
			cahnrs_pagebuilder.lightbox.iframe.attr( 'src', url );
			
			//cahnrs_pagebuilder.lightbox.ajax( cahnrs_pagebuilder.lightbox.serial( par ) , par.data('requesturl') );
			
		}, // end show_lb
		
		close_lb: function(){
			
			cahnrs_pagebuilder.lightbox.frame_wrap.css('top',-99999 );
			
			cahnrs_pagebuilder.lightbox.bg.fadeOut('fast');
			
			cahnrs_pagebuilder.lightbox.iframe.attr('src','about:blank');
			
		},
		
		
		add_lb: function(){
			
			var html = '<div id="pb-lb-bg" class="close-lb"></div>';
			
			html += '<div id="pb-lb-frame-wrap" class="close-lb">';
			
				html += '<div id="pb-lb-frame">';
				
					html += '<a href="#" class="close-lb">x</a>';
				
					html += '<iframe src="about:blank"></iframe>';
				
				html += '</div>';
			
			html += '</div>';
			
			jQuery('body').append( html );
			
		}, // end add_lb
		
		set_height: function(){
			
			win_h = jQuery(window).scrollTop();
			
			par_off = cahnrs_pagebuilder.lightbox.frame_wrap.offsetParent().offset().top;
			
			frm_h = ( win_h - par_off ) + 60;
			
			cahnrs_pagebuilder.lightbox.frame_wrap.css('top', frm_h ); 
			
		}, // end form_set_height
		
		set_content_height: function(){
			
			var h = cahnrs_pagebuilder.lightbox.iframe.contents().find('body').height() + 30;
			
			cahnrs_pagebuilder.lightbox.iframe.height( h );
			
		} // end set_content_height
		
	}, // end lightbox
	
	faq:{
		
		init:function(){
			
			cahnrs_pagebuilder.faq.events();
			
		}, // end init
		
		events:function(){
			
			jQuery('body').on(
				'click',
				'.cpb-faq dt',
				function(){
					cahnrs_pagebuilder.faq.toggle( jQuery( this ).closest( '.cpb-faq' ) );
				}
			);
			
		}, // end events
		
		toggle:function( faq ){
			
			var sibs = faq.siblings('.cpb-faq');
			
			sibs.find('dt').removeClass('active');
			
			sibs.find('dd').slideUp('fast');
			
			if ( faq.find('dt').hasClass('active') ){
				
				faq.find('dt').removeClass('active');
			
				faq.find('dd').slideUp('fast');
				
			} else {
				
				faq.find('dt').addClass('active');
			
				faq.find('dd').slideDown('fast');
				
			} // end if
			
		}, // end toggle
		
	},
	
	
}
cahnrs_pagebuilder.init();

/*********************************************************
 * Start CPB Slideshow JS. This is used on all slideshows with the
 * cbp-slideshow-feature class
**********************************************************/
if ( typeof cpb_slideshow === 'undefined' ) {
	
	var cpb_slideshow = function( slideshow ){
		
		this.show = slideshow;
		
		var self = this;
		
		this.init = function(){
			
			self.bind_events();
			
		} // End init
		
		this.bind_events = function(){
			
			self.show.on( 'click', '.cpb-slide-nav', function(){ self.do_slide_nav( jQuery( this ) ) });
			
			self.show.on( 'click', '.cpb-slide-thumb', function(){ self.do_slide_thumb( jQuery( this ) ) });
			
		} // End bind_events
		
		this.do_slide_thumb = function( item_clicked ) {
			
			var active_slide = self.get_active_side();
			
			var next_index = item_clicked.index();
			
			if ( active_slide.index() == next_index ) return;
			
			var next_slide = self.show.find('.cpb-slide-item').eq( next_index );
			
			var dir = ( active_slide.index() > next_index )? -1:1;
			
			if ( next_slide.length ){
					
				self.do_slide( active_slide, next_slide, dir );
				
			} // end if
			
		} // End do_slide_thumb
		
		this.do_slide_nav = function( item_clicked ){
			
			dir = ( item_clicked.hasClass('slide-prev') ) ? -1:1;
			
			var active_slide = self.get_active_side();
			
			var next_index = self.get_next_slide_index( active_slide, dir );
			
			if ( next_index !== false ){
				
				var next_slide = self.show.find('.cpb-slide-item').eq( next_index );
				
				if ( next_slide.length ){
					
					self.do_slide( active_slide, next_slide, dir );
					
				} // end if
				
			} // End if
			
		} // End do_slide_nav
		
		this.get_active_side = function(){
			
			var active = self.show.find('.cpb-slide-item.active').first();
			
			if ( ! active.length ) {
				
				var active = self.show.find('.cpb-slide-item').first();
				
				active.addClass('active');
				
			} // End if
			
			return active;
			
		} // End get_active_side
		
		this.get_next_slide_index = function( active_slide, dir ){
			
			var next_slide_index = false;
			
			var slides = self.show.find('.cpb-slide-item');
			
			var count = slides.length;
			
			var active_slide_index = active_slide.index();
			
			if ( dir > 0 ){
				
				if ( active_slide_index < ( count - 1 ) ) {
					
					next_slide_index = ( active_slide_index + 1 );
					
				} else {
					
					next_slide_index = 0;
					
				} // End if
				
			} else {
				
				if ( active_slide_index === 0 ) {
					
					next_slide_index = ( count - 1 );
					
				} else {
					
					next_slide_index = ( active_slide_index - 1 );
					
				} // End if
				
			} // End if
			
			return next_slide_index;
			
		} // End get_next_slide
		
		this.do_slide = function( active_slide, next_slide, dir ) {
			
			self.update_thumb_active( next_slide );
			
			if ( dir > 0 ){
				
				var active_left = '-100%';
				var next_left = '100%';
				
			} else {
				
				var active_left = '100%';
				var next_left = '-100%';
			}
			
			next_slide.css( { left : next_left, top:0 });
			
			active_slide.animate({left: active_left}, 1000);
			next_slide.animate({left: 0}, 1000, function(){
				active_slide.removeClass('active');
				active_slide.removeAttr('style');
				next_slide.addClass('active');
				
				});
		} // End do_slide
		
		this.update_thumb_active = function( next_slide ){
			
			var thumbs = self.show.find('.cpb-slide-thumb');
			
			thumbs.removeClass('active');
			
			thumbs.eq( next_slide.index() ).addClass('active');
			
		} // End update_thumb_active
		
		this.init();
		
	} // End ci_slideshow
	
} // End if

if ( typeof cpb_slideshow_array === 'undefined' ) {
	
	var cpb_slideshow_array = new Array();
	
} // End if

jQuery('.cbp-slideshow-feature.inactive').each(
	
	function(){
		
		var show = new cpb_slideshow( jQuery( this ) );
		
		jQuery( this ).removeClass('inactive'); 
		
		cpb_slideshow_array.push( show );
		
	} // End function
	
) // End each