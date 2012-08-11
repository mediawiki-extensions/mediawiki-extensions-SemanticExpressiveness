<?php
namespace SemEx;

/**
 * Class representing one piece of a ExpressiveString representing a wiki link.
 * This represents all kinds of wiki links, also category links and SMW property declarations.
 * This implementation is rather shallow and mainly serves to filter Links.
 * 
 * @since 0.1
 * 
 * @file ExpressiveStringPiece.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveStringPieceWikiLink extends ExpressiveStringPieceByRegex {
	
	// Search all kind of wiki links (including category and SMW)
	// $1: ":" if this is a normal link explicitly
	// $2: empty or source
	// $3: text and if $2 is empty also source
	protected static $regex = '\[\[ (:?) ( \s* (?=[^\[\]\|]*\|)[^\[\]\|]* \s* \| | )( [^\[\]]* ) \]\]';
	protected static $regex_modifiers = 'x';
	protected static $regex_essentials = array( true, true, true );
	
	protected $isExplicitLink;
	protected $linkTarget;
	protected $linkText;
	
	function __construct( array $value ) {
		parent::__construct( $value );
		
		$this->isExplicitLink = $value['explicit'];
		$this->linkTarget    = $value['target'];
		$this->linkText      = $value['text'];
	}
	
	/**
	 * Returns the link in its raw form as wiki text.
	 * 
	 * @return string
	 */
	public function getRawLink() {
		return '[[' . ( $this->isExplicitLink ? ':' : '' ) . $this->linkTarget . $this->linkText . ']]';
	}
	
	public function getRawText() {
		return $this->linkText;
	}
	
	public function getWikiText(
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		if( $linked === ExpressiveStringOutputOptions::LINK_ALL ) {
			return $this->getRawLink();
		} else {
			return $this->linkText;
		}
	}
	
	public function isUnresolvable() {
		return false;
	}
	
	public static function hasAbstractRepresentation() {
		return false;
	}
		
	protected static function examineRegexMatch( array $backRefs, \Parser $parser ) {
		$result = array();
		
		$result['explicit'] = $backRefs[1] === ':';
		$result['text']     = $backRefs[3]; // can contain leading/trailing spaces
		$result['target']   = ( $backRefs[2] !== '' )
				? $backRefs[2]
				: trim( $result['text'] );
		
		// Don't do further checks about whether this is a valid title since this could be
		// SMW property declaration syntax as well
		
		return new static( $result );
	}
}
