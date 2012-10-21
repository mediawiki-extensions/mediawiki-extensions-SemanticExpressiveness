/**
 * JavasScript for context popup of the 'Semantic Expressiveness' extension.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 *
 * @ingroup Semantic Expressiveness
 *
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
"use strict";

/**
 * Constructor for context popup container which pops up whenever hovering over a defined
 * inline-element.
 * The context popups content can contain further popups. This works as long as the popups
 * POPUP_CLASS and POPUP_STORE_ID are the same. Having several popups assigned to the same
 * element or to sub-elements is very buggy and needs fixing if required for some reason.
 * 
 * The Popups themselves are not part of the the subjects DOM. They are stored within a
 * store for all popups. There were several reasons for this decission, adding the popup
 * to the subjects DOM only worked until a certain point, it practically got too buggy.
 * 
 * Known Bugs (all minor):
 * =======================
 * Opera/IE: Mouse-Events (mostly) don't get triggered for an element when the mouse didn't do
 *           anything but the element dynamically appears/disappeary under the mouse. This might
 *           lead to some bugs we don't have in Firefox.
 *           
 * - Bug #101 (Opera/IE):
 *   When mouse leaves sub-popup, the popup fades out but the mouse goes right back in and then
 *   doesn't moove till some time after the sub-popup is completely gone, the parent popupS will
 *   close as well because the mouse event doesn't get triggered unline the mouse moves again!
 *   This can't just be solved by adding another event to the fading popup since then the mouse
 *   could go to space outside of parent popup without the parent being closed.
 *   
 * - Bug #102 (Opera/IE):
 *   When content changes by using setContent() and the mouse is positioned within the popup and
 *   doesn't move untill the fade timer ran out, the popup will fade even though the mouse is
 *   positioned within. Event only gets triggered when mouse moves/clicks. window.scrollTo()
 *   workaround doesn't really work, it doesn't trigger any mouse event.
 *
 * @constructor
 * @since 0.1
 *
 * @param subject Object the html dom element the context popup should be related to
 */
