//this file not use jquery
;(function($, form) {
	
	'use strict';

	var target = $.querySelector( '.forgetmenot' )
	  , label  = target.firstChild
	;
	
	function setActiveLabel() {
 		label.className = ( label.className.match(/active/) ) ? '' : 'active';
 	}
	
	//in load page
	if ( form.rememberme.checked ) {
		setActiveLabel();
	}
	
	target.addEventListener( 'click', function(event) {
		event.preventDefault();		
		setActiveLabel();
		form.rememberme.checked = !form.rememberme.checked;
 	}); 	

})( document, window.loginform );