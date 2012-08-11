<?php
namespace SemEx;

/**
 * Class which can process a string which potentially contains one or several ShortQueryResult
 * representations. Once initialized, this will offer access to all failed and successful short query
 * results and text parts inbetween. Also allows to convert the whole string into plain text.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveString {

	protected $enabledPieceTypes;
	protected $parser;

	/**
	 * All parts the original string $string consists of
	 * @var array of ShortQueryResult objects and/or strings
	 */
	protected $pieces = array();

	/**
	 * Constructor
	 * 
	 * @param string $string
	 * @param Parser $parser
	 * @param array|string $enabledPieceTypes allows to define which expressive types should be
	 *        detected. See static::getRegisteredPieceTypes() for all possible types.
	 */
	function __construct( $string, \Parser $parser, $enabledPieceTypes = null ) {
		if( $enabledPieceTypes === null ) {
			$enabledPieceTypes = static::getRegisteredPieceTypes();
		}
		elseif( ! is_array( $enabledPieceTypes ) ) {
			// only one type, don't care about priority
			$enabledPieceTypes = array( $enabledPieceTypes );
		}
		else {
			// make sure we get the priority as key in user-given array
			$enabledPieceTypes = $this->injectPieceTypePriorities( $enabledPieceTypes );
		}
		krsort( $enabledPieceTypes ); // highest will be initialized last
		$this->enabledPieceTypes = $enabledPieceTypes;
		$this->parser = $parser;

		// add string as non-expressive string...
		$this->addString( $string );

		// ... and examine its expressive meaning one by one:
		foreach( $this->getEnabledPieceTypes() as $pieceType ) {
			$pieceType :: initWithin( $this );
		}
	}

	/**
	 * Returns all parts the original string consists of.
	 * 
	 * @return ExpressiveStringPiece[]
	 */
	public function getPieces() {
		return $this->pieces;
	}

	/**
	 * Returns a piece from a given index or null if the index doesn't exist.
	 * 
	 * @param int $index 
	 * @return ExpressiveStringPiece
	 */
	public function getPiece( $index ) {
		if( ! array_key_exists( $index, $this->pieces ) ) {
			return null;
		}
		return $this->pieces[ $index ];
	}

	/**
	 * Returns all pieces of the string which which have their own expressive meaning and not just
	 * represent a simple string.
	 * 
	 * @return ExpressiveStringPiece[]
	 */
	public function getExpressivePieces() {
		$exprPieces = array();
		foreach( $this->pieces as $piece ) {
			if( $piece->isExpressive() ) {
				$exprPieces[] = $piece;
			}
		}
		return $exprPieces;
	}

	/**
	 * Returns whether the string has any expressive pieces.
	 * 
	 * @return bool
	 */
	public function hasExpressiveness() {
		foreach( $this->pieces as $piece ) {
			if( $piece->isExpressive() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns true if any expressive piece is not resolvable, meaning the piece doesn't have a pure
	 * textual representation.
	 * 
	 * @return bool
	 */
	public function hasUnresolvablePiece() {
		foreach( $this->pieces as $piece ) {
			if( $piece->isUnresolvable() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * This will examine a string for its expressive meaning and add all parts accordingly.
	 * 
	 * @param string $string
	 * @param int $index see self::addPieces()
	 */
	public function addExpressiveString( $string, $index = null ) {
		$newExprString = new static( $string, $this->parser, $this->getEnabledPieceTypes() );
		$this->addPieces( $newExprString->getPieces(), $index );
	}

	/**
	 * Shorthand function for self::addPieces( new ExpressiveStringPiece( $stringPiece ) ).
	 * 
	 * @param string $stringPiece
	 * @param int $index see self::addPieces()
	 */
	public function addString( $stringPiece, $index = null ) {
		$piece = new ExpressiveStringPiece( $stringPiece );
		$this->addPieces( array( $piece ), $index );
	}

	/**
	 * Allows to insert additional pieces to the expressive string. By default at the end
	 * or optionally at a certain index.
	 * 
	 * @param ExpressiveStringPiece|ExpressiveStringPiece[] $pieces
	 * @param int $index if this is set, the new pieces will be inserted at this index,
	 *        the original item at this position will be moved behind the inserted ones.
	 *        Negative index will insert the items that far from the end.
	 */
	public function addPieces( $pieces, $index = null ) {
		if( ! is_array( $pieces ) ) {
			$pieces = array( $pieces );
		}
		$this->pieces = array_values( $this->pieces ); // re-numerate to be on the safe side
		$totalPieces = count( $this->pieces );

		if( $index < 0 ) {
			// -1 will insert $pieces before last current piece
			$index = $totalPieces - $index;
			if( $index < 0 ) {
				$index = 0;
			}
		}

		if( $index === null || $index >= $totalPieces ) {
			// pieces will be added at the end
			$index = $totalPieces;
		}
		else {
			// pieces will be inserted before an existing one
			// add the piece after new pieces for reduction
			$pieces[] = $this->pieces[ $index ];
			unset( $this->pieces[ $index ] );
		}

		if( $index > 0 && $totalPieces > 0 ) {
			// add the one before new pieces for reduction
			$index--;
			$pieces = array_merge(
					array( $this->pieces[ $index ] ),
					$pieces
			);
			unset( $this->pieces[ $index ] );
		}

		static::reducePieces( $pieces );

		array_splice( $this->pieces, $index, 0, $pieces ); // re-numerates keys
	}

	/**
	 * Reduces an array of ExpressiveStringPiece elements where possible. This means if the array
	 * contains several non-expressive objects in a row, they will be reduced to one instead.
	 * This will also make all keys numeric and close gaps.
	 * 
	 * @param ExpressiveStringPiece[] $pieces
	 * @return int number of reduced pieces
	 */
	protected static function reducePieces( array &$pieces ) {
		$lastPieceType = null;
		$lastPieceKey = null;
		$i = 0;

		foreach( $pieces as $key => $piece ) {
			if( ! $piece instanceof ExpressiveStringPiece
				|| $piece->getValue() === ''
			){
				// remove totally useless empty string or invalid item
				unset( $pieces[ $key ] );
				$i++;
				continue;
			}

			$thisPieceType = $piece->getType();

			if(
				$lastPieceType === SEMEX_EXPR_PIECE_STRING
				&& $thisPieceType === SEMEX_EXPR_PIECE_STRING
			) {
				// if two elements in a row are strings, merge them:
				$pieces[ $lastPieceKey ] = new $lastPieceType(
						$pieces[ $lastPieceKey ]->getValue() . $pieces[ $key ]->getValue()
				);
				unset( $pieces[ $key ] );
				$i++;
			} else {
				$lastPieceKey = $key;
				$lastPieceType = $thisPieceType;
			}
		}
		$pieces = array_values( $pieces ); // re-numerate
		return $i;
	}

	/**
	 * Removes pieces of the string starting from a given index to the end or a predefined
	 * number of pieces.
	 * 
	 * @param int $offset
	 * @param int $length how many pieces to remove. null means all pieces from the $offset
	 *        to the end. A negative value will preserve pieces that far from the end.
	 */
	public function removePieces( $offset, $length = null ) {
		if( empty( $length ) ) {
			$length = count( $offset );
		}
		array_splice( $this->pieces, $offset, $length );
		static::reducePieces( $this->pieces );
	}

	/**
	 * Returns the parser used for certain transformations
	 * @return Parser
	 */
	public function getParser() {
		return $this->parser;
	}

	public function setParser( Parser $parser ) {
		$this->parser = $parser;
	}

	/**
	 * Returns the expressive string as wiki text with all expressive pieces resolved but without any
	 * further markup, just plain text.
	 * 
	 * @param bool $expressiveIfEmpty whether an expressive textual placeholder should be placed for
	 *        pieces which have no value. false by default.
	 * @return string
	 */
	public function getRawText( $expressiveIfEmpty = false ) {
		$result = '';

		foreach( $this->pieces as $piece ) {
			if( $expressiveIfEmpty && $piece->isUnresolvable() ) {
				$pieceText = $piece->getAbstractRawText();
			} else {
				$pieceText = $piece->getRawText();
			}
			$result .= $pieceText;
		}
		return $result;
	}

	/**
	 * Returns the expressive string as wiki text with all expressive pieces resolved, perhaps with
	 * markup built around some pieces.
	 * 
	 * @param bool $expressiveIfEmpty whether an expressive textual placeholder should be placed for
	 *        pieces which have no value. true by default.
	 * 
	 * @param bool $expressiveIfEmpty 
	 */
	public function getWikiText(
		$expressiveIfEmpty = true,
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		$result = '';

		foreach( $this->pieces as $piece ) {
			if( ! $expressiveIfEmpty && $piece->isUnresolvable() ) {
				$pieceText = $piece->getAbstractWikiText( $linked, $showErrors );
			} else {
				$pieceText = $piece->getWikiText( $linked, $showErrors );
			}
			$result .= $pieceText;
		}
		return $result;
	}

	/**
	 * same as getRawText() but instead of the resolved meaning of the expressive pieces this will
	 * include the abstract representation of all pieces as long as an abstract version is available
	 * for the piece type.
	 * 
	 * @return string
	 */
	public function getAbstractRawText() {
		$result = '';

		foreach( $this->pieces as $piece ) {
			$result .= $piece->getAbstractRawText();
		}
		return $result;
	}

	/**
	 * same as getWikiText() but instead of the resolved meaning of the expressive pieces this will
	 * include the abstract representation of all pieces as long as an abstract version is available
	 * for the piece type.
	 * 
	 * @return string
	 */
	public function getAbstractWikiText(
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		$result = '';

		foreach( $this->pieces as $piece ) {
			$result .= $piece->getAbstractWikiText( $linked, $showErrors );
		}
		return $result;
	}

	/**
	 * Returns an output generated by one or several ExpressiveStringOutputOptions objects.
	 * 
	 * @param ExpressiveStringOutputOptions|null $defaultOption allows to set a option used
	 *        as default. If set to null the piece types own default options will be used.
	 * @param ExpressiveStringOutputOptions[]|null[] $options If one option is given, it will
	 *        be taken for all types of pieces, otherwise an array is expected which holds keys
	 *        which refer to certain piece types to define a certain option per type. If the
	 *        value for one type is set to null, it will fall back to its default option. If a
	 *        type is completely omitted, the $defaultOption will be used for the type, except it
	 *        is set to null, in this case the types default option will be used.
	 * 
	 * @return string
	 */
	public function getOutput( $defaultOption = null, $options = array() ) {
		$result = '';

		foreach( $this->pieces as $piece ) {
			$pieceType = $piece->getType();

			if( array_key_exists( $pieceType, $options ) ) {
				// option/fallback for this piece type specified
				$pieceOption = $options[ $pieceType ];
			}
			else {
				// no specific option for this type, use specified default option
				$pieceOption = $defaultOption;
			}

			if( $pieceOption === null ) {
				// option wasn't set! use default option for this piece type
				$pieceOption = $pieceOption :: getDefaultOutputOptions();
				$options[ $pieceType ] = $pieceOption; // remember for next pice of this type!
			}

			$result .= $piece->getOutput( $pieceOption );
		}
		return $result;
	}

	/**
	 * Returns which types of expressive string pieces exist. The key number defines the priority
	 * of the type within parsing. The highest will be initialized last during the parser process.
	 * 
	 * @return array 
	 */
	public static function getRegisteredPieceTypes() {
		static $types = null;
		if( $types !== null ) {
			return $types;
		}

		$types = array();

		foreach( static::pieceTypeRegistration() as $priority => $typeDef ) {
			$types[ $priority ] = is_array( $typeDef )
					? $typeDef[0]
					: $typeDef;
		}

		return $types;
	}

	/**
	 * Returns all registered types in an array as keys and their public names as values. If a
	 * registered type doesn't have a name, the type won't be in this list.
	 * Use getRegisteredPieceTypes() for getting all types by priority instead.
	 * 
	 * @return array
	 */
	public static function getRegisteredPieceTypeNames() {
		static $types = null;
		if( $types !== null ) {
			return $types;
		}

		$types = array();

		foreach( static::pieceTypeRegistration() as $typeDef ) {
			if( is_array( $typeDef )
				&& ! empty( $typeDef[1] )
			) {
				$types[ $typeDef[0] ] = $typeDef[1];
			}
		}

		return $types;
	}

	/**
	 * Returns the class name of a certain registered piece type by the name it's been registered to
	 * this class. Some types might not be registered by a name at all.
	 * 
	 * @param name
	 * 
	 * @return string|null piece type class name or null if there is no name identifier for it.
	 */
	public static function getRegisteredPieceTypeByName( $name ) {
		$types = static::getRegisteredPieceTypeNames();
		$index = array_search( $name, $types );
		if( $index !== false ) {
			return $index; // the index is the types class name
		}
		return null;
	}

	protected static function pieceTypeRegistration() {
		static $types = null;
		if( $types !== null ) {
			return $types;
		}

		$types = array(
			// key defines priority
			0    => SEMEX_EXPR_PIECE_STRING, // will always be initialized since there is nothing to initialize
			100  => array( SEMEX_EXPR_PIECE_WIKILINK, 'wikilink' ),
			1000 => array( SEMEX_EXPR_PIECE_SQRESULT, 'shortqueryresult' ),
			2000 => array( SEMEX_EXPR_PIECE_SQ, 'shortquery' )
		);

		// allow other extensions to handle further expressive string pieces
		wfRunHooks( __CLASS__ . 'PieceTypesRegistration' , array( &$types ) );

		return $types;
	}

	/**
	 * Takes an array with piece types as values and sets their keys according to the registered piece
	 * types priorities.
	 * 
	 * @param array $types 
	 * @return array
	 */
	protected static function injectPieceTypePriorities( array $types ) {
		$result = array();
		// go through all registered types and check whether they are within the input array,
		// if so, include it with its original priority
		foreach( static::getRegisteredPieceTypes() as $priority => $type ) {
			if( array_search( $type, $types ) !== false ) {
				$result[ $priority ] = $type;
			}
		}
		return $result;
	}

	/**
	 * Returns which types of expressive string pieces are enabled for this object.
	 * This can differ from static::getRegisteredPieceTypes() if specified within the constructor.
	 * 
	 * @return array 
	 */
	public function getEnabledPieceTypes() {
		return $this->enabledPieceTypes;
	}
}
