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
 * Constructor for AJAX short query information cache which can be used to share retrieved
 * information between several ShortQueryHover elements.
 */
window.semanticExpresiveness.ui.ShortQueryHover.Cache = function(){
}

window.semanticExpresiveness.ui.ShortQueryHover.Cache.prototype = {
	/*
	 * Internal store for cached elements
	 */
	_cache: {}, // TODO: probably should be initialized in constructor/init
	
	_resolveContext: function( context ) {
		if( context instanceof String ) {
			return context;
		}
		return context.getQueryResult().getSource();
	},
	
	/**
	 * Returns an information which has been cached before.
	 * @param context semanticExpresiveness.ui.ShortQueryHover|string
	 * @return mixed
	 */
	getInfo: function( context ) {
		return this._cache[ '__' + this._resolveContext( context ) ];
	},
	
	/*
	 * Cache something.
	 * @param context semanticExpresiveness.ui.ShortQueryHover|string
	 * @param value mixed information to cache
	 */
	addInfo: function( context, value ) {
		this._cache[ '__' + this._resolveContext( context ) ] = value;
	},
	
	/*
	 * Removes one information from the cache.
	 * @param context semanticExpresiveness.ui.ShortQueryHover|string
	 * @return boolean whether the information existed
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
	 * @param context semanticExpresiveness.ui.ShortQueryHover|string
	 * @return boolean
	 */
	hasInfo: function( context ) {
		return this._cache.hasOwnProperty( '__' + this._resolveContext( context ) )
	},
	
	/*
	 * Removes all cached informations from the cache.
	 */	
	clear: function() {
		this._cache = new Object();
	}
};
