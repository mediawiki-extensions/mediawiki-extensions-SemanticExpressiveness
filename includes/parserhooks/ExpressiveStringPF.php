<?php
namespace SemEx;
use ParserHook, Validator, ListParameter;

/**
 * Class for the '?to?!' parser function, basically a limited converter for expressive strings.
 * 
 * @since 0.1
 * 
 * @file ExpressiveStringPF.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveStringPF extends ParserHook {
		
	public function __construct() {
		// make this a parser function extension (no tag extension) only:
		parent::__construct( false, true, ParserHook::FH_NO_HASH );
	}
		
	/**
	 * No LSB in pre-5.3 PHP, to be refactored later
	 */	
	public static function staticInit( \Parser &$parser ) {
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
		$params = ExpressiveStringOutputOptions::getPFParams();
		
		# input text.
		# since 0.1
		$params['text'] = new Parameter( 'text' );
		
		$pieceTypesCriteria = new CriterionInArray(
				array_values( ExpressiveString::getRegisteredPieceTypeNames() )
		);
		
		$params['detect'] = new ListParameter( 'detect' );
		$params['detect']->addCriteria( $pieceTypesCriteria );
		$params['detect']->setDefault( array( '' ), false );
		
		$params['ignore'] = new ListParameter( 'ignore' );
		$params['ignore']->addCriteria( $pieceTypesCriteria );
		$params['ignore']->setDefault( array(), false );
		
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
		
		if( implode( '', $parameters['detect'] ) !== '' ) { // '' counts as if parameter not set
			foreach( $parameters['detect'] as $typeName ) {
				$type = ExpressiveString::getRegisteredPieceTypeByName( $typeName );
				if( $type !== null ) {
					$enabledTypes[] = $type;
				}
			}
		} elseif( empty( $parameters['ignore'] ) ) {
			$enabledTypes = null; // same as next but constructor will process this faster
		} else {
			$enabledTypes = ExpressiveString::getRegisteredPieceTypeNames();
		}
		
		if( $enabledTypes !== null ) {
			$enabledTypes = array_flip( $enabledTypes );
			foreach( $parameters['ignore'] as $typeName ) {
				unset( $enabledTypes[ ExpressiveString::getRegisteredPieceTypeByName( $typeName ) ] );
			}
			$enabledTypes = array_flip( $enabledTypes );
		}
		
		// build expressive string from input with enabled types:
		$exprString = new ExpressiveString( $parameters['text'], $this->parser, $enabledTypes );
		
		/** @ToDo: Make it possible to define options per piece type per parameter prefixes */
		$options = ExpressiveStringOutputOptions::newFromValidatedParams( $parameters );
		return $exprString->getOutput( $options );
	}
}
