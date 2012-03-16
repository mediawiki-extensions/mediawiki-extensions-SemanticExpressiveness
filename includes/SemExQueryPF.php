<?php

/**
 * Class for '?' short query parser function
 * 
 * @since 0.1
 * 
 * @file SemExQueryPF.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */

class SemExQueryPF {
	
	public static function render( Parser &$parser ) {
		global $smwgQEnabled, $smwgIQRunningNumber;
		
		if ( $smwgQEnabled ) {
			$smwgIQRunningNumber++;
			
			$params = func_get_args();
			array_shift( $params ); // remove $parser
			
			$query = SemExShortQuery::newFromPFParams(
					$params,
					array( 'property', Validator::PARAM_UNNAMED )
			);
			$options = SemExShortQueryOutputOptions::newFromPFParams( $params );
			
			if( ! $query || ! $options ) {
				// @ToDo: real error message (anyway, in what case can this happen?)
				return 'FALSE';
			}
			
			$result = SemExShortQueryProcessor::getResultFromQuery( $parser, $query, $options );			
		} else {
			$result = smwfEncodeMessages( array( wfMsgForContent( 'smw_iq_disabled' ) ) );
		}
		
		 return $result;
	}
	
	/**
	 * Returns a description of all allowed function Parameters.
	 * 
	 * @return array
	 */
	public static function getParameters() {
		$params = array();
		
		$params['intro']   = new Parameter( 'intro' );
		$params['outro']   = new Parameter( 'outro' );
		$params['default'] = new Parameter( 'default' );
		
		// add function parameters describing the querry and its options:
		$params = array_merge(
			$params,
			SemExShortQuery::getPFParams(),
			SemExShortQueryOutputOptions::getPFParams()
		);
		
		return $params;
	}
}
