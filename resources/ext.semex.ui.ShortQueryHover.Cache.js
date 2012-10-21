/**
 * JavasScript for context popup of the 'Semantic Expressiveness' extension.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 *
 * @ingroup Semantic Expressiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
( function( semEx, $, undefined ) {
'use strict';

/**
 * Constructor for AJAX short query information cache which can be used to share retrieved
 * information between several ShortQueryHover elements.
 * @constructor
 * @since 0.1
 */
semEx.ui.ShortQueryHover.Cache = function() {
	/*
	 * Internal store for cached elements
	 * @type Object
	 */
	this._cache = {};
};

semEx.ui.ShortQueryHover.Cache.prototype = {

	_resolveContext: function( context ) {
		if( context instanceof String ) {
			return context;
		}
		return context.getQueryResult().getSource();
	},

	/**
	 * Returns an information which has been cached before.
	 *
	 * @param {semEx.ui.ShortQueryHover|String} context
	 * @return mixed
	 */
	getInfo: function( context ) {
		return this._cache[ '__' + this._resolveContext( context ) ];
	},

	/*
	 * Cache something.
	 *
	 * @param {semEx.ui.ShortQueryHover|String} context
	 * @param {mixed} value Information to cache
	 */
	addInfo: function( context, value ) {
		this._cache[ '__' + this._resolveContext( context ) ] = value;
	},

	/*
	 * Removes one information from the cache.
	 *
	 * @param {semEx.ui.ShortQueryHover|String} context
	 * @return Boolean Whether the information existed
	 */
	removeInfo: function( context ) {
		var key = this._resolveContext( context );
		if( this.hasInfo( key ) ) {
			delete this._cache[ '__' + key ];
			return true;
		}
		return false;
	},

	/*
	 * Returns whether the information is cached already.
	 *
	 * @param {semEx.ui.ShortQueryHover|String} context
	 * @return Boolean
	 */
	hasInfo: function( context ) {
		return this._cache.hasOwnProperty( '__' + this._resolveContext( context ) );
	},

	/*
	 * Removes all cached information from the cache.
	 */
	clear: function() {
		this._cache = {};
	}
};

}( semanticExpressiveness, jQuery ) );
