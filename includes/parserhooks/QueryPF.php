<?php
namespace SemEx;

/**
 * Class for '?' short query parser function
 * 
 * @since 0.1
 * 
 * @file QueryPF.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class QueryPF {

	public static function render( Parser &$parser ) {
		global $smwgQEnabled, $smwgIQRunningNumber;

		if ( $smwgQEnabled ) {
			$smwgIQRunningNumber++;

			$params = func_get_args();
			array_shift( $params ); // remove $parser
			$params = static::validateParams( $params );

			$query = ShortQuery::newFromValidatedParams( $params );
			$options = ShortQueryOutputOptions::newFromValidatedParams( $params );

			if( ! $query || ! $options ) {
				// @ToDo: real error message (anyway, in what case can this happen?)
				return 'FALSE';
			}

			$result = ShortQueryProcessor::getResultFromQuery( $parser, $query, $options );

			if( $result === '' ) {
				$result = $params['default'];
			} else {
				$result = // allow ' ' in form of '_' around the result
						preg_replace( '/_$/', ' ', $params['intro'] ) .
						$result .
						preg_replace( '/^_/', ' ', $params['outro'] );
			}
		}
		else {
			$result = smwfEncodeMessages( array( wfMsgForContent( 'smw_iq_disabled' ) ) );
		}

		 return $result;
	}

	protected static function validateParams( array $rawParams, &$validator = null ) {
		$validator = new Validator();
		$validator->setFunctionParams(
				$rawParams,
				static::getParameters(),
				array( 'property', Validator::PARAM_UNNAMED ) // 'property' is parameter 1
		);
		$validator->validateParameters();

		return $validator->getParameterValues();
	}

	/**
	 * Returns a description of all allowed function Parameters.
	 * 
	 * @return array
	 */
	public static function getParameters() {
		$params = array();

		$params['intro']   = new Parameter( 'intro' );
		$params['intro']->setDefault( '' );

		$params['outro']   = new Parameter( 'outro' );
		$params['outro']->setDefault( '' );

		$params['default'] = new Parameter( 'default' );
		$params['default']->setDefault( '' );

		// add function parameters describing the querry and its options:
		$params = array_merge(
			$params,
			ShortQuery::getPFParams(),
			ShortQueryOutputOptions::getPFParams()
		);

		return $params;
	}
}
