;(function(context) {

	'use strict';

	function call(callback, args) {
		if ( typeof callback === 'function' ) {
			callback.apply( null, ( args || [] ) );
		}
	}

	function dispatcher(application, route, args) {
		//execute all application
		call( application.init, args );
		call( application[route], args );
	}

	context.dispatcher = dispatcher;

})( window );