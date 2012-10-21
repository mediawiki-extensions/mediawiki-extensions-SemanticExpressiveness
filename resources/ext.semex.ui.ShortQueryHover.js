/**
 * JavasScript for context popup of the 'Semantic Expressiveness' extension.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 *
 * @ingroup Semantic Expressiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
( function( mw, semEx, $, undefined ) {
'use strict';

var PARENT = semEx.ui.TitledContextPopup;

/**
 * Constructor for context Popup container adjusted for 'Semantic Expressiveness' extension needs
 * @constructor
 * @since 0.1
 *
 * @param {semEx.ShortQueryResult} subject
 */
semEx.ui.ShortQueryHover = function( subject ) {
	PARENT.call( this, subject );
};

/*
 * Inherit and overwrite base class members:
 */
semEx.ui.ShortQueryHover.prototype = new PARENT();
$.extend( semEx.ui.ShortQueryHover.prototype, {

	constructor: semEx.ui.ShortQueryHover,

	// overwrite original content:
	_content: $(
		'<div class="semex-shortqueryinfo-loading">' +
		mw.msg( 'semex-shortquery-hover-loading' ) + '...' +
		'</div>'
	),

	/**
	 * Associated short query result which belongs to the popups subject.
	 *
	 * @type: semEx.ShortQueryResult
	 */
	_queryResult: null,

	/**
	 * Whether the query information has been dynamically loaded or taken from cache (if in use).
	 * If that is the case, this._content contains the information.
	 *
	 * @type Boolean
	 */
	_queryInfoLoaded: false,

	_init: function( subject ) {
		if( ! ( subject instanceof semEx.ShortQueryResult ) ) {
			throw new Error(
					'"' + this.constructor +
					'" initialization expects an instance of "ShortQueryResult" as first parameter.'
			);
		}
		if( subject.isAbstractResult() ) {
			throw new Error(
					'"' + this.constructor + '" can not be built from an abstract "ShortQueryResult".'
			);
		}
		this._queryResult = subject;

		this.$package.TitledContextPopup.prototype._init.call( this, subject.getDOMNode() );
		this._buildTitleInfo();
	},

	/**
	 * @see semEx.ui.TitledContextPopup._draw_buildPopup()
	 */
	_draw_buildPopup: function() {
		// call parent function...
		var divPopup = this.$package.TitledContextPopup.prototype._draw_buildPopup.call( this );
		// add class to identify this as short query hover popup
		divPopup.addClass( this.POPUP_CLASS + '-shortqueryhover' );

		return divPopup;
	},

	/**
	 * destroys the short query hover popup and creates and initializes a simple TitledContextPopup
	 * with basic information about the short query target and without AJAX functionality instead.
	 * The new popup will be returned.
	 *
	 * @return semEx.ui.TitledContextPopup
	 */
	initializeSimilarTitledPopup: function() {
		var oldTitle = this.getTitle().clone();
		var subject = this.getSubject().get( 0 );
		this.destroy(); // destroy old popup before initializing any new one!

		// create new popup with same values but with title only:
		var newPopup = new semEx.ui.TitledContextPopup( subject );
		newPopup.setTitle( oldTitle );
		newPopup.setContent( null );

		return newPopup;
	},

	/**
	 * Generates the information displayed as title
	 */
	_buildTitleInfo: function() {
		var sq = this._queryResult;

		var prop = sq.getProperty();
		prop = '<a href="' + mw.util.wikiGetlink( 'Property:' + prop ) + '">' + prop + '</a>';

		var source = sq.getSource();
		source = '<a href="' + mw.util.wikiGetlink( source ) + '">' + source + '</a>';

		// put links into the message:
		this.setTitle(
			$( mw.msg( 'semex-shortquery-title', prop, source ) )
		);
	},

	/**
	 * @see semEx.ui.ContextPopup.setContent()
	 */
	setContent: function( content ) {
		// Initialize short query popups within inserted code:
		if( this.recursiveInitialization
			&& content instanceof jQuery // could be null or string as well!
		) {
			this._doRecursiveInitialization( content );
		}
		this.$package.TitledContextPopup.prototype.setContent.call( this, content )
	},

	/**
	 * @see semEx.ui.ContextPopup.show()
	 */
	show: function() {
		if( ! this.$package.TitledContextPopup.prototype.show.call( this ) ) {
			// no state change, do nothing
			return false;
		}

		if( this.queryInfoCache && this.queryInfoCache.hasInfo( this ) ) {
			// use cache
			this._queryInfoLoaded = true;
			// clone original information so recursively displaying same information won't do crazy things
			this.setContent( this.queryInfoCache.getInfo( this ).clone() );
		}
		else {
			// load information
			this._loadQueryInfo();
		}
		return true;
	},

	/**
	 * Returns the link from where the popup should get its information.
	 *
	 * @return String
	 */
	getQueryInfoSource: function() {
		var source = this._queryResult.getSource();
		// NOTE: adding action=purge will not work if user not logged in (because of "really want to purge?" message)
		return mw.util.wikiGetlink( source ) + '?action=render';
	},

	/**
	 * Requests and extracts the information from the short query results source page.
	 */
	_loadQueryInfo: function() {
		var self = this;
		var dummy = $( '<div/>' );

		var request = this.getQueryInfoSource();
		request += ' ' + this.queryInfoSelector;

		dummy.load(
			request,
			function( rawData, status, jqXHR ) {
				if( jqXHR.status == 0 ) {
					dummy = false;
				}
				self._applyQueryInfo( dummy, rawData, jqXHR );
			}
		);
	},

	/**
	 * Applies the retrieved short query information.
	 * Also handles the caching and callback handling.
	 */
	_applyQueryInfo: function( data, rawData, jqXHR ) {
		if( this.beforeApplyQueryInfo !== null ) {
			data = this.beforeApplyQueryInfo( data )
		}
		if( data === false ) {
			/** @ToDo: right now the message gets treated as if it were the right value, might be
			 *         of interest to notify the cache that this information is just a message
			 */
			data = $(
					'<div class="semex-shortqueryinfo-loading-failed">' +
					mw.msg( 'semex-shortquery-hover-loading-failed' ) + '</div>'
			);
		}

		// cache information if enabled:
		if( this.queryInfoCache ) {
			this.queryInfoCache.addInfo( this, data );
		}
		// apply new content:
		this._queryInfoLoaded = true;
		this.setContent( data.clone() );
	},

	/**
	 * Checks new popup content for further short queries and initializes their ui functionality.
	 *
	 * @param {jQuery} content
	 */
	_doRecursiveInitialization: function( content ) {
		var self = this;
		this.$package.ShortQueryHover.initialize(
			content,
			function( queryHover ) { // configuration per ShortQueryHover element
				queryHover.queryInfoCache = self.queryInfoCache;
				queryHover.queryInfoSelector = self.queryInfoSelector;
				queryHover.recursiveInitialization = true;
			},
			function( queryHover ) {
				if( self.getQueryResult().getSource() !== queryHover.getQueryResult().getSource() ) {
					return queryHover;
				}
				// this popups content would be the same as the parent popups content, so display
				// necessary information only to avoid endless chains of the same popup
				queryHover = queryHover.initializeSimilarTitledPopup();
			}
		);
	},

	/**
	 * Returns the associated Querry Result object
	 *
	 * @returns semEx.ShortQueryResult
	 */
	getQueryResult: function() {
		return this._queryResult;
	},

	///////////
	// EVENTS:
	///////////

	/**
	 * Callback called once after the information from the short queries source page has been loaded,
	 * just before the information will be set as the context popups content. The return value of the
	 * callback allows to return a modified jQuery object which will be set as the popups content
	 * then. If false is returned, it indicates that no information was found.
	 *
	 * @param {jQuery|false} data The original extracted information from the short queries target
	 *        page. If set to false, this implies that loading the information has failed.
	 * @return jQuery|false
	 */
	beforeApplyQueryInfo: null,


	/////////////////
	// CONFIGURABLE:
	/////////////////

	/**
	 * If set to false, there is no cache and each displaying of a short query popup will lead to
	 * loading the required information again. If set to an Cache object, the information will be
	 * stored within. This allows a global cache to share information once retrieved between short
	 * queries which have the same target page.
	 * @type semEx.ui.ShortQueryHover.Cache|false
	 */
	queryInfoCache: false,

	/**
	 * A valid jQuery selector to choose which elements from the short query target page should be
	 * selected as popup content.
	 * @type String
	 */
	queryInfoSelector: '.NavFrame, .freeInfoBox',

	/**
	 * If set to true, any content displayed within the popup will be checked for further short queries which
	 * will then be initialized as well.
	 * @type Boolean
	 */
	recursiveInitialization: true
} );

}( mediaWiki, semanticExpressiveness, jQuery ) );
