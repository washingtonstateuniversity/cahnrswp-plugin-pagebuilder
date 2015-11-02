// JavaScript Document
jQuery(document).ready(function(){
	
	var cpb_init = function(){
		
		this.pb = jQuery('#cwp-pb');
		
		this.layout = new cpb_layout();
		
		this.add = new cpb_add();
		
		this.forms = new cpb_forms();
		
		var s = this;
		
		// Start Click Actions
		
		s.pb.on( 'click' , '#cwp-pb-options a.cpb-radio-button:not(.selected)' , function(){ alert('You must select "Update" or "Save Draft" to update the editing mode')});
		
		s.pb.on( 'click' , '.cpb-radio-button' , function( event ) { s.forms.select_radio( jQuery( this ) , event )});
		
		s.pb.on( 'click' , '.cpb-tab' , function( event ) { s.forms.select_tab( jQuery( this ) , event )});
		
		s.pb.on( 'click' , '.cpb-accordion' , function( event ) { s.forms.select_accordion( jQuery( this ) , event )});
		
		jQuery('body').on('click','.close-form-action, #cpb-lb-bg', function( event ){ event.preventDefault(); s.forms.close_form(); });
		
		// Remove Item
		s.layout.wrap.on('click' , '.remove-item-action' , function( event ){ s.layout.remove( jQuery(this), event ); });
		
		// Add Item Form
		s.layout.wrap.on('click' , '.add-item-action' , function( event ){ 
			
			event.preventDefault();
		
			s.forms.show_form( jQuery('#form_cpb_add_item') );
			
			s.add.add_loc = jQuery( this ).closest('.cpb-column').children('.cpb-item-set'); 
		
		});
		
		// Add Item
		s.forms.wrap.on('click' , '.ajax-add-item-action' , function(){ s.add_item( jQuery( this ).closest('.cpb-form') ); });
		
		// Edit Item 
		s.layout.wrap.on('click','.cpb-edit-item' , function(event){event.preventDefault(); s.forms.get_form( jQuery(this) ); });
		
		//Add Row 
		s.layout.wrap.on('click','.cpb-add-row-form nav ul', function(){ s.add_row( jQuery( this ) );});
		
		s.layout.wrap.on('mouseenter','.cpb-column-item > .cpb-item-set > a' , function(){
			jQuery(this).closest('.cpb-item-set').addClass('active');
			});
		
		s.layout.wrap.on('mouseleave','.cpb-column-item > .cpb-item-set > a' , function(){
			jQuery(this).closest('.cpb-item-set').removeClass('active');
			});
		
		
		s.add_item = function( form ){
			
			var data = s.add.add_item_data( form );
			
			jQuery.post(
				ajaxurl,
				data,
				function( response ){
					
					s.layout.add_part( response.editor , s.add.add_loc );
					
					s.forms.add_forms( response.forms );
					
				},
				'json'
			);
			
			console.log(data);
			
		} // end add_item
		
		s.add_row = function( obj ){
			
			s.add.add_loc = obj.closest('.cpb-item').children('.cpb-item-set')
			
			var data = s.add.add_row_data( obj );
			
			jQuery.post(
				ajaxurl,
				data,
				function( response ){
					
					s.layout.add_part( response.editor , s.add.add_loc );
					
					s.forms.add_forms( response.forms );
					
				},
				'json'
			);
		}
		
		
		// Start Init Actions
		s.layout.apply_sort( s.layout.wrap );
		
		s.forms.add_lightbox();
		
		s.forms.sel_hover('.cpb-add-row-form nav ul' , false );
		
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
		
	} // end cpb
	
	
	
	
	
	
	var cpb_layout = function(){
		
		this.wrap = jQuery('#cwp-pb-editor');
		
		var l = this;
		
		l.remove = function( item , event ){
			
			event.preventDefault();
			
			item.closest( '.cpb-item' ).slideUp('fast', function(){ 
				
				jQuery( this ).remove(); 
				
				l.set_children();
			}); 
			
		} // end remove
		
		
		l.set_children = function(){
			
			l.wrap.find( '.cpb-item-set' ).each( function(){
				
				var set = new Array();
				
				jQuery( this ).children('.cpb-item').each( function(){
					
					if ( jQuery( this ).attr('data-id') ) set.push( jQuery( this ).attr('data-id') );
					
				}); // end each
				
				jQuery( this ).siblings( '.cpb-input-items-set' ).val( set.join(',') );
				
			}) // end each
			
		} // end set_children
		
		
		l.apply_sort = function( bounds ){
			
			// Sort Columns
			bounds.find('.cpb-section > .cpb-item-set').sortable({ 
				handle: '.cpb-item-row-header .title, .cpb-item-pagebreak-header .title',
				//connectWith: '.cpb-section > .cpb-item-set',
				stop: function(){ l.set_children(); l.class_columns(); }, 
			});
			
			// Sort Columns
			bounds.find('.cpb-row > .cpb-item-set').sortable({ 
				handle: '.cpb-item-column-header .title',
				stop: function(){ l.set_children(); l.class_columns(); }, 
			});
			
			// Sort Items
			bounds.find('.cpb-column > .cpb-item-set').sortable({ 
				handle: '.cpb-item-header .title',
				connectWith: '.cpb-column-item-set',
				stop: function(){ l.set_children(); }, 
			});
			
		} // end apply sort
		
		l.class_columns = function(){
			
			var cls = ['one','two','three','four','five','six','seve','eight','nine','ten'];
			
			l.wrap.find('.cpb-row > .cpb-item-set').each( function(){
				
				jQuery( this ).children('.cpb-item').each( function( index ){
					
					jQuery(this).removeClass('one two three four five six seven eight nine ten');
					
					jQuery(this).addClass( cls[ index ] );
					
				}); // end each
				
			}); // end each
			
		} // end class_columns
		
		
		l.add_part = function( part , loc ){
			
			loc.append( part );
			
			l.set_children();
			
		} // end add part
		
	} // end cpb layout
	
	
	
	
	var cpb_add = function(){
		
		this.add_loc = false;
		
		var a = this;
		
		a.add_item_data = function( form ){
			
			var data = 'item_slug=' + form.find('.cpb-add-item-wrapper.selected').first().data('type') + '&action=cpb_ajax&service=add_part';
			
			return data;
			
		} // end add_item_data
		
		a.add_row_data = function( obj ){
			
			data = '&action=cpb_ajax&service=add_part';
			
			obj.find('input').each( function(){
				
				data += '&' + jQuery( this ).serialize();
			
			});
			
			return data;
			
		} // end add_item_data
		
	} // end cpb_add
	

	var cpb_forms = function(){
		
		this.wrap = jQuery('#cwp-pb-forms');
		
		this.lb = false;
		
		var f = this;
		
		f.wrap.on('click','.cpb-toggle-select', function( event ){ event.preventDefault(); f.toggle_select( jQuery(this) );});
		
		f.toggle_select = function( obj ){
			
			obj.addClass('selected')
			
			if( obj.hasClass('cpb-radio') ) {
				
				obj.siblings().removeClass('selected');
			
				obj.parent().siblings().children().removeClass('selected');
				
			} // end if
			
		} // end toggle_select
		
		f.show_form = function( form ){
			
			form.addClass('active');
				
			f.set_height( form );
				
			f.lb.fadeIn('fast');
			
		} // end show form
		
		f.close_form = function(){
			
			jQuery('.cpb-form.active').css('top', '-9999px' ).removeClass('active');
				
			f.lb.fadeOut('fast');
			
		} // end close form
		
		f.set_height = function( form ){
			
			win_h = jQuery(window).scrollTop();
			
			par_off = form.offsetParent().offset().top;
			
			frm_h = ( win_h - par_off ) + 60;
			
			form.css('top', frm_h ); 
			
		} // end form_set_height
		
		f.add_lightbox = function(){
			
			jQuery( 'body').append('<div id="cpb-lb-bg" style="display:none"></div>' );
			
			f.lb = jQuery('#cpb-lb-bg');
			
		} // end lb
		
		f.add_forms = function( forms ){
			
			for ( var i = 0; i < forms.length ; i++ ){
						
				if ( forms[i].type == 'textblock' ){
					
					var editor = f.wrap.find( '.cpb-extra-editor' ).first();
					
					editor.removeClass( 'cpb-extra-editor' );
					
					editor.attr( 'id' , 'form_' + forms[i].id );
					
					editor.find( 'textarea').attr( 'name' , '_content_' + forms[i].id );
					
				} else {
					
					f.wrap.append( forms[i].form );
					
				}// end if
				
			} // end for
			
		} // end add_forms
		
		f.get_form = function( obj ){
			
			var fid = obj.closest('.cpb-item').data('id');
			
			form = f.wrap.find( '#form_' + fid );
			
			f.show_form( form );
			
		} // end get_form
		
		f.sel_hover = function( obj_sel , parent ){
			
			jQuery( 'body' ).on('mouseenter' , obj_sel , function(){
				
				var sel = jQuery( this )
				
				if ( parent ) sel = sel.closest( parent );
					
				sel.addClass('selected');
				 
			}); 
			
			jQuery( 'body' ).on('mouseleave' , obj_sel , function(){ 
			
				var sel = jQuery( this );
				
				if ( parent ) sel = sel.closest( parent );
				
				sel.removeClass('selected'); 
			
			}); 
			
		} // end sel_hover
		
		f.select_radio = function( obj , event ){
			
			event.preventDefault(); 
			
			obj.addClass('selected');
			
			obj.find('input').prop("checked", true);
			
			obj.siblings('.cpb-radio-button.selected').removeClass('selected');
			
		} // end select radio
		
		f.select_tab = function( obj , event ){
		
			event.preventDefault(); 
			
			obj.addClass('active').siblings().removeClass('active');
			
			var par = obj.closest('.cpb-tab-container');
			
			var i = obj.index();
			
			var tc = par.find('.cpb-tab-content');
			
			tc.eq( i ).show();
			
			tc.not(':eq(' + i + ')').hide();
			
		} // end select tab
		
		f.select_accordion = function( obj , event ){
		
			event.preventDefault(); 
			
			obj.addClass('selected').siblings('.cpb-accordion').removeClass('selected');
			
			obj.find('input').prop("checked", true);
			
			obj.next('.cpb-accordion-content').slideDown('fast').addClass('selected').siblings('.cpb-accordion-content').slideUp('fast').removeClass('selected');
			
		} // end select tab
		
		
	} // end cpb_forms
	
	
	
	
	
	
	var cpb = new cpb_init();
	
}); // End jQuery