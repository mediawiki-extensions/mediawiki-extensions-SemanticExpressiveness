<?php
namespace SemEx;

/**
 * Class for defining options for expressive strings output.
 * ExpressiveString::getOutput() can be used to get the formatted output in the form defined by
 * a ExpressiveStringOutputOptions object.
 * 
 * @since 0.1
 * 
 * @file ExpressiveStringOutputOptions.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveStringOutputOptions extends PFParamsBasedFactory {

	protected static $pfParamsValidatorElement = 'expressive string output options';

	/**
	 * With this format output will be printed as suitable MediaWiki wikitext markup.
	 */
	const FORMAT_WIKITEXT = 1;
	/**
	 * If this is set as format, all links, errors and markup of any kind will not be printed even
	 * though the other options are set accordingly.
	 */
	const FORMAT_RAW = 2;
	/*
	 * @todo: HTML support not yet implemented in any output printer, include this if it should ever
	 *        be required.
	 */
	//const FORMAT_HTML = 3;


	/**
	 * Implies that there will be no links at all within the output.
	 */
	const LINK_NONE  = false;

	/**
	 * Implies that links will be printed within the output.
	 */
	const LINK_ALL   = true;


	/**
	 * The output will be in its abstract representation if a failure has occurred.
	 */
	const ABSTRACT_IF_FAILURE = true;

	/**
	 * The output will never have an abstract representation, even if a failure has occurred. Normally
	 * an empty string will be returned instead.
	 */
	const NO_ABSTRACT = false;

	/**
	 * This can be used to enforce abstract output.
	 */
	const ABSTRACT_ONLY = 2;


	protected $link = self::LINK_ALL;
	protected $showInfo = true;
	protected $showAbstract = null;
	protected $showErrors = null;
	protected $format = self::FORMAT_WIKITEXT;

	/**
	 * Merges options of another ExpressiveStringOutputOptions object with this objects options.
	 * 
	 * @note This is only save when merging the object with an ancestor or equal instance but should
	 *       not be done with objects of a descendant option class.
	 * 
	 * @param ExpressiveStringOutputOptions $options
	 */
	public function mergeOptions( ExpressiveStringOutputOptions $options ) {
		$options = get_object_vars( $options );
		foreach( $options as $var => $val ) {
			$this->{ $var } = $val;
		}
	}

	/**
	 * Defines whether the output should contain links. The following values are possible:
	 * 
	 * self::LINK_NONE  - No links at all.
	 * self::LINK_ALL   - Links will be used where possible.
	 * 
	 * null - Now the option depends on getShowInfo(). If it is true, then this will behave if as
	 *        self::LINK_ALL were set, otherwise it will be self::LINK_NONE.
	 * 
	 * @param mixed $val
	 * @return string previous value
	 */
	public function setLink( $val ) {
		return wfSetVar( $this->link, $val );
	}

	/**
	 * Defines whether the expressive piece should be formatted as such and display all the
	 * available abstract information in case it fails.
	 * This is sort of a group-option setting default behavior for various other options which
	 * consider this option as their default but also can overwrite this explicitly.
	 * 
	 * @param bool $val
	 * @return bool previous value
	 */
	public function setShowInfo( $val ) {
		return wfSetVar( $this->showInfo, $val );
	}

	/**
	 * Defines whether the abstract representation should be displayed if an error occurs or if
	 * perhaps only the abstract value should be returned. The following four options are allowed:
	 * 
	 * self::ABSTRACT_IF_FAILURE - The abstract value will be displayed if an error occurs.
	 * self::NO_ABSTRACT         - Abstract value will never be displayed, even on failure.
	 * self::ABSTRACT_ONLY       - Only the abstract value will be returned.
	 * 
	 * null - Now the option depends on getShowInfo(). If it is true, then this will behave as if
	 *        self::ABSTRACT_IF_FAILURE were set, otherwise it will be self::NO_ABSTRACT.
	 * 
	 * @param mixed|null $val
	 * @return mixed|null previous value
	 */
	public function setShowAbstract( $val ) {
		return wfSetVar( $this->showAbstract, $val );
	}

	/**
	 * Defines whether the output should reveal any eventual errors as messages. By default and if
	 * null is set, the behavior will depend on and equal getShowInfo().
	 * 
	 * @param bool|null $val
	 * @return bool|null previous value
	 */
	public function setShowErrors( $val ) {
		return wfSetVar( $this->showErrors, $val );
	}

	/**
	 * Defines the output format.
	 * 
	 * @param integer $val Allows to set one of the follwoing output formats:
	 * 
	 * self::FORMAT_WIKITEXT - The output will be returned as wikitext (default)
	 * self::FORMAT_HTML     - The output will be formatted and properly escaped to be used
	 *                         directly in HTML (not implemented yet!)
	 * 
	 * @return integer previous value
	 */
	public function setFormat( $val ) {
		return wfSetVar( $this->format, $val );
	}

	public function getShowInfo()  { return $this->showInfo; }
	public function getFormat()    { return $this->format; }

	/**
	 * Returns whether links should be generated. By default this equals getShowInfo().
	 * 
	 * @return mixed previous value
	 */
	public function getLink() {
		if( $this->link !== null ) {
			return $this->link;
		}
		// by default show links when displaying infos
		return $this->showInfo;
	}

	/**
	 * Returns whether the abstract representation should be displayed if the query goes wrong or
	 * if perhaps only the abstract value should be returned without querying at all.
	 * By default this depends on and equals the value of getShowInfo()
	 * 
	 * @return mixed
	 */
	public function getShowAbstract() {
		if( $this->showErrors !== null ) {
			return $this->showErrors;
		}
		// by default show abstract when displaying infos
		return $this->showInfo;
	}

	/**
	 * Returns whether Errors should be displayed for the output.
	 * By default this depends on and equals the value of getShowInfo()
	 * 
	 * @return bool
	 */
	public function getShowErrors() {
		if( $this->showErrors !== null ) {
			return $this->showErrors;
		}
		// by default show errors when displaying infos
		return $this->showInfo;
	}


	/**
	 * @see PFParamsBasedFactory::newFromValidatedParams()
	 */
	public static function newFromValidatedParams( array $params ) {
		$sqOpt = new static();

		$format = $params['format'];
		switch( $format ) {
			case 'raw':
				$format = self::FORMAT_RAW;
				break;
			case 'wiki':
			default:
				$format = self::FORMAT_WIKITEXT;
				break;
		}

		$link = $params['link'];
		switch( $link ) {
			case 'all':
			case 'show':
			case 'subject':
				$link = self::LINK_ALL;
				break;
			case 'none':
				$link = self::LINK_NONE;
				break;
			case 'auto':
			default:
				// links only when displaying info
				$link = null;
		}

		$abstract = $params['abstract'];
		switch( $abstract ) {
			case 'show':
				$abstract = self::ABSTRACT_IF_FAILURE;
				break;
			case 'hide':
				$abstract = self::NO_ABSTRACT;
				break;
			case 'only':
				$abstract = self::ABSTRACT_ONLY;
				break;
			case 'auto':
			default:
				// abstract info only when displaying info
				$abstract = null;
		}

		$errors = $params['errors'];
		switch( $errors ) {
			case 'show':
				$errors = true;
				break;
			case 'hide':
				$errors = false;
				break;
			case 'auto':
			default:
				// show errors only when displaying info
				$errors = null;
		}

		$sqOpt->setFormat( $format );
		$sqOpt->setLink( $link );
		$sqOpt->setShowInfo( $params['info'] );
		$sqOpt->setShowAbstract( $abstract );
		$sqOpt->setShowErrors( $errors );

		return $sqOpt;
	}

	/**
	 * Returns a description of all allowed function Parameters representing ShortQueryResultOptions.
	 * 
	 * @return array
	 */
	public static function getPFParams() {
		$params = array();

		$params['format'] = new Parameter( 'format' );
		$params['format']->addCriteria( new CriterionInArray( 'wiki', 'raw' ) );
		$params['format']->setDefault( 'wiki' );

		$params['info'] = new Parameter( 'info', Parameter::TYPE_BOOLEAN );
		$params['info']->setDefault( true );

		$params['link'] = new Parameter( 'link' );
		$params['link']->addCriteria( new CriterionInArray( 'all', 'show', 'subject', 'none', 'auto' ) );
		$params['link']->setDefault( 'auto' );

		$params['abstract'] = new Parameter( 'abstract' );
		$params['abstract']->addCriteria( new CriterionInArray( 'only', 'show', 'hide', 'auto' ) );
		$params['abstract']->setDefault( 'auto' );

		$params['errors'] = new Parameter( 'errors' );
		$params['errors']->addCriteria( new CriterionInArray( 'show', 'hide', 'auto' ) );
		$params['errors']->setDefault( 'auto' );

		return $params;
	}
}
