/**
 * JavasScript for context popup initialization of the 'Semantic Expressiveness' extension.
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
 * Adds ui popup functionality to all short query results within a specified range of the document.
 * In case the popup would refer to the same page as it is positioned on, it will only show basic
 * information about the short query instead of loading its own content. In that case also the
 * pastCfgFunc callback won't be called and the popups instance is a TitledContextPopup instead of
 * a ShortQueryHover.
 *
 * @since 0.1
 *
 * @param $range jQuery if not set, initialization will be done for the whole document.
 * @param preCfgFunc Function callback which will be called before initialization. The uninitialized
 *        semanticExpressiveness.ui.ShortQueryHover will be passed as first argument.
 * @param pastCfgFunc Function callback which will be called after each successful initialization. The
 *        newly created semanticExpressiveness.ui.ShortQueryHover will be passed as first argument.
 *
 * //TODO use jQuery.Deferred rather than callbacks!
 */
semEx.ui.ShortQueryHover.initialize = function( $range, preCfgFunc, pastCfgFunc ) {
	var queryHover, sqResult;

	$range = $range || $( 'body' );
	preCfgFunc = preCfgFunc || function(){};
	pastCfgFunc = pastCfgFunc || function(){};

	$range
	.find( 'span.shortQuery' )
	.each( function() {
		// create popup but don't initialize yet by not passing any arguments
		queryHover = new semEx.ui.ShortQueryHover();

		// run callback for advanced configuration
		preCfgFunc( queryHover );

		try {
			// in case the short query markup is invalid, initialization will trigger an error!
			sqResult = new semEx.ShortQueryResult( this );

			if( sqResult.isAbstractResult() ) {
				return; // no popup for abstract (failed) queries
			}
		}
		catch( e ) {
			// initialize some simple context popup which at least communicates that this is
			// some kind of invalid short query result
			var errorHover = new semEx.ui.TitledContextPopup( this );
			errorHover.setTitle(
				$( '<strong class="semex-invalid-shortquery-format"/>' )
				.text( 'Invalid short query format!' )
			);
			errorHover.setContent( null );
			$( this ).addClass( 'semex-invalid-shortquery-format' );

			return;
		}

		// init context popup after options are set
		queryHover._init( sqResult );

		if( queryHover.getQueryResult().getSource() === window.wgTitle ) {
			// first-level popup to the same page as it is placed on should only display the basic
			// short query information instead of getting its own pages content via AJAX.
			queryHover = queryHover.initializeSimilarTitledPopup();
		}
		else {
			pastCfgFunc( queryHover );
		}
	} );
};

}( semanticExpressiveness, jQuery ) );