( function( mw, semEx, $, undefined ) {
'use strict';

var instances = 0;

semEx.ui.ContextPopup = function( subject ) {
	// alternative to any $parent or _super nonsens. At lest this save a few bytes of code
	this.$package = semEx.ui;

	// increase instances count to give IDs to popups
	this._id = instances++;
	this.POPUP_ID = this.POPUP_CLASS + '-' + this._id;

	if( subject !== undefined ) {
		this._init( subject );
	}
};
semEx.ui.ContextPopup.prototype = {
	/**
	 * @const
	 * @enum Number
	 */
	PARTS: {
		SUBJECT: 1,
		POPUP: 2
	},

	/**
	 * @const
	 * @enum Number
	 */
	ORIENTATION: {
		TOP: 1,
		BOTTOM: 2,
		LEFT: 3,
		RIGHT: 4
	},

	TIME_TO_FIRST_NOTICE: 175, // time the mouse has to stay on the subject untill the popup fades in
	TIME_TO_FADE_IN:      125,
	TIME_TO_FADE_OUT:     150, // has to be lower than this.TIME_TO_REENTER time!
	TIME_TO_REENTER:      333, // time after mouse has left popup untill the popup finally fades
	// time after content-update to enter mouse to avoid bug #102 or in case popups position has changed:
	TIME_TO_REENTER_ON_UPDATE: 1750,

	/**
	 * @const
	 * Class which marks a popup within the site html.
	 */
	POPUP_CLASS: 'ui-contextpopup',

	/**
	 * @const
	 * ID of the popup which can be used within DOM or for events.
	 */
	POPUP_ID: null, // POPUP_CLASS + _id (set by constructor)

	/**
	 * @const
	 * ID to identify the popup store where the popups dom will temporarily be stored at.
	 */
	POPUP_STORE_ID: 'ui-contextpopup-store',

	/**
	 * Element the popup is related to.
	 * @type jQuery
	 */
	_subject: null,

	/**
	 * Content which should be displayed by the context popup.
	 * Set this from outside by using setContent()
	 * @type jQuery
	 */
	_content: $( '<div style="font-style:italic;">No information available</div>' ),

	/**
	 * Element which temporarily stores popup dom.
	 * @type jQuery|null
	 */
	_popupStore: null,

	/**
	 * An unique ID for the popup instance to keep track of its events.
	 * @type Number
	 */
	_id: null,

	/**
	 * Contains the popup in case it is visible currently.
	 * @type jQuery|null
	 */
	_popup: null,

	/**
	 * Whether the box is visible currently.
	 * @type Boolean
	 */
	_visible: false,

	/**
	 * Contains the current orientation of the box. If it isn't visible at the moment this
	 * will be set to null.
	 * @type Object|null
	 */
	_orientation: null,

	/**
	 * Equals one of this.PARTS properties to give information about which part of the ui element
	 * was last and still is touched. In case the mouse is outside, this is set to null
	 * In case this.isTouchable is set to false, this won't be set to this.PARTS.POPUP
	 * @type Number|null
	 */
	_touchedPart: null,

	/**
	 * Initialize the context popup and assign it to some html element.
	 * This should normally be called directly by the constructor.
	 */
	_init: function( subject ) {
		if( this._subject !== null ) {
			// initializing twice should never happen, have to destroy first!
			this.destroy();
		}
		this._subject = $( subject );
		this._subject.addClass( this.POPUP_CLASS + '-subject' );
		// kind of bind this object to the element so parents can find their child context popups
		this._subject.data( this.POPUP_CLASS, this );

		// remove title so it won't get in the way of the popup
		this._subject.data( this.POPUP_ID + '-origTitle', this._subject.attr( 'title' ) );
		this._subject.removeAttr( 'title' );

		// register events for associated popup trigger:
		var self = this;
		this._subject.bind(
			'mouseenter.' + this.POPUP_ID,
			function() { self._hoverInFunc( self.PARTS.SUBJECT ); }
		).bind(
			'mouseleave.' + this.POPUP_ID,
			function() { self._hoverOutFunc(); }
		);
	},

	/**
	 * Safely removes the popup and associated events from the DOM
	 */
	destroy: function() {
		if( this._id === null ) {
			return;
		}
		this._subject
		.unbind( '.' + this.POPUP_ID )
		// restore original title which was removed before:
		.attr( 'title', this._subject.data( this.POPUP_ID + '-origTitle' ) );

		this._subject
		.removeData( this.POPUP_ID + '-origTitle' )
		.removeData( this.POPUP_CLASS )
		.removeClass( this.POPUP_CLASS + '-subject' )
		.removeClass( this.POPUP_CLASS + '-subject-active' );

		if( this._popup !== null ) {
			this._popup.empty().remove();
		}
	},

	_hoverInFunc: function( part ) {
		this._touchedPart = part;
		// abord countdown to close()
		this._clearTimeTillFade();

		if( ! this.isVisible() ) {
			// wait short time before opening popup, so fast mouse movments over document won't
			// unintendedly trigger too many popups.
			this._setTimeTillDisplay( this.TIME_TO_FIRST_NOTICE );
		}
	},
	_hoverOutFunc: function() {
		var lastTouched = this._touchedPart;
		this._touchedPart = null;
		if( ! this.isVisible() ) {
			// abord countdown to show()
			this._clearTimeTillDisplay();
		}
		else {
			var activeChild = this.getActiveChild();

			if( lastTouched === this.PARTS.SUBJECT
				&& activeChild !== null
				&& activeChild._touchedPart !== null
			) {
				// we entered, not left some sub-popup, so don't close anything!
				return;
			}

			if( lastTouched !== this.PARTS.SUBJECT ) {
				// consider closing all parents if mouse doesn't touch any in time
				this._setTimeTillFadeParents( this.TIME_TO_REENTER );
			} else {
				// mouse entered parent popup since last touched part is the subject which has to be
				// a part of the parent (if any parent popup exists)
				this._setTimeTillFade( this.TIME_TO_REENTER );
			}
		}
	},

	/**
	 * Sets the time the mouse has to be somewhere where the popup gets triggered to actually display
	 * the popup.
	 *
	 * @param time integer
	 * @param {Function} [callback] Executed after this.show()
	 */
	_setTimeTillDisplay: function( time, callback ) {
		var self = this;
		var timeoutId = setTimeout( function(){
			self.show();
			if( callback !== undefined ) {
				callback();
			}
		}, time );
		$( this ).data( this.POPUP_ID + '-enterTimeoutId', timeoutId );
	},
	_clearTimeTillDisplay: function() {
		clearTimeout( $( this ).data( this.POPUP_ID + '-enterTimeoutId' ) );
	},

	/**
	 * Sets the time left untill the popup will be closed if the mouse doesn't re-activate it somehow.
	 *
	 * @param {Number} time
	 * @param {Function} [callback] Executed after this.close()
	 */
	_setTimeTillFade: function( time, callback ) {
		var self = this;
		var timeoutId = setTimeout( function(){
			self.close();
			if( callback !== undefined ) {
				callback();
			}
		}, time );
		$( this ).data( this.POPUP_ID + '-fadeTimeoutId', timeoutId );
	},
	/**
	 * Same as _setTimeTillFade() but also does the same for all parent-popups.
	 *
	 * // TODO: make the 'time' parameter accept jQuery strings for time, e.g. 'slow'
	 * @param {Number} time
	 * @param {Function} [callback] Executed for each affected popup after this.close()
	 */
	_setTimeTillFadeParents: function( time, callback ) {
		// fade this popup first, then first parent all up the chain
		this._setTimeTillFade( time, callback );
		// do the same for all parents:
		var parent = this.getParent();
		if( parent !== null ){
			parent._setTimeTillFadeParents( time, callback );
		}
	},
	/**
	 * Cancels the timer till popup will fade. This also stops the timer for all parents since a
	 * popup can't exist without its parent.
	 */
	_clearTimeTillFade: function() {
		// do the same for all parents:
		var parent = this.getParent();
		if( parent !== null ){
			parent._clearTimeTillFade();
		}
		// stop parent from fading first^^
		clearTimeout( $( this ).data( this.POPUP_ID + '-fadeTimeoutId' ) );
	},

	/**
	 * Returns the element where the popup elements will temporarily be stored. If no store
	 * exists yet, this will create one.
	 *
	 * @return jQuery
	 */
	getPopupStore: function() {
		if( ! this._popupStore ) {
			var store = $( '#' + this.POPUP_STORE_ID );
			if( store.length == 0 ) {
				// no store yet, create it
				var store = $( '<div/>', {
					'id': this.POPUP_STORE_ID
				} );
				store.appendTo( '#bodyContent' ); // place for MWs content, to have proper css for popups
			}
			this._popupStore = store;
		}
		return this._popupStore;
	},

	/**
	 * Returns the popups current orientation or null if its not displayed right now.
	 * @return integer|null
	 */
	getOrientation: function() {
		return this._orientation;
	},

	/**
	 * Function for (re)rendering the box at the ideal place, taking the boxes size and current
	 * position of the subject element relative to the current viewport in acount. If the box is not
	 * visible right now, this will not activate the box, use the show() function instead.
	 *
	 * @param {Object} orientation containing values from this.ORIENTATION for the properties 'horizontal'
	 *        and 'vertical' if any specific orientation should be forced. (optional)
	 * @returns Boolean whether any (re)drawing did happen.
	 */
	draw: function( orientation ) {
		if( ! this.isVisible ) {
			// nothing to draw!
			return false;
		}

		var isUpdate = this._popup !== null; // is this a update of the content while popup is visible?

		// append popup here so css is taken into account for POSITIONING calculations
		var divPopup = this._draw_buildPopup();
		if( isUpdate ) {
			// detach popup so we can be 100% sure that the mouseenter event gets fired in case
			// re-positioning doesn't change position and mouse is still within popup
			divPopup.detach();
		}
		divPopup.appendTo( this.getPopupStore() );

		if( orientation === undefined ) {
			// calculate popup box positioning:
			orientation = this.getIdealAlignment( divPopup );
		}

		this._popup = divPopup;
		this._orientation = orientation;

		// POSITIONING of the popup
		this._draw_doPositioning();

		if( isUpdate // if we update the popups content while it is still visible
			&& this._touchedPart != this.PARTS.SUBJECT // and mouse possibly within popup
		) {
			// will be triggered even if mouse still within popup since we detached the popup before.
			// in case the mouse is outside the new popup, this leaves us with enough time to enter the popup.
			this._setTimeTillFadeParents( this.TIME_TO_REENTER_ON_UPDATE );
			self._touchedPart = null; // parents should be set to null already
			//this._draw_contentUpdateCleanup( divPopup, orientation );

			// FIXME: no solution for Bug #102 yet. (window.scrollTo() no solution, doesn't trigger mouse event)
		}

		this._popup.hide().fadeIn( this.TIME_TO_FADE_IN );

		return true;
	},

	/**
	 * Sub-routine of this.draw() to do the positioning so the popup will appear relative
	 * to the triggering subject element.
	 */
	_draw_doPositioning: function() {
		// Consider that the popup store might not be at position 0;0 !
		var popupStoreOffset = this.getPopupStore().offset(),
			divPopup = this._popup,
		// get pointer to add ontop/onbottom class for it as well... IE6 support once again...
			divPointer = divPopup.children( '.' + this.POPUP_CLASS + '-pointer' ),
			yClassSuffix;

		// calc Y:
		var posY = this._subject.offset().top - popupStoreOffset.top;
		if( this._orientation.vertical === this.ORIENTATION.TOP ) { // get Y
			// above subject
			yClassSuffix = '-ontop';
			divPopup.addClass( this.POPUP_CLASS + '-ontop' );
			divPointer.addClass( this.POPUP_CLASS + '-pointer-ontop' );
			posY -= divPopup.outerHeight(); // height is different after classes are applied!
		} else {
			// underneath subject
			yClassSuffix = '-onbottom';
			divPopup.addClass( this.POPUP_CLASS + '-onbottom' );
			divPointer.addClass( this.POPUP_CLASS + '-pointer-onbottom' );
			posY += this._subject.outerHeight();
		}

		// calc X:
		var posX = -popupStoreOffset.left;

		if( this._orientation.horizontal === this.ORIENTATION.LEFT ) {
			// expand to left
			divPopup.addClass( this.POPUP_CLASS + '-fromleft' );
			posX += this._subject.offset().left + this._subject.outerWidth() - divPopup.outerWidth() // <<left
		} else {
			// expand to right
			divPopup.addClass( this.POPUP_CLASS + '-fromright' );
			posX += this._subject.offset().left; // right>>
		}

		// if popup would leave viewport (-10px tollerance), move it to the left until it fits...
		var popupEndX = popupStoreOffset.left + posX + divPopup.outerWidth() + 10,
			viewPortEndeX = $( window ).scrollLeft() + $( window ).width(),
			xOverflow = popupEndX - viewPortEndeX,
			origPosX = posX,
			xPointerMarging = 0;

		if( xOverflow > 0 ) {
			posX -= xOverflow;
			xPointerMarging = xOverflow;
		}
		if( posX + popupStoreOffset.left <= 10 ) {
			// ... except this would put the popup too far left
			posX = -popupStoreOffset.left + 10; // 10px tollerance
			xOverflow = origPosX - posX;
		}

		// apply calculated coordinates
		divPopup.css( 'top', posY + 'px' );
		divPopup.css( 'left', posX + 'px' );

		// get popups pointer for adjustments
		divPointer = divPopup.children( '.' + this.POPUP_CLASS + '-pointer' );

		// if pointer goes over the right edge, move it to the edge and cut it nicely.
		// this basically happens when popup is cut off on the right side by the viewport.
		var marginSide = 'left',
			pointerEndX = xPointerMarging + divPointer.position().left + divPointer.width();

		if( pointerEndX > divPopup.outerWidth() ) {
			var divBox = divPopup.children( '.' + this.POPUP_CLASS + '-box' );

			divPointer.css( 'border-right', divBox.css( 'border-right' ) );
			divPointer.css( 'width', divPointer.width() / 2 );

			if( this._orientation.horizontal === this.ORIENTATION.RIGHT ) {
				xPointerMarging = divPopup.outerWidth() - ( divPointer.position().left + divPointer.outerWidth() );
				xPointerMarging += 'px';
			} else {
				xPointerMarging = '-' + divPointer.css( 'right' );
				marginSide = 'right';
			}
		}

		divPointer.css( 'margin-' + marginSide, xPointerMarging );
	},

	/**
	 * Called by this.draw() when the popups html for the dom has to be built.
	 */
	_draw_buildPopup: function() {
		/*
		 * Inner div necessary because we need outer divs marging for the relative positioning.
		 * The inner divs marging is just good for some space between subject and box.
		 */
		var divPopup; // outer div (for position marging/positioning)

		if( this._popup === null ) {
			divPopup = $( '<div/>' ); 

			// if mouse can touch te popup:
			if( this.isTouchable ) {
				// when moving mouse from popup trigger element into popup, don't destroy popup!
				var self = this;
				divPopup.hover(
					function() { self._hoverInFunc( self.PARTS.POPUP ); },
					function() { self._hoverOutFunc(); }
				);
			}
		}
		else {
			// just clean-up the existing popup for re-using it
			divPopup = this._draw_existingPopupCleanup( this._popup );
		}

		divPopup
		.addClass( this.POPUP_CLASS + ' ' + this.POPUP_ID )
		.data( this.POPUP_CLASS, this ); // bind this object to element so child popups can get its parent

		var divContent = $( '<div/>', { // inner div (for shadow and box shape + style marging)
			'class': this.POPUP_CLASS + '-box'
		} );
		var divPointer = $( '<div/>', { // div for arrow
			'class': this.POPUP_CLASS + '-pointer'
		} );

		divContent
		.append( this._content ) // actual content
		.appendTo( divPopup );

		divPointer.appendTo( divPopup );

		return divPopup;
	},

	/**
	 * Sub-Function of this._draw_buildPopup(), gets called when this.setContent() changes the content
	 * which leads to a re-draw. In that case we don't destroy the original popup but rather clean up
	 * what's necessary on the old one and re-use it.
	 * Basically this removes all children, css-styles and classes.
	 *
	 * @param {jQuery} divPopup the old popup
	 * @return jQuery the cleaned-up popup
	 */
	_draw_existingPopupCleanup: function( divPopup ) {
		divPopup.children().empty().remove();
		return divPopup
		.removeAttr( 'class' )
		.removeAttr( 'style' );
	},

	/**
	 * Called whenever the popups content gets updated while the popup is still being displayed.
	 * In this case the popup size might change and perhaps it will be re-aligned which could
	 * lead to the mouse suddenly being outside of the popup. In this case some cleanup has to be
	 * done so the popup will fade out if the mouse doesn't re-enter soon.
	 * 
	 * FIXME: IE 6+7 & Opera Bug: In case mouse is positioned within old popup content and after new
	 *        content is applied the mouse doesn't move untill the fade timeout is out, the mosue
	 *        event will not be triggered and therefore the popup will be closed.
	 *        > This could be fixed with some permanent 'mousemove' event caching mouse position.
	 *        > Might be worse performance but less complicated than current handling.
	 */
	/*
	_draw_contentUpdateCleanup: function( newPopup, newOrientation ) {
		this._popup.unbind();
		this._popup.empty().remove();

		if( this._touchedPart != this.PARTS.SUBJECT ) {
			if( newOrientation.vertical != this._orientation.vertical ) {
				// popup now displayed on the other side, mouse must be outside now!
				this._touchedPart = null;
				this._setTimeTillFade( 1250 );
			}
			else {
				// mouse could still be inside, no certainty!
				var self = this;
				var mouseMoveUnbind = function() {
					self._subject.unbind( '.ContextPopupUpdateCleanup' );
					newPopup.unbind( '.ContextPopupUpdateCleanup' );
				};
				var mouseMoveHandler = function( part ) {
					return function() {
						self._touchedPart = part;
						self._clearTimeTillFade();
						mouseMoveUnbind();
					}
				};
				// destroy the popup in case the mouse is outside the popup now (due to positioning/size changes)...
				this._setTimeTillFade( 1500, function() {
					self._touchedPart = null;
					mouseMoveUnbind();
				} );

				// ...unless we're still within popup-triggering territory (indicated by mouse-movement)
				this._subject.bind( 'mousemove.ContextPopupUpdateCleanup',  mouseMoveHandler( this.PARTS.SUBJECT ) );
				newPopup.bind( 'mousemove.ContextPopupUpdateCleanup', mouseMoveHandler( this.PARTS.POPUP ) );
			}
		}
	},
	*/

	/**
	 * Calculates whether the information would best be displayed at top or bottom with left or
	 * right orientation relative to the element, considering the current viewport.
	 *
	 * @param {jQuery} popup DOM element required for additional positioning calculation
	 * @return Object containing the properties 'horizontal' and 'vertical'
	 */
	getIdealAlignment: function( popup ) {
		var measurement = this.$package.InlineMeasurer.measure( this._subject );

		// calculate viewport offset at top and bottom
		var spaceT = this._subject.offset().top - $( window ).scrollTop();
		var spaceB = $( window ).height() - spaceT - this._subject.outerHeight();

		// calculate viewport offset left and right
		var spaceL = this._subject.offset().left - $( window ).scrollLeft();
		var spaceR = $( window ).width() - spaceL - this._subject.outerWidth();

		var result = new Object();

		result.vertical = ( // if enough space, always expand downwards
				spaceT <= spaceB
				|| spaceB > ( popup.outerHeight() + 10 )
				|| this._subject.offset().top < ( popup.outerHeight() + 5 ) // never TOP if it would be cut off!
			)
			? this.ORIENTATION.BOTTOM
			: this.ORIENTATION.TOP;

		result.horizontal = ( // if enough space, always expand to right if subject isn't a multi-line inline-element
				!( !measurement.isOneLiner && result.vertical === this.ORIENTATION.TOP )
				&& (
					spaceL <= spaceR
					|| ( spaceR + this._subject.outerWidth() ) > ( popup.outerWidth() + 10 )
					|| this._subject.offset().left < ( popup.outerWidth() + 5 ) // not LEFT if it would be cut off!
					|| ( !measurement.isOneLiner && result.vertical === this.ORIENTATION.BOTTOM )
				)
			)
			? this.ORIENTATION.RIGHT
			: this.ORIENTATION.LEFT;

		return result;
	},

	/**
	 * This will show the box if it is in a hidden state at the time.
	 * This will trigger the 'beforeShow' callback event.
	 *
	 * @return Boolean whether visibility state has changed.
	 */
	show: function() {
		if( this._visible ) {
			return false;
		}
		if( this.beforeShow !== null && !this.beforeShow() ) { // callback
			return false;
		}
		this._subject.addClass( this.POPUP_CLASS + '-subject-active' );
		this._visible = true;
		this.draw();

		return true;
	},

	/**
	 * This will close the box if it is in an visible state at the moment.
	 * This will trigger the 'beforeClose' callback event.
	 *
	 * @return Boolean whether visibility state has changed.
	 */
	close: function() {
		if( ! this._visible ) {
			return false;
		}
		if( this.beforeClose != null && !this.beforeClose ) { // callback
			return false;
		}
		// close child popups which might still be open for some reasons:
		var openChild = this.getActiveChild();
		if( openChild !== null ) {
			openChild.close();
		}
		// effect for fade out but possibility to get a new popup at the same time alreaddy:
		var popup = this._popup;
		popup.unbind(); // unbind events so we can't get the popup back while its fading out
		this._popup.fadeOut( this.TIME_TO_FADE_OUT, function() {
			// Bug #101
			popup.empty().remove();
		} );
		// remove popup from store:
		this._popup = null;
		this._visible = false;
		this._orientation = null;
		this._touchedPart = null;
		this._subject.removeClass( this.POPUP_CLASS + '-subject-active' );
		return true;
	},

	/**
	 * Whether the popup is visible at the moment
	 *
	 * @returns Boolean
	 */
	isVisible: function() {
		return this._visible;
	},

	/**
	 * Returns the element which triggers the popup.
	 *
	 * @return jQuery
	 */
	getSubject: function() {
		return this._subject;
	},

	/**
	 * Returns the content which should be displayed within the popup container.
	 *
	 * @return jQuery
	 */
	getContent: function() {
		return this._content;
	},

	/**
	 * Allows to set the content of the box. The contents dimensions will define the boxes size
	 * in case the default css rules are not altered.
	 *
	 * @param {jQuery|String} content
	 */
	setContent: function( content ) {
		if( content === undefined ) {
			content = '';
		}
		if( content instanceof String ) {
			content = $( document.createTextNode( content ) );
		}
		this._content = content;
		if( this._visible ) {
			// refresh if content has changed
			this.draw();
		}
	},

	/**
	 * Returns the direct parent popup if any exists. If this is a top-level popup already, null will
	 * be returned. Consider that as long as the popup has its content hidden, there are no children
	 * because the parents subject would not be existent if hidden. Therefore the parent can only be
	 * returned as long as the parents content still exists within the DOM.
	 *
	 * @return semEx.ui.ContextPopup|null
	 */
	getParent: function() {
		// check whether the subject has a popup container as one of its parents
		var parent = this._subject.parents( '.' + this.POPUP_CLASS );
		if( parent.length === 0 ) {
			return null;
		}
		// the popup container should hold a data-'link' to its popup content instance
		var parentObj = parent.eq(0).data( this.POPUP_CLASS );
		if( parentObj === undefined ) {
			return null;
		}
		return parentObj;
	},

	/**
	 * Returns all sub-popups within the popups content.
	 *
	 * @return Array contains the ContextPopup child instances
	 */
	getChildren: function() {
		var ChildPopups = [];
		if( !( this._content instanceof jQuery ) ) {
			return ChildPopups;
		}
		var childSubjects = this._content.find( '.' + this.POPUP_CLASS + '-subject' );
		var i = 0;
		var self = this;
		childSubjects.each( function() {
			// check for potential subjects data which should be a ContextPopup instance
			var childObj = $( this ).data( self.POPUP_CLASS );
			if( childObj !== undefined ) {
				ChildPopups[i++] = childObj;
			}
		} );
		return ChildPopups;
	},

	/**
	 * Returns the direct active sub-popup. There can only be one direct active sub-popup
	 * at a time. In case no sub-popup exists or none is active, null will be returned.
	 *
	 * @return semEx.ui.ContextPopup|null
	 */
	getActiveChild: function() {
		var childPopups = this.getChildren();
		for( var i = 0; i < childPopups.length; i++ ) {
			var child = childPopups[i];
			if( child.isVisible() ) {
				// mouse entered sub-popup, don't make this (parent) popup disappear!
				return child;
			}
		}
		return null;
	},

	///////////
	// EVENTS:
	///////////

	/**
	 * Callback which is being called before the popup is changing its state from closed to displayed.
	 * If this returns false, showing the popup will be prevented.
	 */
	beforeShow: null,

	/**
	 * Callback which is being called before the popup is changing its state from displayed to closed.
	 * If this returns false, closing the popup will be prevented.
	 */
	beforeClose: null,

	/////////////////
	// CONFIGURABLE:
	/////////////////

	/**
	 * Allows to configurate whether or not the popup will still stay when the mouse moves
	 * from subject object into the popup.
	 * @type Boolean
	 */
	isTouchable: true
};

}( mediaWiki, semanticExpressiveness, jQuery ) );
