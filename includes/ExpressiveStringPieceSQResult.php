<?php
namespace SemEx;
use Parser, DOMDocument;

/**
 * Class representing one short query result piece of a ExpressiveString.
 * Similar to ExpressiveStringPieceSQ with the difference that the initialization routines for
 * this type search for the result of an already queried short query within a string.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveStringPieceSQResult extends ExpressiveStringPieceByRegex {

	// [1] match <span> with 'shortQuery' class if we can make sure...
	// [2] ... no further <span>-pairs inside...
	//     OR
	// [3] ... DOM inside only contains opening+closing <span>-pairs (ensured by recursive regex)
	protected static $regex = '
		(?# COMMENT #1 )
		<span \s+(?:[^>]*\s+|) class\s*=\s*(?P<q>[\'"])(?:[^>\k<q>]*\s+|\s*) shortQuery (?:[^>\k<q>]*\s+|\s*)\k<q>[^>]*>

		(?# COMMENT #2 )
		( (?>(?!<span(?:\s+[^>]*|)>|<\/span>).)*

		(?# COMMENT #3 )
		| (?P<innerDOM> <span(?:\s+[^>]*|)>(?: (?>(?!<span(?:\s+[^>]*|)>|<\/span>).)* | (?&innerDOM) )*?<\/span> )*
		)* <\/span>';

	protected static $regex_modifiers = 'sx';
	protected static $regex_essentials = array( false, false );

	/**
	 * @var ShortQueryResult
	 */
	protected $value;

	function __construct( ShortQueryResult $value ) {
		parent::__construct( $value );
	}

	public function getRawText() {
		return $this->value->getRawText();
	}

	public function getWikiText(
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		return $this->value->getWikiText( $linked, $showErrors );
	}

	public function getAbstractRawText() {
		return $this->value->getAbstractResult()->getRawText();
	}

	public function getAbstractWikiText(
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		return $this->value->getAbstractResult()->getWikiText( $linked, $showErrors );
	}

	public static function hasAbstractRepresentation() {
		return true;
	}

	public function isUnresolvable() {
		// unresolvable if faulty short query or no result
		return $this->value->isEmpty();
	}

	protected function getErrors_internal() {
		return $this->value->getErrors();
	}

	protected function getOutputByOptions( ExpressiveStringOutputOptions $options ) {
		return $this->value->getOutput( $options );
	}

	/**
	 * @return ShortQueryOutputOptions
	 */
	public static function getDefaultOutputOptions() {
		return new ShortQueryOutputOptions();
	}

	protected static function examineRegexMatch( array $backRefs, Parser $parser ) {
		/*
		$part = str_replace( '&nbsp;', ' ', $part );
		$part = str_replace( '&#32;',  ' ', $part );
		$part = str_replace( '&#160;', ' ', $part );
		*/
		$xmlDoc = new DOMDocument();
		$xmlDoc->strictErrorChecking = false;

		wfSuppressWarnings();
		$validDom = $xmlDoc->loadXML( $backRefs[0] );
		wfRestoreWarnings();

		if( ! $validDom ) {
			// no well-formed xml, insert as plain string
			return false;
		}

		try {
			// try to re-fabricate short query result from DOM:
			$sqResult = ShortQueryResult::newFromDOM( $xmlDoc->documentElement, $parser );
		} catch( \Exception $exc ) {
			// invalid, insert as plain string
			return false;
		}

		return new static( $sqResult );
	}
}
