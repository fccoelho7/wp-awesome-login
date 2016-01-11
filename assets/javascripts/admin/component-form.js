;(function($, context) {

	'use strict';

	/* 
	 *	CONSTANTES
	 */
	var MESSAGE_HTML = '<div class="[type] render-message"><p><strong>[message]</strong></p></div>',
	    SPINNER_HTML = '<span class="spinner is-active" style="display: block;"></span>'
	;

	var ComponentForm = function(container) {
		this.$el    = container;
		this.submit = this.$el.find( '[data-element="submit"]' );
		this.init();
	};

	ComponentForm.prototype.init = function() {
		this.addEventListener();
	};

	ComponentForm.prototype.addEventListener = function() {
		this.$el
			.on( 'submit', this._onSubmit.bind( this ) )
		;
	};

	ComponentForm.prototype._onSubmit = function(event) {
		event.preventDefault();
		this.send();
	};

	ComponentForm.prototype.beforeSend = function() {
		this.submit.attr( 'disabled', 'disabled' );
		this.setShowSpinner();
	};

	ComponentForm.prototype.send = function() {
		var url    = this.$el.attr( 'action' )
		  , params = this.$el.serialize()
		;

		var ajax = jQuery.ajax({
			url       : url,
			data      : params,
			dataType  : 'json',
			type      : 'POST'
		});

		this.beforeSend();
		ajax.done( this._done.bind( this ) );
		ajax.fail( this._fail.bind( this ) );
	};

	ComponentForm.prototype._done = function(response) {
		this.submit.removeAttr( 'disabled' );
		this.setHideSpinner();
		this.setShowMessage( 'updated', response.message );
	};

	ComponentForm.prototype._fail = function(throwError, status) {
		var response = ( throwError.responseJSON || {} );

		this.submit.removeAttr( 'disabled' );
		this.setHideSpinner();
		this.setShowMessage( 'error', response.message );
	};

	ComponentForm.prototype.setShowMessage = function(type, message) {
		this.$el.prev( '.render-message' ).remove();
		this.$el.before( this.renderMessage( type, message ) );
	};

	ComponentForm.prototype.renderMessage = function(type, message) {
		return MESSAGE_HTML.replace( /\[type\]/, type ).replace( /\[message\]/, message );
	};

	ComponentForm.prototype.setShowSpinner = function(insertion) {
		this.submit.after( SPINNER_HTML );
	};

	ComponentForm.prototype.setHideSpinner = function() {
		this.submit.siblings( '.spinner' ).remove();
	};

	context.ComponentForm = ComponentForm;

})( jQuery, window );