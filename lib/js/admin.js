CPB = {
	
	wrap: jQuery( '#cpb-editor' ),
	
	init: function(){
		
		CPB.layout.set_style();
		
		jQuery( 'body').on( 'click' , '.cpb-close-form-action' , function( event ){ CPB.s_event( event ); CPB.forms.hide_form() } );
		
		CPB.wrap.on( 'click' , '.add-row-item' , function( event ){ CPB.s_event( event ); CPB.layout.add_row( jQuery( this ) ) } );
		
		CPB.wrap.on( 'click' , '.cpb-add-items-set li' , function( event ){ CPB.layout.add_item( jQuery( this ) ) } );
		
		CPB.wrap.on( 'click' , '.add-item-action' , function( event ){ CPB.s_event( event ); CPB.forms.show_add_item_form( jQuery( this ) ) } );
		
		CPB.wrap.on( 'click' , '.add-part-action' , function( event ){ CPB.s_event( event ); CPB.layout.add_part_basic( jQuery( this ) ) } );
		
		CPB.wrap.on( 'click' , '.cpb-remove-item-action' , function( event ){ CPB.s_event( event ); CPB.layout.remove_item( jQuery( this ) ) } );
		
		CPB.wrap.on( 'click' , '.cpb-edit-item-action' , function( event ){ CPB.s_event( event ); CPB.forms.edit_item( jQuery( this ) ) } );
		
		CPB.wrap.on( 'click' , '.cpb-tabs a' , function( event ){ CPB.s_event( event ); CPB.forms.change_tab( jQuery( this ) ) } );
		
		CPB.wrap.on( 'mouseenter' , '.cpb-content-item' , function(){ CPB.layout.set_active( jQuery( this ) , true );})
		
		CPB.wrap.on( 'mouseleave' , '.cpb-content-item' , function(){ CPB.layout.set_active( jQuery( this ) , false );})
		
		jQuery( window ).resize(function() { CPB.layout.set_content_height( CPB.wrap ); });
		
		CPB.layout.apply_sort( CPB.wrap );
		
		CPB.forms.add_lb_bg();
		
		//CPB.layout.load_content( CPB.wrap );
		
		CPB.layout.set_content( CPB.wrap , false );
		
		CPB.forms.init();
		
		CPB.feilds.remote_options.init();
		
		CPB.layout.editor_options.init();
		
	}, // end init
	
	
	s_event : function( event ){ event.preventDefault(); },
	
	
	layout: {
		
		wrap: jQuery('#cpb-editor-layout'),
		
		current_column: false,
		
		style: false,
		
		editor_options: {
			
			init: function(){
				
				CPB.wrap.on( 'click' , '#cpb-editor-options label' , function( event ){ CPB.layout.editor_options.set_editor( jQuery( this ) ) } );
				
				CPB.wrap.on( 'click' , '#cpb-excerpt-options label' , function( event ){ CPB.layout.editor_options.set_excerpt( jQuery( this ) ) } );
				
			},
			
			set_editor: function( ic ){
				
				if ( ! ic.hasClass('active') ){
					
					ic.addClass('active').siblings().removeClass('active');
					
					alert('You must hit either Save Draft, Publish or Update to change editing mode');
					
				} // end if
				
			}, // end set_editor
			
			set_excerpt: function( ic ){
				
				if ( ! ic.hasClass('active') ){
					
					ic.addClass('active').siblings().removeClass('active');
					
					if ( ic.next('input').val() == 0 ){
						
						ic.siblings('textarea').attr('disabled', 'disabled');
						
					} else {
						
						ic.siblings('textarea').attr('disabled', false);
						
					} // end if
					
				} // end if
				
			}// end set_excerpt
			
		}, // end editor_options
		
		set_style: function(){

			var data = CPB.wrap.find( 'input[name="_wp_http_referer"],input[name="ajax-nonce"],input[name="ajax-post-id"]' ).serialize();
			
			data += '&action=cpb_ajax&service=get_style';
				
			jQuery.post(
				ajaxurl,
				data,
				
				function( response ){

					alert( response );
					
					CPB.layout.style = response;
					
					CPB.layout.apply_style( CPB.wrap );
					
				} // end function
			);
			
		},
		
		apply_style: function( wrap ){
			
			if ( CPB.layout.style ){ 
				
				wrap.find('iframe.cpb-editor-content').each( function(){
					
					jQuery( this ).contents().find('head').append( '<style>' + CPB.layout.style + 'html,body {background: none;}</style>' );
					
				});
			
			} // end if
			
			CPB.layout.set_content_height();
			
		},
		
		add_item: function( ic ){
			
			var data = CPB.forms.get_data( ic ); 
			
			CPB.layout.get_part( data , CPB.layout.current_column );
			
		},
		
		set_active: function( ic , set ){
			
			if ( set ){
				
				ic.addClass('active');
				
			} else {
				
				ic.removeClass('active');
				
			} // end if
			
		}, // end 
		
		remove_item: function( ic ){
			
			var itm = ic.closest('.cpb-item');
			
			itm.slideUp('fast' , function(){ itm.remove(); CPB.layout.set_children(); });
			
		},
		
		add_row: function( ic ){
			
			var data = CPB.forms.get_data( ic );
			
			var container = CPB.wrap.find( '#cpb-editor-layout > .cpb-child-set' );
			
			CPB.layout.get_part( data , container );
			
		}, // end add_row
		
		get_part: function( data , container ){
			
			jQuery.post(
				ajaxurl,
				data + '&action=cpb_ajax&service=get_part',
				function( response ){

					//console.log( response  );
					
					CPB.layout.insert_part( response , container );
					
				} // end function
				,'json'
			);
			
		},
		
		add_part_basic: function( ic , event ){
			
			if ( event ) event.preventDefault();
			
			var data = CPB.forms.get_data( ic );
			
			var cont = ic.closest('.cpb-item').children('.cpb-child-set');
			
			CPB.layout.get_part( data , cont );
			
		},
		
		apply_sort: function( container ){
			
			// Make Rows Sortable
			container.find('.cpb-child-set.cpb-layout-set').sortable({ 
				handle: '.cpb-move-item-action',
				stop: function( event , ui ){ CPB.layout.set_children(); CPB.layout.set_columns(); CPB.layout.set_content( ui.item , true  ) }, 
			});
			
			// Make Items Sortable
			container.find('.cpb-child-set-items').sortable({ 
				handle: '.cpb-item-title, .cpb-edit-item-action',
				connectWith: '.cpb-child-set-items',
				stop: function( event , ui ){ CPB.layout.set_children(); CPB.layout.set_content( ui.item , true ) }, 
			});
			
		},
		
		insert_part: function ( part_json , container ){
			
			CPB.forms.hide_form();
			
			container.append( part_json.editor );
			
			CPB.forms.wrap.prepend( part_json.forms ); 
			
			CPB.layout.set_children();
			
			CPB.layout.apply_sort( container.parent() );
			
			CPB.layout.set_wp_editor( container );
			
			CPB.forms.post_search.apply_sort();
			
			//CPB.layout.set_content( container , true );
			
			if ( part_json.is_content == 1 ){
				
				window.setTimeout( function(){ CPB.forms.show_form( part_json.id ); },200 );
				
			} // end if
			
			
		}, // end insert_part
		
		set_wp_editor: function( container ){
			
			container.find('.cpb-wp-editor').each( function(){
				
				var id = jQuery( this ).data( 'id' );
				
				var id_data = id.split( '_' );
				
				if ( jQuery('#' + id ).length == 0 ){
				
					var editor = jQuery('.cpb-blank-editor').first();
					
					editor.find('input.cpb-form-item-id').val( id_data[0] );
					
					editor.find('input','select').each( function(){
						
						var inpt_n = jQuery( this ).attr('name');
						
						if ( inpt_n ){
						
							inpt_n = inpt_n.replace( /_cpb\[.*?\]/g , '_cpb[' + id + ']' );
							
							jQuery( this ).attr('name' , inpt_n );
						
						} // end if
						
					});
					
					var textarea = editor.find('textarea');
					
					var name = textarea.attr('name');
					
					textarea.attr( 'name' , '_cpb_content_' + id );
					
					textarea.attr( 'data-updateid' , name );
					
					editor.find('.cpb-form-item-id').attr('name' , '_cpb[items][' + id + ']' )
					
					editor.removeClass( 'cpb-blank-editor' );
					
					editor.attr( 'id' , id );
				
				} // end if
				
			});
			
			
		},
		
		set_children: function(){
			
			CPB.layout.wrap.find('.cpb-child-set').each( function(){
				
				var children = [];
				
				var set = jQuery( this );
				
				set.children().each( function(){
					
					children.push( jQuery( this ).data('id') );
					
				});
				
				set.closest('.cpb-item').children('fieldset').find('input.cpb-children-input').val( children.join(',') );
				
				console.log( children );
				
			});
			
		}, // end set_children
		
		set_columns: function( container ) {
			
			CPB.layout.wrap.find( '.cpb-row' ).each( function(){
				
				jQuery( this ).find('.cpb-column').each( function( index ){
					
					var col_index = ['one','two','three','four','five','six','seven','eight','nine','ten']
					
					var c = jQuery( this );
					
					c.removeClass('column-one column-two column-three column-four column-five column-six column-seven column-eight column-nine column-ten');
					
					c.addClass( 'column-' + col_index[ index ] );
					
					c.find('.cpb-column-index').html( index + 1 );
					
				})
				
			})
			
		}, // end set_columns
		
		set_content: function( wrap , wp_editor ){
			
			CPB.layout.apply_style( wrap );
			
			var form_data = '';
			
			var forms = [];
			
			var ids = [];
			
			var items = wrap.find('.cpb-editor-content');
			
			items.each( function(){
				
				var id = jQuery(this).data('id');
				
				var form = jQuery('#' + id );
				
				forms.push( CPB.forms.get_form_data( form , wp_editor ) );
				 
				//forms.push( 'fieldset#' + id ); 
				
				//ids.push( id );
				
				});
			
			CPB.layout.get_content( forms.join('&') );
			
		},
		
		get_content: function( data ){
			
			jQuery.post(
				ajaxurl,
				data + '&action=cpb_ajax&service=get_content',
				function( response ){
					
					//alert( response );
					
					CPB.layout.insert_content( response );
					
				} // end function
				,'json'
			);
			
		}, // end get_content
		
		insert_content: function( content_array ){
			
			for ( var key in content_array ) {
				
				// skip loop if the property is from prototype
        		if( !content_array.hasOwnProperty( key ) ) continue;
				
				var content = content_array[ key ];
				
				jQuery('#item-content-' + key ).contents().find('body').html( '<main style="padding:0;margin:0;">' + content.replace(/\\"/g, '"') + '</main>' );
				
			} // end for
			
			CPB.layout.set_content_height( CPB.wrap );
			
		},
		
		
		update_content: function( form ){
			
			if ( form.hasClass('cpb-content-item-form') ){
				
				CPB.layout.apply_style( CPB.wrap );
				
				CPB.layout.get_content( CPB.forms.get_form_data( form , true ) );
				
			} // end if
			
		},
		
		set_content_height: function( parent ){
			
			jQuery('.cpb-editor-content').each( function(){
				
				var h = jQuery(this).contents().find('main').height() + 20;
				
				jQuery( this ).height( h );
				
			})
		}
		
	}, // end layout
	
	
	forms: {
		
		wrap: jQuery('#cpb-editor-forms'),
		
		lightbox: false,
		
		init: function(){
			
			CPB.wrap.on( 'click' , '.cpb-form-item-remove' , function( event ){ CPB.s_event( event ); CPB.forms.remove_item( jQuery( this ) ) } );
			
			CPB.forms.multi_form.init();
			
			CPB.forms.load_media();
			
			CPB.forms.post_search.init();
			
			CPB.forms.select_post.init();
			
			CPB.forms.multi_select.init();
			
		},
		
		remove_item: function( ic ){
			
			ic.closest('.cpb-form-item').slideUp('fast' , function(){ jQuery( this ).remove(); });
			
		}, // end remove_item
		
		get_data: function( form ){
			
			var data = CPB.wrap.find( 'input[name="_wp_http_referer"],input[name="ajax-nonce"],input[name="ajax-post-id"]' ).serialize();
			
			data += '&' + form.find( 'input, textarea, select').serialize();
			
			//alert( data );
			
			return data;
			
		},
		
		get_part_data: function( form ){
			
			var data = CPB.wrap.find( 'input[name="_wp_http_referer"],input[name="ajax-nonce"],input[name="ajax-post-id"]' ).serialize();
			
			data += '&' + form.find( 'input, textarea, select').serialize();
			
			//alert( data );
			
			return data;
			
		},
		
		get_form_data: function( form , wp_editor ){
			
			var data = CPB.wrap.find( 'input[name="_wp_http_referer"],input[name="ajax-nonce"],input[name="ajax-post-id"]' ).serialize();
			
			data += '&' + form.serialize();
				
			var id = form.attr('id');
			
			if ( form.hasClass( 'cpb-wp-editor-item-form' ) && form.find('.wp-editor-wrap.tmce-active').length > 0 && wp_editor ){
				
				var textarea = jQuery( 'textarea[name="_cpb_content_' + id + '"]');
				
				if ( typeof textarea.data('updateid') != 'undefined' ){
					
					var tmce_id = textarea.data('updateid');
					
				} else {
					
					var tmce_id = '_cpb_content_' + id;
					
				} // end if
				
				//console.log( tmce_id + ',' + id );
				
				data += '&_cpb_content_' + id + '=' + encodeURIComponent( tinyMCE.get( tmce_id ).getContent() ); 
				
				
			} // end if
			
			return data;
			
		}, // end get_form_data
		
		show_add_item_form: function( ic ){
			
			CPB.layout.current_column = ic.siblings('.cpb-child-set');
			
			CPB.forms.show_form( 'cpb-add-item-form');
			
		},
		
		edit_item: function( ic ){
			
			CPB.forms.show_form( ic.closest('.cpb-item').data('id') );
			
		}, // end edit_item
		
		show_form: function( id ){
			
			var form = jQuery( '#' + id ).parent();
			
			form.addClass('active');
			
			CPB.forms.set_height( form );
			
			CPB.forms.show_lb(); 
			
		}, // end show_form
		
		hide_form: function(){
			
			var form = CPB.wrap.find('.cpb-item-form-wrap.active');
			
			form.removeClass('active');
			
			CPB.forms.hide_lb();
			
			CPB.layout.update_content( form.find('fieldset') );
			
		},
		
		set_height: function( form ){
			
			win_h = jQuery(window).scrollTop();
			
			par_off = form.offsetParent().offset().top;
			
			frm_h = ( win_h - par_off ) + 60;
			
			form.css('top', frm_h ); 
			
		}, // end form_set_height
		
		add_lb_bg: function(){
			
			jQuery('body').append('<div id="cpb-lb-bg" class="cpb-close-form-action" style="display:none"></div>');
			
			CPB.forms.lightbox = jQuery('#cpb-lb-bg');
			
		},
		
		show_lb: function(){
			
			CPB.forms.lightbox.fadeIn('fast');
			
		},
		
		hide_lb: function(){
			
			CPB.forms.lightbox.fadeOut('fast');
			
		},
		
		change_tab: function( tab ){
			
			tab.addClass('active').siblings().removeClass('active');
			
			var sel = tab.closest('.cpb-item-form').find('.cpb-item-section').eq( tab.index() );
			
			sel.addClass('active').siblings().removeClass('active');
			
		},
		
		show_mulit_form: function( ic , action ){
			
			var form = ic.closest('.cpb-multi-form');
			
			if ( 'close' == action ){
				
				form.find('fieldset').removeClass('active').prop('disabled', true);
				
				form.find('cpb-multi-form-options').removeClass('active');
				
			} else {
				
				form.find('cpb-multi-form-options').addClass('active');
				
				form.find('fieldset').eq( ic.index() ).addClass('active').prop('disabled', false);
				
			} // end if
			
		},
		
		multi_select: {
			
			init: function(){
				
				CPB.forms.multi_select.bind_events();
				
				CPB.forms.multi_select.apply_sort();
				
			},
			
			bind_events: function(){
				
				jQuery('body').on(
					'click',
					'.cpb-form-dropdown-multi-select li',
					function(){
						CPB.forms.multi_select.select( jQuery( this ) );
					}
				);
				
				
			}, // end bind_events
			
			select: function( ic ){
				
				if ( ic.hasClass('selected') ){
					
					ic.removeClass('selected');
					
				} else {
					
					ic.addClass('selected');
					
				} // End if
				
				CPB.forms.multi_select.set_ids( ic );
				
			},
			
			set_ids: function( ic ){
				
				var ids = [];
				
				var drop_down = ic.closest('.cpb-form-dropdown-multi-select' );
				
				var input = drop_down.siblings('input');
				
				drop_down.find('li.selected').each( function(){
					
					ids.push( jQuery(this).data('postid') );
					
				});
				
				input.val( ids.join(',') );
				
			}, // End set_ids
			
			apply_sort: function(){
				
				jQuery('.cpb-form-dropdown-multi-select').sortable({
					stop: function( event , ui ){ CPB.forms.multi_select.set_ids( ui.item ); }
				}
				);
			
				//jQuery('.cpb-form-dropdown-multi-select').sortable(//{ 
					//stop: function( event , ui ){ /*CPB.forms.multi_select.set_ids( ic );*/ }, 
				//});
				
			}
			
		},
		
		select_post:{
			
			init: function(){
				
				CPB.forms.select_post.bind_events();
				
			},
			
			bind_events: function(){
				
				jQuery('body').on(
					'click',
					'.cpb-select-post-updated-action',
					function( event ){
						event.preventDefault();
						CPB.forms.select_post.add_post( jQuery( this ) );
					}
				);
				
			}, // end bind_events
			
			add_post: function( ic ){
				
				var wrapper = ic.closest('.cpb-select-post');
				
				var basename = wrapper.data('basename');
				
				var select = wrapper.find('select');
				
				var val = select.val();
				
				var text = select.find('option:selected').text();
				
				var selected = wrapper.find('.cpb-select-post-selected');
				
				var input = '<label>' + text + '</label><input type="text" name="' + basename + '" value="' + val + '" />';
				
				selected.append( input );
				
			}, // End add_post
			
		}, // End select_post
		
		multi_form: {
			
			init: function(){
				
				CPB.wrap.on( 'click' , '.close-multi-form-action' , function( event ){ CPB.s_event( event ); CPB.forms.multi_form.hide( jQuery( this ) ) } );
				
				CPB.wrap.on( 'change' , '.cpb-accordion-form-checkbox' , function( event ){ CPB.forms.multi_form.show( jQuery( this ) ) } );
				
				CPB.wrap.on('click' , '.cpb-search-field a', function( event){ CPB.s_event( event ); CPB.forms.post_search.do_search( jQuery( this ) ); })
			
			},
			
			hide: function( ic ){
			
				var form = ic.closest('.cpb-multi-form');
		
				form.find('fieldset').removeClass('active').prop('disabled', true);
				
				form.find('.cpb-multi-form-options').removeClass('active');
				
				form.find('.cpb-accordion-form-checkbox').attr('checked', false);
			
			},
			
			show: function( ic ){
				
				if ( ic.is(":checked") ){
					
					var form = ic.closest('.cpb-multi-form');
			
					var ops = ic.closest('.cpb-multi-form-options');
					
					form.find('fieldset').eq( ops.find('.cpb-accordion-form-checkbox').index( ic ) ).addClass('active').prop('disabled', false);
				
					ops.addClass('active');
				
				} // end if
			
			},
			
		},
		
		load_media: function(){
				
			if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
				
				jQuery('body').on('click', '.add-media-action', function(event) {
					
					event.preventDefault();
					
					var wrap = jQuery(this).closest( '.cwp-add-media-wrap');
					
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
			
		}, // end media_loader
		
		post_search: {
			
			init: function(){
				
				CPB.wrap.on( 'click' , '.cpb-form-search-posts .cpb-search li' , function( event ){ CPB.forms.post_search.results( jQuery( this ) ); } );
				
				CPB.wrap.on( 'mouseenter' , '.cpb-form-field.cpb-search' , function( event ){ CPB.forms.post_search.show_drop( jQuery( this ) , true ); } );
				
				CPB.wrap.on( 'mouseleave' , '.cpb-form-field.cpb-search' , function( event ){ CPB.forms.post_search.show_drop( jQuery( this ) , false ); } );
				
				CPB.forms.post_search.apply_sort();
			
			},
			
			apply_sort: function(){
				
				CPB.wrap.find('.cpb-results-set').sortable();
				
			},
			
			get_site: function( ic ){
				
				return ic.closest('.cpb-form-search-posts').find('.cpb-select-site-url input').val();
				
			}, // end get_site
			
			show_drop: function( ih , show ){
				
				var wrap = ih.find('ul');
				
				wrap.stop();
				
				if ( show ){
					
					wrap.slideDown(500);
					
				} else {
					
					wrap.slideUp(500);
				}
				
			}, // end show drop
			
			do_search: function(ic){
				
				var data = ic.siblings('input').serialize();
				
				data += '&site=' + CPB.forms.post_search.get_site( ic );
				
				jQuery.post(
					ajaxurl,
					data + '&action=cpb_ajax&service=search_posts',
					function( response ){
						
						if ( response ){
							
							var drop = ic.siblings('ul');
							
							drop.html('').show();
							
							for ( var key in response ) {
				
								// skip loop if the property is from prototype
								if( !response.hasOwnProperty( key ) ) continue;
								
								drop.append('<li data-id="' + response[ key ].ID + '">' + response[ key ].title + '</li>');
								
								drop.css('height','auto');
								
								//alert( response[ key ].title );
								
							} // end for
							
						} // end if
						
						//CPB.layout.insert_content( response );
						
					} // end function
					,'json'
				);
				
			}, // end do_search
			
			results: function( ic ){
				
				CPB.forms.post_search.show_drop( ic.closest('.cpb-form-field.cpb-search') , false )
				
				var wrap = ic.closest('.cpb-form-search-posts');
					
				var results = wrap.find('.cpb-results-set');
					
				var id = ic.data('id');
					
				var title = ic.html();
					
				var site = CPB.forms.post_search.get_site( ic );
					
				var input = '<li class="cpb-form-item">' + title + '<a href="#" class="cpb-form-item-remove"></a><input type="text" name="' + wrap.data('basename') + '[post-' + id + '][id]" value="' + id + '" />';
					
				input += '<input type="text" name="' + wrap.data('basename') + '[post-' + id + '][title]" value="' + title + '" />';
					
				input += '<input type="text" name="' + wrap.data('basename') + '[post-' + id + '][site]" value="' + site + '" /></li>';
					
				results.delay(1000).append( input );
				
				
			} // end results
			
		},
		
	}, // end forms
	
	feilds: {
		
		remote_options: {
			
			init: function(){
				
				CPB.wrap.on('click', '.cpb-action-load-remote-feed-options' , function(){ 
					CPB.s_event( event ); 
					CPB.feilds.remote_options.get_options( jQuery(this) ); });
				
			}, // end init
			
			get_options: function( ic ){
			
				var wrap = ic.closest('.cpb-form-remote-feed');
				
				var url = ic.siblings('input').val();
				
				if ( url ){
					
					CPB.feilds.remote_options.request( url , 'post_types' , wrap.find('.cpb-remote-select-post-type select') );
					
					CPB.feilds.remote_options.request( url , 'taxonomies' , wrap.find('.cpb-remote-select-taxonomy-type select') );
					
				} // end if
			
			}, // end get_options
			
			request_types: function( url , wrap ){
			}, // request_types
			
			request: function( url , type , select_ops ){
				
				if ( select_ops.length ){
				
					jQuery.post(
						ajaxurl,
						'&action=cpb_ajax&service=remote_request&type=' + type + '&site=' + url,
						
						function( response ){
							
							if ( response ){
								
								var ops = '';
								
								for ( var key in response ) {
					
									// skip loop if the property is from prototype
									if( !response.hasOwnProperty( key ) ) continue;
									
									if ( select_ops.find('option[value="' + response[ key ] +'"]' ).length ) continue;
									
									ops += '<option class="item-' + response[ key ] +'" value="' + response[ key ] + '">';
									
									ops += response[ key ].charAt(0).toUpperCase() + response[ key ].slice(1) + '</option>';
									
								} // end for
								
								select_ops.append( ops );
								
							} // end if
							
							//CPB.layout.insert_content( response );
							
						} // end function
						,'json'
					);
					
				} // end if
				
			}, // request_taxonomies
			
		} // end get_remote_options
		
	}
	
}

CPB.init();