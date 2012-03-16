/**
 * JavasSript for the 'Semantic Expresiveness' extension.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * 
 * @since 0.1
 * @ingroup Semantic Expresiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
window.semanticExpresiveness = new( function() {
	
	this.log = function( message ) {
		if( typeof mediaWiki === 'undefined' ) {
			if( typeof console !== 'undefined' ) {
				console.log( 'SemEx: ' + message );
			}
		}
		else {
			return mediaWiki.log.call( mediaWiki.log, 'SemEx: ' + message );
		}
	}
	
	// Initialize user interface stuff when ready
	jQuery( function( $ ) {
		// add popup ui functionality to short query results:
		var globalShortQueryInfoCache = new window.semanticExpresiveness.ui.ShortQueryHover.Cache;
		window.semanticExpresiveness.ui.ShortQueryHover.initialize(
			$( 'body' ),
			function( queryHover ) { // configuration per ShortQueryHover element
				queryHover.queryInfoCache = globalShortQueryInfoCache;
			}
		);
	} );
	
} )();