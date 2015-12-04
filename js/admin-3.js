// JavaScript Document

var CWPB = {
	wrap: jQuery('#cwp-pb'),
	
	init: function(){
		
		CWPB.set_events()
		
		},
		
	set_events: function(){
		
		// Update editor item on click
		CWPB.wrap.on('click' , '.cpb-form .update_item_editor' , function( event ){ 
			event.preventDefault();
			var form = new CWPB.forms.cpb_form( jQuery( this ) );
			CWPB.ajax.update_editor( form ) 
			})
			
		CWPB.editor.iframe.on_load( CWPB.wrap )
		
		CWPB.editor.iframe.resize()
		
		},
		
		
		
	
	};

// Ajax Handlers ---------------------

CWPB.ajax = {
	
	request_url: ajaxurl,
	
	update_editor: function( form_obj ){
		
		settings = 'action=cpb_update_editor&item_id=' + form_obj.id + '&' + form_obj.get_settings();
		
		jQuery.post(
				CWPB.ajax.request_url,
				settings,
				function( response ){
					
					if ( 'editor'  in response ){
						
						CWPB.wrap.find( '.cpb-item.' + form_obj.id + ' .cpb-item-content' ).html( response.editor ); 
						
					} // end if
					
				},
				'json'
				
			);
		
	}
	
}

CWPB.forms = {
	
	cpb_form: function( ic ){
		
		var f = this;
		
		f.wrap = jQuery( ic ).closest('.cpb-form');
		
		f.id = this.wrap.data('id');
		
		f.get_settings = function(){
			
			data = f.wrap.find('input,select,textarea').serialize();
			
			if ( f.wrap.find('.wp-editor-wrap.tmce-active').length > 0 ){
				
				data += '&current_content=' + encodeURIComponent( tinyMCE.get('_content_' + f.id ).getContent() ); 
				
			} // end if
			
			return data;
			
		}
		
	}
	
}

CWPB.editor = {
	
	iframe: {
		
		on_load: function( w ){
			w.find( 'iframe.cpb-dynamic-editor').each(
			 function(){
				 jQuery( this ).load( function(){ CWPB.editor.iframe.update( jQuery( this ) ); }); 
				
				}
			 )
			}, 
		
		update:function( f ){
			var t = f.siblings('textarea');
			f.contents().find('body > main').html( t.val() );
			CWPB.editor.iframe.size( f );
		},
		
		size:function(f){
			var h = f.contents().find('body > main').height();
			f.height( h );
		},
		
		resize:function(){
			jQuery( window ).resize( function(){
				CWPB.wrap.find( 'iframe.cpb-dynamic-editor' ).each( function(){
					CWPB.editor.iframe.size( jQuery( this ) );
				})
			})
		}
		
		
		
	},
	
}

CWPB.init();

