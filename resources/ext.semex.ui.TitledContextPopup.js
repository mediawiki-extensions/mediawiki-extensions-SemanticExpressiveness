/**
 * JavasSript for context popup of the 'Semantic Expresiveness' extension.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * 
 * @since 0.1
 * @ingroup Semantic Expresiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
"use strict";

/**
 * Constructor for context popup container which allows to display some title section
 * next to the subject but within the context popup box. This means if the popup gets
 * displayed above its triggering subject, the title will be displayed at the popups
 * bottom, if the popup will be beyond the subject, the title will be displayed on top.
 */
window.semanticExpresiveness.ui.TitledContextPopup = function( subject ){
	window.semanticExpresiveness.ui.ContextPopup.call( this, subject );
};

/*
 * Inherit and overwrite base class members:
 */
window.semanticExpresiveness.ui.TitledContextPopup.prototype = new window.semanticExpresiveness.ui.ContextPopup();
$.extend( window.semanticExpresiveness.ui.TitledContextPopup.prototype, {
	
	/**
	 * Title which should be displayed next to the popups content.
	 * @var jQuery
	 */
	_title: null,
	
	/**
	 * @see semanticExpresiveness.ui.TitledContextPopup.POPUP_STORE_ID
	 */
	//POPUP_STORE_ID: 'ui-titledcontextpopup-store',
	
	/**
	 * Allows to set the content of the title.
	 * @param $content jQuery|String
	 */
	setTitle: function( content ) {
		if( typeof content == 'undefined' ) {
			content = null;
		}
		if( content instanceof String ) {
			content = $( document.createTextNode( content ) );
		}
		this._title = content;
	},
	
	/**
	 * Returns the content which should be displayed as title within the popup container.
	 * @return jQuery
	 */
	getTitle: function() {
		return this._title;
	},
	
	/**
	 * @see semanticExpresiveness.ui.ContextPopup._draw_buildPopup()
	 */
	_draw_buildPopup: function() {
		// call parent function...
		var divPopup = this.$package.ContextPopup.prototype._draw_buildPopup.call( this );
		
		if( this._title === null ) {
			// TitleContextPopup without title set is not much more than normal ContextPopup
			return divPopup;
		}
		
		var boxClass = this.POPUP_CLASS + '-box';
		
		// ...to get the content part from DOM
		var divContent = divPopup.children( '.' + boxClass );
		divContent
		.addClass( this.POPUP_CLASS + '-titlepopup-content' )
		.removeClass( boxClass );
		
		var divContainer = $( '<div/>', { // parent for title and content, replaces old content node
			'class': this.POPUP_CLASS + '-titlepopup-container ' + boxClass
		} );
		var divTitle = $( '<div/>', { // title, next to content node
			'class': this.POPUP_CLASS + '-titlepopup-title'
		} );
		divTitle.append( this._title );
		
		// set content div next to title div within a new container:
		divContainer.append( divTitle );
		if( this._content !== null ) {
			divContainer.append( divContent );
		} else {
			// Title popup can have empty content (if title is set)
			divContent.empty().remove();
		}
		divPopup
		.append( divContainer )
		.addClass( this.POPUP_CLASS + '-titlepopup' );
		
		return divPopup;
	},
	
	/**
	 * @see semanticExpresiveness.ui.TitledContextPopup._draw_doPositioning()
	 */
	_draw_doPositioning: function() {
		// we have positioned the title on top before, check whether it should be at the bottom:
		if( this.getTitlePosition() === this.ORIENTATION.BOTTOM ) {
			// find titles div and put it at the end
			var divTitle = this._popup.find( '.' + this.POPUP_CLASS + '-titlepopup-title' );
			divTitle.parent().append( divTitle.detach() );
		}
		this.$package.ContextPopup.prototype._draw_doPositioning.call( this );
	},
	
	/**
	 * Returns the designated position of the title part of the popup. If the popup is not visible
	 * at the moment or no title is set, null will be returned.
	 * @return integer|null
	 */
	getTitlePosition: function() {
		if( this._orientation == null || this._title == null ) {
			return null;
		}
		if( this._orientation.vertical === this.ORIENTATION.TOP ) {
			return this.ORIENTATION.BOTTOM;
		} else {
			return this.ORIENTATION.TOP;
		}
	}
	
} );