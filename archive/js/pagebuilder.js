// JavaScript Document
( function( document, window ) { 
	
	var cwpb_init = function( $ ){
		
		var s = this;
		
		s.add_media_uploader = function(){
			
			if ( $('.add-media-action').length > 0) {
				
				if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
					
					$('body').on('click', '.add-media-action', function(event) {
						
						event.preventDefault();
						
						var wrap = $(this).closest( '.cwpb-add-media-wrap');
						
						var id = wrap.find('.cwpb-add-media-id');
						
						var img_src = wrap.find('.cwpb-add-media-src');
						
						var img = wrap.find('.cwpb-add-media-img');
						
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
				
			}; // end if
			
		} // end add_media_uploader
		
		// Call on Init
		s.add_media_uploader();
		
	}



	
	var cwp_pg = function( $ ){
		
		this.edtr = new pb_edtr();
		
		this.lb = new pb_lb();
		
		var s = this;
		
		s.edtr.pb.on( 'click', '.cwpb-form-tab' , function( event ){
			
			event.preventDefault();
			
			s.edtr.form_tab( jQuery( this ) );
			
		});
		
		s.edtr.pb.on( 'click', '.edit-item-action' , function( event ){
			
			event.preventDefault();
			
			var form_id = jQuery( this ).closest( '.cwpb-object' ).attr('id');
			
			var form = jQuery('.' + form_id );
			
			s.lb.show_lb( form );
			
		});
		
		s.edtr.pb.on( 'click', '.add-part-action' , function( event ){
			
			var c = jQuery( this );
			
			event.preventDefault();
			
			var part = $( this ).data( 'part');
			
			s.edtr.set_current( c , part );
			
			var form = s.edtr.get_part_form( part );
			
			s.lb.show_lb( form );
			
			//s.edtr.get_ajax_part( part );
			
		});
		
		// Add Item Action
		s.edtr.pb.on( 'click' , '.add-item-action' , function( event ){
			
			var c = jQuery( this );
			
			event.preventDefault();
			
			s.edtr.set_current( c , 'column' );
			
			var form = s.edtr.form.add_item;
			
			s.lb.show_lb( form );
			
		});
		
		s.edtr.pb.on( 'click' , '.ajax-part-action' , function( event ){
			
			var c = jQuery( this );
			
			event.preventDefault();
			
			s.edtr.add_ajax_part( c.closest( 'fieldset' ) );
			
		});
		
		s.edtr.pb.on( 'click' , '.delete-item-action' , function( event ){
			
			var c = jQuery( this );
			
			event.preventDefault();
			
			s.edtr.remove_obj( c );
			
		});
		
		s.edtr.pb.on( 'click' , '.settings-action' , function( event ){
			
			var c = jQuery( this );
			
			event.preventDefault();
			
			var form = s.edtr.get_form( c );
			
			s.lb.show_lb( form );
			
		});
		
		// Handle Hover state for editing object from contents
		s.edtr.pb.on( 'mouseenter' , '.cwpb-item-content ' , function(){
			
			jQuery( this ).find('.edit-item-action').fadeIn('fast');
			
		});
		
		s.edtr.pb.on( 'mouseleave' , '.cwpb-item-content ' , function(){
			
			jQuery( this ).find('.edit-item-action').fadeOut('fast');
			
		});
		
		
		
		// Show add item button on hover
		/*s.edtr.pb.on( 'mouseenter' , '.page-column' , function(){
			
			jQuery( this ).find('.add-item-action').fadeIn('fast');
			
		});
		
		s.edtr.pb.on( 'mouseleave' , '.page-column' , function(){
			
			jQuery( this ).find('.add-item-action').fadeOut('fast');
			
		});*/
		
		
		
		
		/**
		 * Start add Row section
		*/
		
		/*s.pb.on( 'click' ,'a.add-row-action' , function( event ) {
			
			event.preventDefault();
			
			s.cur.row = $( this );
			
		});
		
		s.pb.on( 'click' , '.sumbit-add-row-action' , function( event ){
			
			event.preventDefault();
			
			var ser = s.form.row.find( 'input,select' ).serialize();
			
			$.post( 
				ajaxurl,
				ser, 
				function( data ) {
					s.cur.row.siblings('.items-set').append( data );
					//alert( data );
				}
			);
			
		});*/
		
		/**
		 * Start add Section
		*/
		
		/*s.pb.on( 'click' ,'a.add-section-action' , function( event ) {
			
			event.preventDefault();
			
			s.lb.show_lb( s.form.sec );
			
			s.cur.sec = $( this );
			
		});
		
		s.pb.on( 'click' , '.sumbit-add-section-action' , function( event ){
			
			event.preventDefault();
			
			var ser = s.form.sec.find( 'input,select' ).serialize();
			
			s.lb.hide_lb();
			
			$.post( 
				ajaxurl,
				ser, 
				function( data ) {
					s.cur.sec.siblings('.items-set').append( data );
					//alert( data );
				}
				
			);
			
		});*/
		
		/**
		 * Start Lightbox
		*/
		
		
		
	}
	
	var pb_lb = function(){
		
		jQuery( 'body').append( '<div id="pg-lb-bg" class="close-lb-action" style="display: none"></div>' );
		
		this.bg = jQuery( '#pg-lb-bg' );
		
		var s = this;
		
		s.show_lb = function( frame ){
			
			s.set_height( frame );
			
			s.bg.fadeIn( 'fast' );
			
			frame.addClass( 'active_form' );
			
		}
		
		s.hide_lb = function(){
			
			frame = jQuery( '.cwpb-lb-item.active_form' );
			
			frame.removeClass( 'active_form' );
			
			frame.css('top', -9999 );
			
			s.bg.fadeOut( 'fast' );
		}
		
		jQuery( 'body' ).on( 'click' , '.close-lb-action' , function( event ){
				
			event.preventDefault();
			
			s.hide_lb();
			
		});
		
		s.set_height = function( frame ){
			
			win_h = jQuery(window).scrollTop();
			
			par_off = frame.offsetParent().offset().top;
			
			frm_h = ( win_h - par_off ) + 100;
			
			frame.css('top', frm_h ); 
			
		}
		
	}
	
	var pb_edtr = function(){
		
		this.pb = jQuery( '#cwp-pagebuilder' );
		
		this.form = new Object();
		
		this.form.row = this.pb.find('.cwpb-settings-form.cwpb-add-row');
		
		this.form.section = this.pb.find('.cwpb-settings-form.cwpb-add-section');
		
		this.form.item = this.pb.find('.cwpb-settings-form.cwpb-add-item');
		
		this.cur = new Object();
		
		var s = this;
		
		s.add_ajax_part = function( form ){
			
			form.prepend( '<input id="cwpb-part-input" type="hidden" name="action" value="pb_editor_part" />' );
			
			var ser = form.find( 'input,select' ).serialize();
			
			form.find('#cwpb-part-input').remove();
			
			var c = s.cur.item;
			
			jQuery.post( 
				ajaxurl,
				ser, 
				function( data ) {
					
					console.log( data );
					
					c.after( data.editor );
					
					s.ajax_add_form( data );
					
					s.set_items();
					
					//s.make_sortable()
				}
				,'json'
			);
			
		}
		
		s.get_items_container = function( current , type ){
			
			var container = false;
			
			
			
			return container;
			
		}
		
		s.set_items = function(){
			
			jQuery('.cwpb-child-items').each( function(){
				
				var s = jQuery( this );
				
				var child_ids = new Array();
				
				var children = s.children( '.cwpb-item' );
				
				s.children( '.cwpb-item' ).each( function(){
					
					child_ids.push( jQuery( this ).attr( 'id' ) );
					
				}) // end each
				
				s.siblings( 'input.cwpb-child-items-input').val( child_ids.join(',') )
				
				
				/*var id = s.attr( 'id' );
				
				var item_set = s.find( '.items-set').filter('.' + id );
				
				var input = s.find('.items-set-input').filter('.' + id );
				
				var items = item_set.children();
				
				var ids = new Array();
			
				items.each( function(){ ids.push( jQuery( this ).attr('id') ) } );
				
				input.val( ids.join(',') );*/
				
			});
			
		}
		
		s.ajax_add_form = function( data ){
			
			if ( 'textblock' == data.type ){
				
				var form = jQuery('.cwpb-settings-form.is-cwpb-editor.is-cwpb-unset').first();
				
				form.addClass( data.id ).removeClass( 'is-cwpb-unset' );
				
				form.find( 'textarea' ).attr('name','_cwpb_content_' + data.id );
				
			} else {
				
				jQuery('#cwp-pagebuilder-forms').prepend( data.forms );
				
			}// end if
			
		}
		
		s.set_current = function( item , type ){
			
			s.cur.item = item;
			
			s.cur.type = type;
			
		}
		
		s.get_part_form = function( part ){
			
			switch ( part ){
				
				case 'section':
					var form = s.form.section;
					break;
				case 'row':
					var form = s.form.row;
					break;
				case 'item':
					var form = s.form.item;
					break;
				
			} // ens switch
			
			return form;
			
		}
		
		s.remove_obj = function( ic ){
			
			var obj = ic.closest( '.cwpb-item' );
			
			obj.slideUp('fast' , function(){ obj.remove(); s.set_items(); });
			
			jQuery( '.pb_settings_form' ).filter('.' + obj.attr( 'id' ) ).remove();
			
		}
		
		s.make_sortable = function(){
			
			s.pb.find('.items-set-section, .items-set-row, .items-set-column').sortable( { 
			handle: ".move-action" ,
			connectWith: ".items-set"
			} );
		
			s.pb.find( '.items-set-editor' ).sortable({ handle: ".move-action" })
			
		}
		
		s.get_form = function( item_clicked ){
			
			var id = item_clicked.closest('.cwpb-item').attr('id');
			
			var form = jQuery( 'fieldset.' + id );
			
			return form;
			
		}
		
		s.form_tab = function( c ){
			
			var index = c.index();
			
			var form = c.closest( 'fieldset' );
			
			var sect = form.find('.cwpb-settings-form-content').children();
			
			sect.eq( index ).addClass('active').siblings().removeClass('active');
			
			c.addClass('active').siblings().removeClass('active');
			
		}
		
	}
	
	jQuery( document ).ready( function( $ ){ var pagebuilder = new cwp_pg( $ ); });
	
	jQuery( document ).ready( function( $ ){ 
		var cwpb = new cwpb_init( $ ); 
	});
	
} )( document , window );