;(function($, context) {

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
