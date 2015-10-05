// JavaScript Document
(function() {
	
	var cpb_obj = function( $ ){
		
		var s = this;
		
		s.add = new Object();
		
		s.pb = $('#cwp-pb');
		
		s.pbf = s.pb.find('#cwp-pb-forms');
		
		s.lb = false;
		
		// Actions
		s.pb.on( 'mouseenter' , '.cpb-section > footer, .cpb-column > .inner-wrapper > footer' , function(){ s.display_opts( $( this ) , 'show' ); });
		
		s.pb.on( 'mouseleave' , '.cpb-section > footer, .cpb-column > .inner-wrapper > footer' , function(){ s.display_opts( $( this ) , 'hide' ); });
		
		s.pb.on( 'click' , '.cpb-edit-item' , function( event ){ event.preventDefault(); s.edit_item( $( this ).data('id') ); });
		
		s.pb.on( 'click' , '.close-form-action' , function( event ){ event.preventDefault(); s.form_display( $( this ).closest( '.cpb-form') , 'hide' ); });
		
		s.pb.on( 'click' , '.add-part-action' , function( event ){ event.preventDefault(); s.add_part( $( this ) ); });
		
		s.pb.on( 'click' , '.ajax-part-action' , function( event ){ event.preventDefault(); s.ajax_part( $( this ) ); }); 
		
		s.pb.on( 'click' , '.remove-item-action' , function( event ){ event.preventDefault(); s.remove_item( $( this ) ); });
		
		s.pb.on( 'click' , '.cpb-item-icon' , function(){ $( this ).toggleClass( 'active' ); });
		
		s.pb.on( 'click' , '.cpb-form-frame nav a' , function( event ){ event.preventDefault(); s.tab_form( $( this ) ); });
		
		s.pb.on( 'click' , '.cpb-subform-nav label' , function(){ s.subform_section( $( this ) ); });
		
		//s.pb.on( 'click', '.cpb-form-frame .cpb-layout label', function(){ $(this).toggleClass('active').siblings().removeClass('active');});
		
		s.pb.on( 'change', 'input[type=radio]', function(){ s.active_label( $( this ) )});
		
		s.pb.on( 'change' , '.cbp-form-subsection > header input', function(){ 
			
			s.new_sec( $( this ).closest('.cbp-form-subsection')  );
			
		});
		
		$('body').on( 'click' , '#cpb-lb-bg' , function(){ s.form_display( $('.cpb-form.active') , 'hide');});
		
		
		
		$('.cpb-item-section-set').sortable({ 
			handle: 'header.cpb-item-row-header',
			stop: function(){ s.set_children() }, 
		});
		
		$('.cpb-item-row-set').sortable({ 
			handle: 'header.cpb-item-column-header',
			stop: function(){ s.set_children() }, 
		});
		
		$('.cpb-column-item-set').sortable({ 
			handle: 'header.cpb-item-header',
			connectWith: '.cpb-column-item-set',
			stop: function(){ s.set_children() }, 
		});
		
		/*$('.cpb-item-set').not('.cpb-column-item-set').sortable({ 
			handle: 'header',
			stop: function(){ s.set_children() }, 
		});
		
		$('.cpb-item-set').not('.cpb-column-item-set').sortable({ 
			handle: 'header',
			stop: function(){ s.set_children() }, 
		});
		
		$('.cpb-column-item-set').sortable({ 
			handle: 'header' , 
			connectWith: '.cpb-column-item-set',
			stop: function(){ s.set_children() }, 
		});*/
		
		s.subform_section = function( label ){
			
			var i = label.index() * 0.5;
			
			label.closest('.cpb-form-section').find('.cpb-subform-section').eq( i ).addClass('active').siblings('.cpb-subform-section').removeClass('active'); 
			
		} // end subform_section

		
		s.active_label = function( input ){
			
			var n = input.attr('name');
			
			var id = input.attr('id');
			
			if ( id !== undefined && id && n !== undefined && n ){
				
				var label = input.siblings('label[for="' + id + '"]');
				
				label.addClass('active');
				
				s.pb.find( 'input[name="' + n + '"]' ).not( input ).siblings('label').not( label ).removeClass('active');
				
				
			} // end if
			
			/*var n = input.attr('name');
			
			if ( n && n !== undefined ){
				
				s.pb.find( 'input[name="' + n + '"]' ).not( input ).siblings('label').removeClass('active');
				
			} // end if
			
			input.siblings('label').addClass('active');*/
			
		} // end active_label
		
		
		s.tab_form = function( ic ){
			
			ic.addClass('active').siblings().removeClass('active');
			
			ic.closest('.cpb-form-frame').find('.cpb-form-content').eq( ic.index() ).addClass('active').siblings().removeClass('active'); 
			
		} // end tab_form
		
		
		s.remove_item = function( ic ){
			
			var itm = ic.closest( '.cpb-item');
			
			itm.slideUp('fast' , function(){ itm.remove(); s.set_children(); });
			
		} // end remove_item
		
		
		/*
		* Set layout children
		*/
		s.set_children = function(){
			
			s.pb.find( '.cpb-item-set' ).each( function(){
				
				var chdr = new Array();
				
				var set = $( this );
				
				set.children().each( function() {
					
					if ( $( this ).attr('data-id') ) chdr.push( $( this ).attr('data-id') );
					
				});
				
				set.siblings( '.cpb-input-items-set' ).val( chdr.join(',') );
				
			});
			
			s.pb.find( '.cpb-row').each( function(){
				
				var cls = 'one two three four five six seven eight nine ten';
				
				var row = $( this );
				
				var cols = row.children('.cpb-item-set').children('.cpb-column');
				
				console.log( cols );
				
				cols.each( function( index ){
					
					var c_cls = cls.split(' ');
					
					$(this).removeClass('one two three four five six seven eight nine ten');
					
					//console.log( index );
					
					$(this).addClass( c_cls[ index ] );
					
				} );
				
			});
			
		} // end set_children
		
		/*
		* Show the buttons in footer of object on call
		*/
		s.display_opts = function( wrap , action ){
			
			var opts = wrap.find( 'a' );
			
			if ( 'hide' == action ){
				
				opts.stop().slideUp('fast');
				
			} else {
				
				opts.stop().slideDown('fast');
				
			} // end if
			
		} // end show_options
		
		
		/*
		* Show edit item form
		*/
		s.edit_item = function( id ){
			
			var form = $( '#form_' + id );
			
			s.form_display( form , 'show' ); 
			
		} // end edit_item
		
		/*
		* Show add item form
		*/
		s.add_part = function( ic ){
			
			switch ( ic.data( 'part' ) ) {
				
				case 'item':
					s.add.type = 'item';
					s.add.form = $( '#form_cpb_add_item' );
					s.add.obj = ic;
					s.add.container = ic.closest( 'footer').siblings('.cpb-item-set');
					break;
				case 'row':
					s.add.type = 'row';
					s.add.form = $( '#form_cpb_add_row' );
					s.add.obj = ic;
					s.add.container = ic.closest( 'footer').siblings('.cpb-item-set');
					break;
				
			} // end switch
			
			s.form_display( s.add.form , 'show' );
			
		} // end add_item
		
		/*
		* Show or hide the form
		*/
		s.form_display = function( form , action ){
			
			if ( 'show' == action ){
				
				//form.addClass('active');
				
				form.css('top', '0' ).addClass('active');
				
				s.set_height( form );
				
				s.lb.fadeIn('fast');
				
			} else {
				
				//form.removeClass('active');
				
				form.css('top', '-9999px' ).removeClass('active');
				
				s.lb.fadeOut('fast');
				
			} // end if
			
		} // end form_display
		
		s.set_height = function( form ){
			
			win_h = jQuery(window).scrollTop();
			
			par_off = form.offsetParent().offset().top;
			
			frm_h = ( win_h - par_off ) + 60;
			
			form.css('top', frm_h ); 
			
		} // end form_set_height
		
		s.ajax_part = function( ic ){
			
			var form = ic.closest( '.cpb-form');
			
			var data = form.serialize();
			
			data += '&action=cpb_ajax&service=add_part';
			
			$.post(
				ajaxurl,
				data,
				function( response ){
					
					//alert( response );
					
					s.add.container.append( response.editor );
					
					for ( var i = 0; i < response.forms.length ; i++ ){
						
						if ( response.forms[i].type == 'textblock' ){
							
							var editor = s.pb.find( '.cpb-extra-editor' ).first();
							
							editor.removeClass( 'cpb-extra-editor' );
							
							editor.attr( 'id' , 'form_' + response.forms[i].id );
							
							editor.find( 'textarea').attr( 'name' , '_content_' + response.forms[i].id );
							
						} else {
							
							s.pbf.append( response.forms[i].form );
							
						}// end if
						
					} // end for
					
					console.log( response );
					
					s.set_children();
					
				},
				'json'
			);
			
			s.form_display( form , 'hide' );
			
		} // end ajax_part
		
		s.add_lightbox_bg = function(){
			
			$( 'body').append('<div id="cpb-lb-bg" style="display:none"></div>' );
			
			s.lb = $('#cpb-lb-bg');
			
		} // end lb
		
		
		s.add_media_uploader = function(){
				
			if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
				
				$('body').on('click', '.add-media-action', function(event) {
					
					event.preventDefault();
					
					var wrap = $(this).closest( '.cwp-add-media-wrap');
					
					var id = wrap.find('.cpb-add-media-id');
					
					var img_src = wrap.find('.cpb-add-media-src');
					
					var img = wrap.find('.cpb-add-media-img');
					
					wp.media.editor.send.attachment = function(props, attachment) {
						
						id.val(attachment.id);
						
						console.log( attachment );
						
						img_src.val(attachment.url);
						
						img.html( '<img src="' + attachment.url + '" />' );
						
						
					};
					
					wp.media.editor.open(wrap);
					
					return false;
					
				}); // end on click
				
			} // end if
			
		} // end add_media_uploader
		
		s.new_sec = function( sec ){
			
			sec.addClass('active').children('div').slideDown('fast' , function(){ sec.addClass('selected') });
			
			var sibs = sec.siblings('.cbp-form-subsection')
			
			sibs.children('div').slideUp('fast' , function(){ sibs.removeClass('selected') });
			
		} // end s.new_sec
		
		// Call on Init
		s.add_media_uploader();
		
		s.add_lightbox_bg();
		
		
	} // end cpb_obj
	
	
	
	jQuery( document ).ready( function( $ ){ var cpb = new cpb_obj( $ ); });
	
})()