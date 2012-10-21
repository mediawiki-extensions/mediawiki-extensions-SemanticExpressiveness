<?php
namespace SemEx;

use \Parameter, \SMWPropertyValue;

/**
 * Parameter criterion for 'Validator' stating that the value must be a SMW property.
 * Optionally this will only allow properties of certain types.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 * @ingroup Criteria
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class CriterionIsProperty extends \ItemParameterCriterion {

	protected $allowedTypes;

	/**
	 * Constructor.
	 * 
	 * @param $allowedTypes string|string[] will only aprove of the property if its type id matches
	 *        one of the given type ids. For example '_wpg' for properties of type page.
	 * 
	 * @since 0.1
	 */
	public function __construct( $allowedTypes = null ) {
		if( $allowedTypes !== null && !is_array( $allowedTypes ) ) {
			$allowedTypes = array( $allowedTypes );
		}
		$this->allowedTypes = $allowedTypes;
		parent::__construct();
	}

	/**
	 * @see ItemParameterCriterion::validate
	 */
	protected function doValidation( $value, Parameter $parameter, array $parameters ) {
		// create SMW property from user input:
		$prop = SMWPropertyValue::makeUserProperty( $value );

		if( $this->allowedTypes !== null ) {
			// filter unallowed types for this validation:
			$type = $prop->getPropertyTypeID();
			if( ! in_array( $type, $this->allowedTypes ) ) {
				return false;
			}
		}
		return $prop->isValid();
	}

	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */
	protected function getItemErrorMessage( Parameter $parameter ) {
		return '';
	}

	/** 
	 * @see ItemParameterCriterion::getFullListErrorMessage
	 */
	protected function getFullListErrorMessage( Parameter $parameter ) {
		return '';
	}
}
