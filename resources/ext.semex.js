/**
 * JavasScript for the 'Semantic Expressiveness' extension.
 * @see https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * 
 * @since 0.1
 * @ingroup Semantic Expressiveness
 * 
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz at web dot de >
 */
var semanticExpressiveness = new ( function( mw, $, undefined ) {
	'use strict';

	// make sure rest of the module is loaded first
	mw.loader.using( 'ext.semex', function() {
		// Initialize user interface stuff when dom ready (can be ready before or after module loaded)
		$( function() {

			// add popup ui functionality to short query results:
			var globalShortQueryInfoCache = new semanticExpressiveness.ui.ShortQueryHover.Cache();
			semanticExpressiveness.ui.ShortQueryHover.initialize(
				$( 'body' ),
				function( queryHover ) { // configuration per ShortQueryHover element
					queryHover.queryInfoCache = globalShortQueryInfoCache;
				}
			);

		} );
	} );

} )( mediaWiki, jQuery );