<?php

/**
 * Class representing one short query (sq) piece of a SemExExpressiveString.
 * Similar to SemExExpressiveStringPieceSQResult with the difference that this piece represents a
 * syntax for short to be executed instead of their actual results.
 * 
 * Basically, this is the parser for the "<?property::page>" syntax which gets more complicated with
 * advanced nested variations "<?a::b::c::d::e>" or "<?a::b::<?c::<?d::e>>>".
 * 
 * @since 0.1
 * 
 * @file SemExExpressiveStringPieceSQ.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class SemExExpressiveStringPieceSQ extends SemExExpressiveStringPieceSQResult {
	
	/* using:
	 * $origLimit = ini_set( 'pcre.recursion_limit', 8 );	
	 * to enforce hard limit for nested syntax for this regex doesn't work. The whole regex will fail
	 */
	protected static $regex = '<\?!?\s* [^<\|>]{1,64}? (?:\s*::\s*:?\s* (?>[^<>]{0,32}(?R)?){1,4})? \s*>';	
	protected static $regex_modifiers = 'x';
	protected static $regex_essentials = array();
	
	
	protected static function examineRegexMatch( array $backRefs, Parser $parser ) {
		// handle one single short query of the whole sentence
		// split it into its compontents:		
		preg_match(
				'/^<(\?!|\?)\s* ([^<\|>)]+?) (?:\s*::\s* (:?) \s* (.*?) )? \s*>$/x',
				$backRefs[0], $matches
		);
		$matches += array( 3 => false, 4 => false );
		$isPlain = ( $matches[1] == '?!' );			
		$pageSrcExplicit = ( $matches[3] == ':' );

		$property = SMWPropertyValue::makeUserProperty( $matches[2] );

		$source = $matches[4];
		if( $source === false ) {
			$source = null; // source is current page
		}
		else {
			// (recursivelly) examine the source for further short query syntax within:
			$source = new SemExExpressiveString( $source, $parser, static::getType() );
			$source = static::resolveDeepOneLinerNotation( $source );

			if( $source === false ) {
				// source is invalid in some way
				return false;
			}
		}

		// set the source of the query to a Title or another expressive string which
		// can contain several short queries all over again
		$query = new SemExShortQuery( $property, $source );			
		return new static(
				new SemExShortQueryResult( $query, $parser )
		);
	}
	
	/**
	 * Helper function to resolve short query notation like "<?a::b::c::d::e>".
	 * Note: "e" could also be another short query like "<?a::b::c::<?d::e>>", the parts before on
	 * the other hand are all properties which do not support a short query or expressive string
	 * source at the time.
	 * 
	 * @param SemExExpressiveString $eSrc
	 * @return Title|SemExExpressiveString|false
	 */
	protected static function resolveDeepOneLinerNotation( SemExExpressiveString $eSrc ) {
		if( $eSrc->getPiece( 0 )->isExpressive() ) {
			// whole meaning examined already and behind an expressive there can't be any "::" as
			// separator between property and source unless we'd allow expressive property sources.
			return $eSrc;
		}
		
		/*
		 * $eSrc only has one piece OR the next piece must be expressive. There could be further
		 * string pieces after the expressive one, but in that case they are part of the last part
		 */
		$sources = preg_split(
				'/\s*::\s*/',
				$eSrc->getPiece( 0 )->getRawText()
		);
		$eSrc->removePieces( 0 );
		
		// part behind last "::" is part of final source
		$eSrc->addString( array_pop( $sources ), 0 );		
		
		if( ! $eSrc->hasExpressiveness() ) {			
			$eSrc = Title::newFromText( $eSrc->getRawText() );
			if( $eSrc === null ) {
				return false;
			}
		}
		
		if( empty( $sources ) ) {
			// no further "::" separations or expressiveness
			return $eSrc;
		}
		
		$lastSrc = $eSrc;		
		$sources = array_reverse( $sources ); //last item first
		
		foreach( $sources as $property ) {
			$property = SMWPropertyValue::makeUserProperty( $property );
			if( ! $property->isValid() ) {
				return false;
			}
			$lastSrc = new SemExShortQuery( $property, $lastSrc );			
		}
		
		return $lastSrc;
	}
}
