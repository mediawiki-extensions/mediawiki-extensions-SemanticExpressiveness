<?php
namespace SemEx;

/**
 * Parameter criterion for 'Validator' stating that the value must be the key of a registered
 * SMWDataStore instance.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 * @ingroup Criteria
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class CriterionIsQuerySource extends ItemParameterCriterion {

	/**
	 * Constructor.
	 * 
	 * @since 0.1
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @see ItemParameterCriterion::validate
	 */
	protected function doValidation( $value, Parameter $parameter, array $parameters ) {
		global $smwgQuerySources;
		return array_key_exists( $value, $smwgQuerySources );
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
