<?php
namespace SemEx;

/**
 * Factory class to generate and execute 'Semantic Expressiveness' short queries and return their
 * result as ShortQueryResult object or in directly in a serialized form.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ShortQueryProcessor {

	/**
	 * Processes a 'Semantic Expressiveness' Short Qery as given by an array of parameters as usually
	 * given by the '?' and '?!' parser functions.
	 * 
	 * @param parser Parser
	 * @param rawParams array unprocessed parameters
	 * 
	 * @return array
	 */
	public static function getResultFromFunctionParams( \Parser $parser, array $rawParams ) {

		$query = ShortQuery::newFromPFParams( $rawParams );
		$options = ShortQueryOutputOptions::newFromPFParams( $rawParams );

		// @ToDo: Check for validation errors at some point

		return self::getResultFromQuery( $parser, $query, $options );
	}

	/**
	 * Processes a 'Semantic Expressiveness' Short Query and returns the result which can contain
	 * either a valid result or an abstract value.
	 * Optionally there can be a ShortQueryResultOptions object passed to retain a certain
	 * kind of output from the short query.
	 *
	 * @param Parser $parser
	 * @param ShortQuery $query
	 * @param ShortQueryOutputOptions $options
	 * 
	 * @return ShortQueryResult|String
	 */
	public static function getResultFromQuery(
			\Parser $parser,
			ShortQuery $query,
			$options = null
	) {
		$result = new ShortQueryResult( $query, $parser );

		if( $options !== null ) {
			return $result->getOutput( $options );
		}

		return $result;
	}
}
