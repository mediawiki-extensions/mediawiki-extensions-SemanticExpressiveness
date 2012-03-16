<?php

/**
 * Factory class to generate and execute 'Semantic Expressiveness' short queries and return their
 * result as SemExShortQueryResult object or in directly in a serialized form.
 * 
 * @since 0.1
 * 
 * @file SemExShortQueryProcessor.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class SemExShortQueryProcessor {
	
	/**
	 * Processes a 'Semantic Expressiveness' Short Qery as given by an array of parameters as usually
	 * given by the '?' and '?!' parser functions.
	 * 
	 * @param parser Parser
	 * @param rawParams array unprocessed parameters
	 * 
	 * @return array
	 */
	public static function getResultFromFunctionParams( Parser $parser, array $rawParams ) {
		
		$query = SemExShortQuery::newFromPFParams( $rawParams );
		$options = SemExShortQueryOutputOptions::newFromPFParams( $rawParams );
		
		// @ToDo: Check for validation errors at some point
		
		return self::getResultFromQuery( $parser, $query, $options );
	}
	
	/**
	 * Processes a 'Semantic Expressiveness' Short Query and returns the result which can contain
	 * either a valid result or an abstract value.
	 * Optionally there can be a SemExShortQueryResultOptions object passed to retain a certain
	 * kind of output from the short query.
	 *
	 * @param Parser $parser
	 * @param SemExShortQuery $query
	 * @param SemExShortQueryOutputOptions $options
	 * 
	 * @return SemExShortQueryResult|String
	 */
	public static function getResultFromQuery(
			Parser $parser,
			SemExShortQuery $query,
			$options = null
	) {
		$result = new SemExShortQueryResult( $query, $parser );
		
		if( $options !== null ) {
			return $result->getOutput( $options );
		}		
		
		return $result;
	}
}
