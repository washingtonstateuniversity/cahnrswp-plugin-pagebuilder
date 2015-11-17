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

CWPB.init();

