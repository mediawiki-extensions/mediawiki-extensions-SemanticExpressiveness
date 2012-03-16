<?php

/**
 * Class for the '?to?!' parser function, basically a limited converter for expressive strings.
 * 
 * @since 0.1
 * 
 * @file SemExExpressiveStringPF.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class SemExExpressiveStringPF extends ParserHook {
		
	public function __construct() {
		// make this a parser function extension (no tag extension) only:
		parent::__construct( false, true, ParserHook::FH_NO_HASH );
	}
		
	/**
	 * No LSB in pre-5.3 PHP, to be refactored later
	 */	
	public static function staticInit( Parser &$parser ) {		
		$instance = new self;
		$instance->init( $parser );
		return true;
	}
	
	/**
	 * Gets the name of the parser hook.
	 * @see ParserHook::getName
	 * 
	 * @return string
	 */
	protected function getName() {
		return '?to?!';
	}
	
	/**
	 * Returns an array containing the parameter info.
	 * @see ParserHook::getParameterInfo
	 * 
	 * @return array
	 */
	protected function getParameterInfo( $type ) {
		$params = array();
		
		# input text.
		# since 0.1
		$params['text'] = new Parameter( 'text' );
		
		$pieceTypesCriteria = new CriterionInArray(
				array_values( SemExExpressiveString::getRegisteredPieceTypeNames() )
		);
		
		$params['detect'] = new ListParameter( 'detect' );
		$params['detect']->addCriteria( $pieceTypesCriteria );
		$params['detect']->setDefault( false, false );
		
		$params['ignore'] = new ListParameter( 'ignore' );
		$params['ignore']->addCriteria( $pieceTypesCriteria );
		$params['ignore']->setDefault( array(), false );
				
		$params = array_merge( $params, SemExExpressiveStringOutputOptions::getPFParams() );
		
		return $params;
	}
	
	/**
	 * Returns the list of default parameters.
	 * @see ParserHook::getDefaultParameters
	 * 
	 * @return array
	 */
	protected function getDefaultParameters( $type ) {
		return array(
			array( 'text', Validator::PARAM_UNNAMED ),
		);
	}
	
	/**
	 * Returns the parser function options.
	 * @see ParserHook::getFunctionOptions
	 * 
	 * @return array
	 */
	protected function getFunctionOptions() {
		return array(
			'noparse' => true,
			'isHTML' => false
		);
	}
		
	/**
	 * Renders and returns the output.
	 * @see ParserHook::renderTag
	 * 
	 * @param array $parameters
	 * @return string
	 */
	public function render( array $parameters ) {
		// get all types that should be handled by this
		$enabledTypes = array();
		
		if( $parameters['detect'] !== false ) {
			foreach( $parameters['detect'] as $typeName ) {
				$type = SemExExpressiveString::getRegisteredPieceTypeByName( $typeName );
				if( $type !== null ) {
					$enabledTypes[] = $type;
				}
			}
		} elseif( empty( $parameters['ignore'] ) ) {
			$enabledTypes = null; // same as next but constructor will process this faster
		} else {
			$enabledTypes = SemExExpressiveString::getRegisteredPieceTypeNames();
		}
		
		$enabledTypes = array_flip( $enabledTypes );
		foreach( $parameters['ignore'] as $typeName ) {
			unset( $enabledTypes[ SemExExpressiveString::getRegisteredPieceTypeByName( $typeName ) ] );
		}
		$enabledTypes = array_flip( $enabledTypes );
		
		// build expressive string from input with enabled types:
		$exprString = new SemExExpressiveString( $parameters['text'], $this->parser, $enabledTypes );
		
		/** @ToDo: Make it possible to define options per piece type per parameter prefixes */
		$options = SemExExpressiveStringOutputOptions::newFromValidatedParams( $parameters );
		return $exprString->getOutput( $options );
	}
	
}
