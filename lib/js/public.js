/*****************************************************************
* Add CPB Slideshow Functionality
****************************************************************/
if (typeof cpb_slider == 'undefined') {
	var cpb_slider = function( show_elem ) {
		this.show = show_elem;
		var self = this;
		this.init = function() {
			self.show.addClass('is-init');
			self.show.on('click','a.next',function(e) {e.preventDefault();self.next_slide() });
			self.show.on('click','a.prev',function(e) {e.preventDefault();self.prev_slide() });
			self.show.on('click', '.slideshow-secondary > a',function(e) {e.preventDefault();self.nav_slide( jQuery( this ) ) });
			self.add_secondary_nav();
		}
		this.nav_slide = function( ic ) {
			this.chng_slide( ic.index(), 'auto' ); 
		}
		this.next_slide = function() {
			var c = self.show.find('.slide.active-slide');
			var n_index = ( c.index() + 1 ); 
			if ( n_index == self.show.find('.slide').length ) {
				n_index = 0; 
			};
			this.chng_slide( n_index, 1 ); 
		}
		this.prev_slide = function() {
			var c = self.show.find('.slide.active-slide');
			var n_index = ( c.index() - 1 ); 
			if ( n_index < 0 ) {
				n_index = ( self.show.find('.slide').length - 1 ); 
			};
			this.chng_slide( n_index, -1 );
		}
		this.chng_slide = function( n_index, dir ) {
			self.do_secondary_active( n_index );
			var c = self.show.find('.slide.active-slide');
			var n = self.show.find('.slide').eq( n_index );
			if ( c.index() == n_index ) return; 
			if ( dir == 'auto' ) {
				var dir = self.return_dir( n_index, c );
			} // end if
			if ( dir > 0 ) {
				var s_left = '100%';
				var c_left = '-100%';
			} else {
				var s_left = '-100%';
				var c_left = '100%';
			}
			n.css( {top: '0px', left: s_left})
			n.animate( {left:'0'}, 750 );
			c.animate( {left:c_left}, 750, function() {
				n.removeAttr('style').addClass('active-slide');
				c.removeAttr('style').removeClass('active-slide');
			} );
			var nav = self.show.find('nav.thumbs a');
			nav.removeClass('active-slide');
			nav.eq( n_index ).addClass('active-slide');
		}
		this.return_dir = function( n_index, c ) {
			if ( c.index() > n_index ) {
				return -1;
			} else {
				return 1
			}
		}
		this.do_secondary_active = function( index ) {
			var s_nav = self.show.find('.slideshow-secondary a');
			if ( s_nav.length ) {
				s_nav.removeClass('active-slide');
				s_nav.eq( index ).addClass('active-slide');
			} // end if
		}
		this.add_secondary_nav = function() {
			var s_nav = self.show.find('.slideshow-secondary');
			console.log( s_nav );
			if ( s_nav.length ) {
				var slides =self.show.find('.slide');
				slides.each( function() {
					var slide = jQuery( this );
					var active = ( slide.hasClass('active-slide') ) ? 'active-slide' : '';
					var thumb = '<a class="' + active + '" href="#">';
					var bg_image = jQuery( this ).find('.slide_img_bg');
					if ( bg_image.length ) {
						thumb += '<span style="' + bg_image.attr('style') + ';">';
					} // end if
					thumb += '</a>';
					//var thumb = '<a href="#"><span style="background-image: url(' + jQuery(this).css('background-image') + ');"></span></a>';
					s_nav.append( thumb );
				});
			} // end if
		}
	} // end cpb_slider
}
if (typeof cpb_shows == 'undefined') var cpb_shows = new Array();
jQuery( '.cpb-slideshow' ).not('.is-init').each( function() {
	var s = jQuery( this );
	var slds = new cpb_slider( s );
	slds.init();
	cpb_shows.push( slds );
} );
jQuery('body').on('click','.cpb-more-button a', function( event ) { 
	event.preventDefault();

	var but = jQuery( this );

	var wrap = jQuery( this ).closest('.cpb-more-content');

	if ( wrap.hasClass('active') ) {

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

	init: function() {

		cahnrs_pagebuilder.lightbox.init();	
		cahnrs_pagebuilder.layout.bind_events();
		cahnrs_pagebuilder.layout.column_css();
		cahnrs_pagebuilder.faq.init();
		cahnrs_pagebuilder.lists.init();
		cahnrs_pagebuilder.social.bind_events();
		cahnrs_pagebuilder.az_index.bind_events();
		cahnrs_pagebuilder.tabs.init();

	}, // end init

	az_index:{

		bind_events:function() {

			jQuery('body').on('click','.cpb-az-index-wrap .cpb-az-index-nav .cpb-az-index-nav-item.has-items', function() {

				var c = jQuery(this);

				c.addClass('active').siblings().removeClass('active');

				var wrap = c.closest('.cpb-az-index-wrap');

				var next = wrap.find('.cpb-az-index-alpha-set').eq( c.index() );

				next.addClass('active').siblings().removeClass('active');

			});

		} // End bind_events

	},

	social:{

		bind_events:function() {

			jQuery('body').on('click','.cpb-social-icon',function() {

				var c = jQuery(this);

				if ( ! c.hasClass('is-link') ) {

					c.addClass('active').siblings().removeClass('active');

					var wrap = c.closest('.cpb-social-item');

					var next = wrap.find('.cpb-social-content').eq( c.index() );

					next.addClass('active').siblings().removeClass('active');

				} // End if

			});

		} // End bind_events

	}, // End socail

	lists:{

		init:function() {

			cahnrs_pagebuilder.lists.bind_events();

			jQuery( '.list-style-drop-down li' ).each( function() {
				if ( jQuery( this ).children('ul').length ) {

					jQuery( this ).addClass('is-drop-down');

				} // End if
			});

		},

		bind_events:function() {

			jQuery('body').on('click', 'li.is-drop-down', function() { cahnrs_pagebuilder.lists.accordion( jQuery( this ) ); } );

		},

		accordion:function( list_item ) {

			var submenu = list_item.children('ul');

			if ( submenu.length ) {

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

		bind_events:function() {
			jQuery(window).resize( function() { cahnrs_pagebuilder.layout.column_css(); });
		},

		column_css:function() {

			jQuery( '.row > .column, .cpb-row .cpb-column').each( function() {
				var c = jQuery( this );

				if ( ! c.hasClass('no-size') ) {
					c.removeClass('small medium large medium-small column-size-100 column-size-200 column-size-300 column-size-400 column-size-500 column-size-600 column-size-700 column-size-800 column-size-900 column-size-1000 column-size-1100 column-size-1200 column-size-1300');
					var w = c.width();
					var nw = ( Math.floor( (c.width()/100) ) * 100 );
					var cls = 'large';
					if ( w < 450 ) {
						cls = 'small';
					} else if( w >= 450 && w < 600 ) {
						cls = 'medium-small';
					} else if ( w >= 600 && w < 900 ) {
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

		init: function() {

			if ( jQuery('.as-lightbox').length > 0 ) {

				cahnrs_pagebuilder.lightbox.add_lb();

				cahnrs_pagebuilder.lightbox.set_lb();

				jQuery('body').on('click', '.as-lightbox a', function( event ) {  cahnrs_pagebuilder.lightbox.show_lb( jQuery( this ), event ); })

				jQuery('body').on('click', '.close-lb', function( event ) {  cahnrs_pagebuilder.lightbox.close_lb(); })  

				cahnrs_pagebuilder.lightbox.iframe.load( function() { cahnrs_pagebuilder.lightbox.set_content_height() });

			} // end if

		}, // end init

		set_lb: function() {

			cahnrs_pagebuilder.lightbox.bg = jQuery('#pb-lb-bg');

			cahnrs_pagebuilder.lightbox.frame_wrap = jQuery('#pb-lb-frame-wrap');

			cahnrs_pagebuilder.lightbox.frame = jQuery('#pb-lb-frame');

			cahnrs_pagebuilder.lightbox.iframe = jQuery('#pb-lb-frame iframe');

		}, // end set_lb

		show_lb: function( ic, event ) {

			event.preventDefault();

			jQuery('#pb-lb-bg').fadeIn('fast');

			cahnrs_pagebuilder.lightbox.set_height();

			var par = ic.closest('.cpb-item');

			var url = par.data('requesturl');

			cahnrs_pagebuilder.lightbox.iframe.attr( 'src', url );

			//cahnrs_pagebuilder.lightbox.ajax( cahnrs_pagebuilder.lightbox.serial( par ), par.data('requesturl') );

		}, // end show_lb

		close_lb: function() {

			cahnrs_pagebuilder.lightbox.frame_wrap.css('top',-99999 );

			cahnrs_pagebuilder.lightbox.bg.fadeOut('fast');

			cahnrs_pagebuilder.lightbox.iframe.attr('src','about:blank');

		},


		add_lb: function() {

			var html = '<div id="pb-lb-bg" class="close-lb"></div>';

			html += '<div id="pb-lb-frame-wrap" class="close-lb">';

				html += '<div id="pb-lb-frame">';

					html += '<a href="#" class="close-lb">x</a>';

					html += '<iframe src="about:blank"></iframe>';

				html += '</div>';

			html += '</div>';

			jQuery('body').append( html );

		}, // end add_lb

		set_height: function() {

			win_h = jQuery(window).scrollTop();

			par_off = cahnrs_pagebuilder.lightbox.frame_wrap.offsetParent().offset().top;

			frm_h = ( win_h - par_off ) + 60;

			cahnrs_pagebuilder.lightbox.frame_wrap.css('top', frm_h ); 

		}, // end form_set_height

		set_content_height: function() {

			var h = cahnrs_pagebuilder.lightbox.iframe.contents().find('body').height() + 30;

			cahnrs_pagebuilder.lightbox.iframe.height( h );

		} // end set_content_height

	}, // end lightbox

	faq:{

		init:function() {

			cahnrs_pagebuilder.faq.events();

		}, // end init

		events:function() {

			jQuery('body').on(
				'click',
				'.cpb-faq dt',
				function() {
					cahnrs_pagebuilder.faq.toggle( jQuery( this ).closest( '.cpb-faq' ) );
				}
			);

		}, // end events

		toggle:function( faq ) {

			var sibs = faq.siblings('.cpb-faq');

			sibs.find('dt').removeClass('active');

			sibs.find('dd').slideUp('fast');

			if ( faq.find('dt').hasClass('active') ) {

				faq.find('dt').removeClass('active');

				faq.find('dd').slideUp('fast');

			} else {

				faq.find('dt').addClass('active');

				faq.find('dd').slideDown('fast');

			} // end if

		}, // end toggle

	}, // End FAQ

	tabs : {

		init: function() {

			cahnrs_pagebuilder.tabs.column_tabs.bind_events();

		}, // End init

		column_tabs: {

			bind_events : function() {

				jQuery('body').on( 
					'click',
					'.cpb-item-tabs-columns .cpb-item-tab-title',
					function() {
						cahnrs_pagebuilder.tabs.column_tabs.do_tab( jQuery( this  ) );
					}
				);

				/*jQuery('body').on( 
					'click',
					'.cpb-item-tabs-columns .cpb-item-tab-content',
					function() {
						cahnrs_pagebuilder.tabs.column_tabs.do_tab( jQuery( this  ).prev('.cpb-item-tab-title') );
					}
				);*/

			}, // End bind_events

			do_tab:function( tab ) {

				var tab_data = cahnrs_pagebuilder.tabs.column_tabs.get_tab_obj( tab );

				if ( tab_data.tab.hasClass('active')) {

					cahnrs_pagebuilder.tabs.column_tabs.close_tab( tab_data );

				} else {

					cahnrs_pagebuilder.tabs.column_tabs.open_tab( tab_data );

				} // End if

			}, // End do_tab

			open_tab : function( tab_data ) {

				//var tab_data = cahnrs_pagebuilder.tabs.column_tabs.get_tab_obj( tab_data );

				tab_data.tab_siblings.find('.cpb-item-tab-inner-content').hide();
				tab_data.tab_siblings.animate( {width: tab_data.tab_small + '%'}, 1000, function() { tab_data.tab.addClass('active')} );
				tab_data.tab_siblings.removeClass('active');

				tab_data.tab.animate( {width: tab_data.tab_width +'%'}, 1000, function() {

					tab_data.tab.find('.cpb-item-tab-inner-content').fadeIn('fast');

				} );

				tab_data.tab_content.animate( {width: tab_data.content_width +'%'}, 1000, function() {

					tab_data.tab_content_inner.hide();
					tab_data.tab_content_inner.addClass('active');
					tab_data.tab_content_inner.fadeIn('fast');
				} );

				tab_data.tabs_content.find('.cpb-item-tab-inner-content').removeClass('active');

				tab_data.tab_content_siblings.animate({width: 0}, 1000 );

			}, // End open tab

			close_tab : function( tab_data ) {


				//var tab_data = cahnrs_pagebuilder.tabs.column_tabs.get_tab_obj( tab );

				tab_data.tabs_content.find('.cpb-item-tab-inner-content').removeClass('active');

				tab_data.tab.siblings('.cpb-item-tab-title').animate( {width: tab_data.tab_width + '%'}, 1000, function() {

					tab_data.tab.siblings('.cpb-item-tab-title').find('.cpb-item-tab-inner-content').fadeIn('fast');

					tab_data.tab.removeClass('active');

				} );



				tab_data.tab_content.animate( {width: 0 }, 1000 );

			}, // End

			get_tab_obj : function( tab ) {

				var tab_data = {};
				tab_data.tab = tab;
				tab_data.tab_inner = tab_data.tab.find('.cpb-item-tab-inner-content');
				tab_data.tab_wrapper = tab_data.tab.closest('.cpb-item-tabs-set');
				tab_data.tabs = tab_data.tab_wrapper.find('.cpb-item-tab-title');
				tab_data.tab_siblings = tab_data.tab.siblings('.cpb-item-tab-title');
				tab_data.tab_content = tab_data.tab.next('.cpb-item-tab-content');
				tab_data.tab_content_inner = tab_data.tab_content.find('.cpb-item-tab-inner-content');
				tab_data.tab_content_siblings = tab_data.tab_content.siblings('.cpb-item-tab-content');
				tab_data.tabs_content = tab_data.tab_wrapper.find('.cpb-item-tab-content');
				tab_data.tab_small = 5;
				tab_data.tab_count = tab_data.tabs.length;
				tab_data.tab_width = (100 / tab_data.tab_count );
				tab_data.content_width = ( 100 - ( tab_data.tab_width + ( tab_data.tab_small * ( tab_data.tab_count - 1 ) ) ) );

				return tab_data;

			}

		}, // End Column Tabs



	}, // End tabs


};

cahnrs_pagebuilder.init();

/*********************************************************
 * Start CPB Slideshow JS. This is used on all slideshows with the
 * cbp-slideshow-feature class
**********************************************************/
if ( typeof cpb_slideshow === 'undefined' ) {

	var cpb_slideshow = function( slideshow ) {

		this.show = slideshow;

		var self = this;

		this.init = function() {

			self.bind_events();

		} // End init

		this.bind_events = function() {

			self.show.on( 'click', '.cpb-slide-nav', function() { self.do_slide_nav( jQuery( this ) ) });

			self.show.on( 'click', '.cpb-slide-thumb', function() { self.do_slide_thumb( jQuery( this ) ) });

		} // End bind_events

		this.do_slide_thumb = function( item_clicked ) {

			var active_slide = self.get_active_side();

			var next_index = item_clicked.index();

			if ( active_slide.index() == next_index ) return;

			var next_slide = self.show.find('.cpb-slide-item').eq( next_index );

			var dir = ( active_slide.index() > next_index )? -1:1;

			if ( next_slide.length ) {

				self.do_slide( active_slide, next_slide, dir );

			} // end if

		} // End do_slide_thumb

		this.do_slide_nav = function( item_clicked ) {

			dir = ( item_clicked.hasClass('slide-prev') ) ? -1:1;

			var active_slide = self.get_active_side();

			var next_index = self.get_next_slide_index( active_slide, dir );

			if ( next_index !== false ) {

				var next_slide = self.show.find('.cpb-slide-item').eq( next_index );

				if ( next_slide.length ) {

					self.do_slide( active_slide, next_slide, dir );

				} // end if

			} // End if

		} // End do_slide_nav

		this.get_active_side = function() {

			var active = self.show.find('.cpb-slide-item.active').first();

			if ( ! active.length ) {

				var active = self.show.find('.cpb-slide-item').first();

				active.addClass('active');

			} // End if

			return active;

		} // End get_active_side

		this.get_next_slide_index = function( active_slide, dir ) {

			var next_slide_index = false;

			var slides = self.show.find('.cpb-slide-item');

			var count = slides.length;

			var active_slide_index = active_slide.index();

			if ( dir > 0 ) {

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

			if ( dir > 0 ) {

				var active_left = '-100%';
				var next_left = '100%';

			} else {

				var active_left = '100%';
				var next_left = '-100%';
			}

			next_slide.css( { left : next_left, top:0 });

			active_slide.animate({left: active_left}, 1000);
			next_slide.animate({left: 0}, 1000, function() {
				active_slide.removeClass('active');
				active_slide.removeAttr('style');
				next_slide.addClass('active');

				});
		} // End do_slide

		this.update_thumb_active = function( next_slide ) {

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

	function() {

		var show = new cpb_slideshow( jQuery( this ) );

		jQuery( this ).removeClass('inactive'); 

		cpb_slideshow_array.push( show );

	} // End function

) // End each