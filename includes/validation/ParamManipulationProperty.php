<?php
namespace SemEx;

/**
* Parameter manipulation converting the value to a SMWPropertyValue.
 * 
 * @since 0.1
 * 
 * @file ShortQueryResult.php
 * @ingroup SemanticExpressiveness
 * @ingroup ParameterManipulations
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ParamManipulationProperty extends ItemParameterManipulation {

	/**
	 * Constructor.
	 * 
	 * @since 0.4.14
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @see ItemParameterManipulation::doManipulation
	 */
	public function doManipulation( &$value, Parameter $parameter, array &$parameters ) {
		$value = SMWPropertyValue::makeUserProperty( $value );
	}

}
