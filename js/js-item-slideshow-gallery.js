if (typeof cpb_slider == 'undefined') {
	var cpb_slider = function( show_elem ){
		this.show = show_elem;
		var self = this;
		this.init = function(){
			self.show.addClass('is-init');
			self.show.on('click','a.next',function(e){e.preventDefault();self.next_slide() });
			self.show.on('click','a.prev',function(e){e.preventDefault();self.prev_slide() });
			self.show.on('click', 'nav.slideshow-secondary > a' ,function(e){e.preventDefault();self.nav_slide( jQuery( this ) ) });
			self.add_secondary_nav();
		}
		this.nav_slide = function( ic ){
			this.chng_slide( ic.index() , 'auto' ); 
		}
		this.next_slide = function(){
			var c = self.show.find('.slide.active-slide');
			var n_index = ( c.index() + 1 ); 
			if ( n_index == self.show.find('.slide').length ){
				n_index = 0; 
			};
			this.chng_slide( n_index , 1 ); 
		}
		this.prev_slide = function(){
			var c = self.show.find('.slide.active-slide');
			var n_index = ( c.index() - 1 ); 
			if ( n_index < 0 ){
				n_index = ( self.show.find('.slide').length - 1 ); 
			};
			this.chng_slide( n_index , -1 );
		}
		this.chng_slide = function( n_index , dir ){
			self.do_secondary_active( n_index );
			var c = self.show.find('.slide.active-slide');
			var n = self.show.find('.slide').eq( n_index );
			if ( c.index() == n_index ) return; 
			if ( dir == 'auto' ){
				var dir = self.return_dir( n_index , c );
			} // end if
			if ( dir > 0 ){
				var s_left = '100%';
				var c_left = '-100%';
			} else {
				var s_left = '-100%';
				var c_left = '100%';
			}
			n.css( {top: '0px', left: s_left})
			n.animate( {left:'0'} , 750 );
			c.animate( {left:c_left} , 750 , function(){
				n.removeAttr('style').addClass('active-slide');
				c.removeAttr('style').removeClass('active-slide');
			} );
			var nav = self.show.find('nav.thumbs a');
			nav.removeClass('active-slide');
			nav.eq( n_index ).addClass('active-slide');
		}
		this.return_dir = function( n_index , c ){
			if ( c.index() > n_index ){
				return -1;
			} else {
				return 1
			}
		}
		this.do_secondary_active = function( index ){
			var s_nav = self.show.find('nav.slideshow-secondary a');
			if ( s_nav.length ){
				s_nav.removeClass('active-slide');
				s_nav.eq( index ).addClass('active-slide');
			} // end if
		}
		this.add_secondary_nav = function(){
			var s_nav = self.show.find('nav.slideshow-secondary');
			console.log( s_nav );
			if ( s_nav.length ){
				var slides =self.show.find('.slide');
				slides.each( function(){
					var slide = jQuery( this );
					var active = ( slide.hasClass('active-slide') ) ? 'active-slide' : '';
					var thumb = '<a class="' + active + '" href="#">';
					var bg_image = jQuery( this ).find('.slide_img_bg');
					if ( bg_image.length ){
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
jQuery( '.cpb-slideshow' ).not('.is-init').each( function(){
	var s = jQuery( this );
	var slds = new cpb_slider( s );
	slds.init();
	cpb_shows.push( slds );
} );