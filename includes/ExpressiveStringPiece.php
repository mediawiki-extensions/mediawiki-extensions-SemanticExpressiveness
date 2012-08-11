<?php
namespace SemEx;
use Parser;

/**
 * Class representing one piece of a ExpressiveString.
 * 
 * @since 0.1
 * 
 * @file ExpressiveStringPiece.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveStringPiece {

	protected $value;

	/**
	 * Constructor
	 * 
	 * @param mixed $value
	 */
	function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Returns the value of this piece
	 * 
	 * @return mixed depending on the pieces type
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Returns the raw text value of this piece. Doesn't contain any expressive markup anymore,
	 * just its textual representation.	If there is no textual representation, this will return
	 * an empty string even though the actual textual representation could be an empty string.
	 * static::isUnresolvable() can be used to make sure whether any textual representation is
	 * available.
	 * 
	 * @return string
	 */
	public function getRawText() {
		return $this->value;
	}

	/**
	 * Same as getRawText() but potentially with more expensive markup for wiki text usage.
	 * 
	 * @return string
	 */
	public function getWikiText(
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		return $this->value;
	}

	/**
	 * Returns an abstract textual representation of this pieces meaning. If the type doesn't
	 * support abstract representation, this will return the normal raw text.
	 * 
	 * @return string
	 */
	public function getAbstractRawText() {
		return $this->getRawText();
	}

	/**
	 * Returns the abstract wiki text representation of this piece. If the type doesn't support
	 * abstract representation, this will return the normal wiki text.
	 * 
	 * @return string 
	 */
	public function getAbstractWikiText(
		$linked = ExpressiveStringOutputOptions::LINK_ALL,
		$showErrors = false
	) {
		return $this->getWikiText( $linked, $showErrors );
	}

	/**
	 * Returns the output of the piece in a form specified by a ExpressiveStringOutputOptions
	 * object.
	 * 
	 * @param ExpressiveStringOutputOptions $options
	 * @return string
	 */
	public final function getOutput( ExpressiveStringOutputOptions $options ) {
		// make sure that the output options object contains all options which might be expected by
		// any overridden ancestor class function.
		$dfltOptions = $this->getDefaultOutputOptions();

		if( get_class( $options ) !== get_class( $dfltOptions ) ) {
			$dfltOptions->mergeOptions( $options );
			$options = $dfltOptions;
		}
		return $this->getOutputByOptions( $options );
	}

	/**
	 * This is the function handling getOutput() internally, receiving ab appropriate instance of
	 * 'ExpressiveStringOutputOptions' or rather of the subclass describing all options for
	 * the piece type.
	 * It is datermined by getDefaultOutputOptions() which one would be the most suitable
	 * 'ExpressiveStringOutputOptions' subclass for this type.
	 * 
	 * @param ExpressiveStringOutputOptions $options
	 * @return string
	 */
	protected function getOutputByOptions( ExpressiveStringOutputOptions $options ) {
		$useRaw = $options->getFormat() === ExpressiveStringOutputOptions::FORMAT_RAW;
		$linked = $options->getLink();
		$errors = $options->getShowErrors();

		if( $options->getShowAbstract() === ExpressiveStringOutputOptions::ABSTRACT_ONLY
			|| $options->getShowAbstract() === ExpressiveStringOutputOptions::ABSTRACT_IF_FAILURE
			&& $this->isUnresolvable()
		) {
			if( $useRaw ) {
				return $this->getAbstractRawText();
			} else {
				return $this->getAbstractWikiText( $linked, $errors );
			}
		} else {
			if( $useRaw ) {
				return $this->getRawText();
			} else {
				return $this->getWikiText( $linked, $errors );
			}
		}
	}

	/**
	 * Returns true if the expressive meaning can't be resolved into a textual representation.
	 * 
	 * @return bool
	 */
	public function isUnresolvable() {
		return false;
	}

	/**
	 * Returns whether there have occurred errors of any kind.
	 * 
	 * @return bool
	 */
	public function hasErrors() {
		$errors = $this->getErrors();
		return !empty( $errors );
	}

	/**
	 * Returns all errors occurred so far
	 * Note that this will return true if isUnresolvable() is true, even though this cann still
	 * have an abstract representation instead.
	 * 
	 * @return string[]
	 */
	public final function getErrors() {
		$errors = $this->getErrors_internal();
		if( $this->isUnresolvable() ) {
			$errors = array_merge( array( 'semex-expressivestring-unresolvable' ), $errors );
		}
		return $errors;
	}

	/**
	 * Function to gather errors from the internal $value. Use this rather than overwriting getErrors()
	 * 
	 * @return string[]
	 */
	protected function getErrors_internal() {
		return array();
	}

	/**
	 * Returns whether this piece type has a meaningful abstract representation.
	 * 
	 * @return bool
	 */
	public static function hasAbstractRepresentation() {
		return false;
	}

	/**
	 * Returns what kind of piece of an expressive string this is.
	 * 
	 * @return string
	 */
	public static function getType() {
		return get_called_class();
	}

	/**
	 * Returns an instance of ExpressiveStringOutputOptions (or a descendant class engineered for
	 * this specific piece type) which holds the default output options.
	 * 
	 * @return ExpressiveStringOutputOptions
	 */
	public static function getDefaultOutputOptions() {
		return new ExpressiveStringOutputOptions();
	}

	/**
	 * Whether or not this piece has a expressive meaning, basically this is true if this is not a
	 * simple string.
	 * 
	 * @return bool
	 */
	public static function isExpressive() {
		// will return true for inherited classes but still allows them to overwrite.
		return ( static::getType() !== SEMEX_EXPR_PIECE_STRING );
	}

	/**
	 * This will initialize the piece type within a existing ExpressiveString object by examining
	 * its non-expressive parts for expressive meaning.
	 * 
	 * @param ExpressiveString $target
	 */
	public static function initWithin( ExpressiveString $target ) {
		if( ! static::isExpressive() ) {
			// unnecessary to examine a non-expressive meaning just to end up with the same!
			return;
		}
		$i = -1;
		foreach( $target->getPieces() as $piece ) {
			$i++;
			$newPieces = static::examinePiece( $piece, $target->getParser() );
			if( $newPieces === false ) {
				// no meaning in this context
				continue;
			}

			// replace old piece with its new resolved meaning:
			$target->removePieces( $i, 1 );
			$target->addPieces( $newPieces, $i );

			$i += count( $newPieces ) - 1;
		}
	}

	/**
	 * Examines a string or a ExpressiveStringPiece about whether it does match this piece type
	 * and returns an array with this type of piece detected and non-expressive information as normal
	 * ExpressiveStringPiece objects. Returns false if nothing detected.
	 * 
	 * @param string|ExpressiveStringPiece $piece
	 * @param Parser $parser
	 * @return ExpressiveStringPiece[]|false
	 */
	public final static function examinePiece( $piece, Parser $parser ) {
		if( ! $piece->isExpressive() ) {
			$piece = $piece->getRawText();
		}
		if( is_string( $piece ) ) {
			return static::examineString( $piece, $parser );
		}

		return static::examineExpressivePiece( $piece, $parser );
	}

	/**
	 * Examines a string for expressive meaning matching this piece type and returns an array
	 * with this type of piece detected and non-expressive information as normal
	 * ExpressiveStringPiece objects. Returns false if nothing detected.
	 * 
	 * @param string $string
	 * @param Parser $parser
	 * @return ExpressiveStringPiece[]|false
	 */
	protected static function examineString( $string, Parser $parser ) {
		return false;
	}

	/**
	 * Examines an piece which already has its expressive meaning detected for further meaning
	 * matching this type and returns an array of detected pieces. Returns false if nothing
	 * detected.
	 * 
	 * @param ExpressiveStringPiece $piece
	 * @param Parser $parser
	 * @return ExpressiveStringPiece[]|false
	 */
	protected static function examineExpressivePiece( ExpressiveStringPiece $piece, Parser $parser ) {
		return false;
	}
}
