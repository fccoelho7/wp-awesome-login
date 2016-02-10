jQuery(function($) {

	'use strict';

	var App = {
		init : function() {

		},
		'appearance_page_wpal-settings-theme' : function() {
			var form   = new ComponentForm( $( '[data-component="form"]' ) )
			  , upload = new ComponentUpload( $( '[data-component="upload"]' ) )
			;

			App.setColorPicker();
		},
		setColorPicker : function() {
			$( '[data-component="color-picker"]' ).each(function(index, element) {
				element = $( element );
				( !element.val() && element.val( element.data( 'default-color' ) ) );

				//set plugins color
				element.wpColorPicker();
			});
		}
	};

	dispatcher( App, window.pagenow, [ $( 'body' ) ] );

});