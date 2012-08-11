<?php
namespace SemEx;

/**
* Parameter manipulation converting the value to a SMWStore listed within the global
 * $smwgQuerySources config variable.
 * 
 * @since 0.1
 * 
 * @file ShortQueryResult.php
 * @ingroup SemanticExpressiveness
 * @ingroup ParameterManipulations
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ParamManipulationQuerySource extends ItemParameterManipulation {
	
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
		global $smwgQuerySources;
		
		// get specific store or take default:		
		if( array_key_exists( $value, $smwgQuerySources ) ) {
			$value = new $smwgQuerySources[ $value ]();
		} else {
			$value = smwfGetStore(); // take default
		}
	}
	
}
