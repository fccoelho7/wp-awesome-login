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

})( window );;;(function($, context) {

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

})( jQuery, window );;;(function($, context) {

	'use strict';

	var FieldImage = function(container) {
		this.container = container;
		this.src       = this.container.data( 'attr-image-src' );
		this.empty     = this.container.data( 'attr-image-empty' );
		this.position  = this.container.data( 'attr-image-position' );
		this.$el       = jQuery( '<img>' );
		this.on        = jQuery.proxy( this.$el, 'on' );
		this.fire      = jQuery.proxy( this.$el, 'trigger' );
		this.inserted  = false;
		this.create();
	};

	FieldImage.prototype.create = function() {
		if ( !this.src && !this.empty ) {
			return;
		}

		this.$el
			.addClass( 'image-create-component' )
			.attr( 'src', this.getSrc() )
		;

		if ( !this.inserted ) {
			this._insert();
		}
	};

	FieldImage.prototype.getSrc = function() {
		return ( this.src || this.empty );
	};

	FieldImage.prototype.setEmptyImage = function() {
		if ( this.empty ) {
			this.$el.attr( 'src', this.empty );
			return;
		}

		this.$el.hide();
	};

	FieldImage.prototype._insert = function() {
		this.inserted = true;
		this.container[ ( this.position || 'before' ) ]( this.$el );
		this.container.addClass( 'created-image' );
	};

	FieldImage.prototype.reload = function(src) {
		this.src = src;
		this.create();
		this.$el.show();
	};

	/*Hidden*/
	var FieldHidden = function(container) {
		this.container = container;
		this.value     = this.container.data( 'attr-hidden-value' );
		this.name      = this.container.data( 'attr-hidden-name' );
		this.type      = ( this.container.data( 'attr-type' ) || 'id' );
		this.$el       = null;
		this.create();
	};

	FieldHidden.prototype.create = function() {
		this.$el = jQuery( '<input>', {
			type  : 'hidden',
			class : 'hidden-create-component',
			value : this.value,
			name  : this.name
		});

		this.container.before( this.$el );
	};

	FieldHidden.prototype.val = function(value) {
		this.value = value;
		this.$el.val( value );
	};

	FieldHidden.prototype.isEmpty = function() {
		return ( !this.value );
	};

	var ComponentUpload = function(container) {
		this.container        = container;
		this.changeButtonText = this.container.data( 'attr-button-text' );
		this.removeButtonText = this.container.data( 'attr-remove-text' );
		this.image            = null;
		this.hidden           = null;
		this.buttonRemove     = null;
		this.init();
	};

	ComponentUpload.prototype.init = function() {
		this._createElements();
		this._createEmptyButton();
		this.addEventListener();
	};

	ComponentUpload.prototype._defineButtonText = function() {
		( window._wpMediaViewsL10n || {} ).insertIntoPost = ( this.changeButtonText || 'inserir' );
	};

	ComponentUpload.prototype._createElements = function() {
		this.image  = new FieldImage( this.container );
		this.hidden = new FieldHidden( this.container );
	};

	ComponentUpload.prototype._createEmptyButton = function() {
		if ( this.hidden.isEmpty() ) {
			return;
		}

		this.container.hide();

		if ( this.isExistButtonRemove() ) {
			this.getButtonRemove().show();
			return;
		}

		this.getButtonRemove()
			.on( 'click', this._onClickButtonDelete.bind( this ) )
			.insertAfter( this.container )
		;
	};

	ComponentUpload.prototype.isExistButtonRemove = function() {
		return this.container.next( '.button-remove' ).length;
	};

	ComponentUpload.prototype.getButtonRemove = function() {
		var text = ( this.removeButtonText || 'Remover imagem destacada' );
		var html = '<a href="javascript:void(0);" class="button-remove"></a>';

		if ( !this.buttonRemove ) {
			this.buttonRemove = jQuery( html ).text( text );
		}

		return this.buttonRemove;
	};

	ComponentUpload.prototype.addEventListener = function() {
		this.container.on( 'click', this._onClickButtonUpload.bind( this ) );
		this.image.on( 'click', this._onClickButtonUpload.bind( this ) );
	};

	ComponentUpload.prototype.reset = function() {
		this.hidden.val( '' );
		this.image.setEmptyImage();
		this.buttonRemove.hide();
		this.container.show();
	};

	ComponentUpload.prototype._onClickButtonUpload = function(event) {
		this._defineButtonText();
		wp.media.editor.send.attachment = this._onAfterAttachmentAction();
    	wp.media.editor.open();
	};

	ComponentUpload.prototype._onClickButtonDelete = function() {
		this.reset();
	};

	ComponentUpload.prototype._onAfterAttachmentAction = function() {
		var self = this;
		var type = ( this.hidden.type || 'url' );

		return function(props, attachment) {
			self.image.reload( attachment.url );
			self.hidden.val( attachment[type] );
			self._createEmptyButton();
		};
	};

	context.ComponentUpload = ComponentUpload;

})( jQuery, window );
;jQuery(function($) {

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