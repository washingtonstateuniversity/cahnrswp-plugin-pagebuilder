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
		
	}, // end init
	
	layout:{
		
		bind_events:function(){
			jQuery(window).resize( function(){ cahnrs_pagebuilder.layout.column_css(); });
		},
		
		column_css:function(){
			
			jQuery( '.row > .column').each( function(){
				var c = jQuery( this );
				c.removeClass('small medium large');
				var w = c.width();
				var cls = 'large';
				if ( w < 450 ){
					cls = 'small';
				} else if ( w >= 500 && w < 900 ){
					cls = 'medium';
				} // end if
				
				c.addClass( cls );
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
		
		/*ajax: function( data , request_url ){
			
			alert( request_url );
			
			jQuery.get(
				request_url,
				data,
				function( response ){
					
					alert( response );
					
				} 
			);
			
		} // end ajax*/
		
	} // end lightbox
	
	
}
cahnrs_pagebuilder.init();