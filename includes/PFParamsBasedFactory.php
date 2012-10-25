<?php
namespace SemEx;

use Validator;

/**
 * Interface for classes offering a factory method for creating a new instance from parser function
 * parameters.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
abstract class PFParamsBasedFactory {

	protected static $pfParamsValidatorElement;

	/**
	 * Fabricates a new instance by a set of parser function parameters.
	 * If the given parameters were insufficient, false will be returned. Validation errors can be
	 * accessed by the $validator parameter.
	 *
	 * @param array $params raw parameters as given by a parser function
	 * @param array $defaultParams see Validator::setFunctionParams() $defaultParams parameter.
	 * @param null $validator
	 * @param bool $unknownInvalid
	 * @return __CLASS__|false
	 */
	public static function newFromPFParams(
			array $params,
			array $defaultParams = array(),
			&$validator = null,
			$unknownInvalid = false
	) {
		$params = static::getValidatedPFParams( $params, $defaultParams, $validator, $unknownInvalid, true );

		if( $validator->hasFatalError() ) {
			return false;
		}
		return static::newFromValidatedParams( $params, $validator );
	}

	/**
	 * Same as newFromPFParams() except that the $params array has to have the parameter names set
	 * as key values.
	 * If the given parameters were insufficient, false will be returned. Validation errors can be
	 * accessed by the $validator parameter.
	 * 
	 * @param array $params raw parameters as values, parameter names as keys
	 * @param type $validator
	 * @param type $unknownInvalid
	 * @return __CLASS__ 
	 */
	public static function newFromParamsArray(
			array $params,
			&$validator = null,
			$unknownInvalid = false
	) {
		$params = static::getValidatedPFParams( $params, array(), $validator, $unknownInvalid, false );

		if( $validator->hasFatalError() ) {
			return false;
		}
		return static::newFromValidatedParams( $params, $validator );
	}

	/**
	 * This should be overwritten by classes inheriting this one to fabricate a new instance by a set
	 * of parameters.
	 * 
	 * @param array $params
	 * @return __CLASS__ 
	 */
	public static function newFromValidatedParams( array $params ) {
		return null;
	}

	/**
	 * Returns the validated parser function parameters by a given set of unprocessed parameters.
	 * 
	 * @param array $params
	 * @param array $defaultParams see Validator::setFunctionParams() $defaultParams parameter.
	 *        this has no effect if $pfStyle is set to false.
	 * @param null &$validator allows to get the Validator object used to validate the parameters.
	 * @param bool $unknownInvalid
	 * @param bool $pfParamsStyle if true this will not expect $params to have parameter keys as
	 *        name, instead the names will be extracted from the values.
	 * 
	 * @return array
	 */
	public static function getValidatedPFParams(
			array $params,
			array $defaultParams = array(),
			&$validator = null,
			$unknownInvalid = false,
			$pfParamsStyle = true
	) {
		$validator = new Validator( static::$pfParamsValidatorElement, $unknownInvalid );

		if( $pfParamsStyle ) {
			$validator->setFunctionParams( $params, static::getPFParams(), $defaultParams );
		} else {
			$validator->setParameters( $params, static::getPFParams() );
		}

		$validator->validateParameters();

		return $validator->getParameterValues();
	}

	/**
	 * Returns a description of all allowed function Parameters representing this class.
	 * 
	 * @return array
	 */
	public static function getPFParams() {
		return array();
	}
}
