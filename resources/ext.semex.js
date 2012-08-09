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
( function( $, mw, undefined ) { "use strict";

	window.semanticExpresiveness = new( function() {} )();

	// make sure rest of the module is loaded first
	mw.loader.using( 'ext.semex', function() {
		// Initialize user interface stuff when dom ready (can be ready before or after module loaded)
		$( document ).ready( function() {

			// add popup ui functionality to short query results:
			var globalShortQueryInfoCache = new window.semanticExpresiveness.ui.ShortQueryHover.Cache;
			window.semanticExpresiveness.ui.ShortQueryHover.initialize(
				$( 'body' ),
				function( queryHover ) { // configuration per ShortQueryHover element
					queryHover.queryInfoCache = globalShortQueryInfoCache;
				}
			);

		} );
	} );

} )( jQuery, mediaWiki );
