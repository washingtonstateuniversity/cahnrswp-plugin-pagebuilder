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