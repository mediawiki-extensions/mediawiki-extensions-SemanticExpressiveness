<?php
namespace SemEx;
use Parser;

/**
 * Abstract class representing one piece of a ExpressiveString with abstract features to
 * initialize pieces of a type by a matching regular expression.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
abstract class ExpressiveStringPieceByRegex extends ExpressiveStringPiece {

	/**
	 * The regular expression to match pieces which will be considered candidates for being pieces of
	 * this type and which can be further examined in static::examineRegexMatch()
	 * This is only the inner regex, modifiers can be set via static::$regex_modifiers
	 * The '/' character has to be escaped '\/' within the expression.
	 * @var string
	 */
	protected static $regex;

	/**
	 * Optional modifiers for the static::$regex regex.
	 * @var string
	 */
	protected static $regex_modifiers = '';

	/**
	 * Array telling which backreferences of $regex are essential information and which can be ignored.
	 * The array index refers to the backreference index within $regex, if it is set to true it means
	 * that the value will be made available within static::examineRegexMatch().
	 * The whole matched string will be made available anyway, so it might not be necessary to request
	 * further backrefs here.
	 * @var bool[]
	 */
	protected static $regex_essentials = array();


	protected static function examineString( $string, Parser $parser ) {
		// the regex is built to hold the whole matched string as backref for later
		$regex = '/(' . static::$regex . ')/' . static::$regex_modifiers;
		$rawParts = preg_split( $regex, $string, -1, PREG_SPLIT_DELIM_CAPTURE );

		$count = count( $rawParts ) ;
		if( $count <= 1 ) {
			// no match for this type
			return false;
		}

		$parts = array();
		$backrefs = count( static::$regex_essentials );

		// group backrefs and actual match which belong together, put non-expressive strings inbetween
		// so we have an array full of strings and sub-arrays still in the right order:
		for( $i = 0; $i < $count; $i++ ){
			$parts[] = $rawParts[ $i ]; // string or empty part inbetween
			$i++;
			if( $i === $count ) {
				break; // last piece
			}

			// get all essential backrefs for the matching piece:
			$essentialBackrefs = array(
				$rawParts[ $i ] // the part holding the whole matched string, the raw piece
			);
			for( $k = 0; $k < $backrefs; $k++ ) {
				$i++;
				if( static::$regex_essentials[ $k ] ) {
					$essentialBackrefs[] = $rawParts[ $i ];
				}
			}
			$parts[] = $essentialBackrefs;
		}

		// walk through the created array and call sub-function for each array element which holds all the
		// information about one potential match for a piece of this type:
		$result = array();
		$i = -1;
		foreach( $parts as $part ) {
			$i++;
			if( is_string( $part ) ) {
				// only each second element can be a regex match
				if( $part !== '' ) {
					$result[] = new ExpressiveStringPiece( $part );
				}
				continue;
			}

			$piece = static::examineRegexMatch( $part, $parser );

			if( $piece === false ) {
				// not a piece of this type, consider it a meaningless string
				$piece = new ExpressiveStringPiece( $part[0], $parser );
			}

			$result[] = $piece;
		}

		return $result;
	}

	/**
	 * Function called by examineString() when a static::$regex matching string has been found.
	 * Returns the resolved piece or false in case this is not a piece of this type.
	 * 
	 * @param string[] $backRefs all references from static::$regex which are marked as essential
	 *        by static::$regex_essentials. The index 0 contains the whole match.
	 * @param Parser $parser
	 * 
	 * @return ExpressiveStringPiece|false
	 */
	protected static function examineRegexMatch( array $backRefs, Parser $parser ) {
		return false;
	}
}
