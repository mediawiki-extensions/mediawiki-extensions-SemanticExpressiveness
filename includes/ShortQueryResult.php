<?php
namespace SemEx;
use Parser, Title, HTML;
use SMWDataValueFactory;

/**
 * Class describing the result of a 'Semantic Expressiveness' short query. The result can be a
 * Semantic MediaWiki property value of a page or a abstract value in case the requested value
 * doesn't exist.
 * @ToDo: Expand this for having HTML output as well if required
 * 
 * @since 0.1
 * 
 * @file ShortQueryResult.php
 * @ingroup SemanticExpressiveness
 *
 * @licence GNU GPL v3+
 * @author Daniel Werner < danweetz@web.de >
 */
class ShortQueryResult {

	/**
	 * @var ShortQuery
	 */
	protected $query;
	/**
	 * @var Parser
	 */
	protected $parser;

	protected $errors = array();
	protected $result = null;
	protected $source = false; // null means missing source, false means not yet datermined
	protected $sourceResult = null; // in case the source is another query, this is its cached result

	/**
	 * Constructor
	 * 
	 * @param ShortQuery $query the short query the result should be datermined for. Once a
	 *        result is datermined, the query itself must not be modified.
	 * @param Parser $parser specifies the Parser context in which the result will be released as
	 *        a more human readable final output over various output functions of the object.
	 * @param SMWDataItem[] $result optionally allows to pre-define the result. If not set, the
	 *        result of the query will be queried on demand. To start the query the result
	 *        immediately, getRawResult() can be called after construction.
	 */
	function __construct( ShortQuery $query, Parser $parser, $result = null ) {
		$this->query = $query;
		$this->parser = $parser;
		$this->result = $result;
		// we query the result on demand to save resources!
	}

	/**
	 * Returns whether the result was datermined already. This is false if the result was not accessed
	 * yet. This might be of interest if the object gets passed around and the context/time the actual
	 * query process happens potentially influences the result.
	 * 
	 * @return bool
	 */
	public function queryExecuted() {
		return $this->result !== null;
	}

	/**
	 * Returns an ShortQueryAbstractResult object representing the same query.
	 * 
	 * @return ShortQueryAbstractResult
	 */
	public function getAbstractResult() {
		return new ShortQueryAbstractResult( $this->query, $this->parser, $this->result );
	}

	/**
	 * Returns the raw result as array of SMWDataItems. In case the source is not clearly
	 * specified, this will return null.
	 * 
	 * @param bool $forceRefresh if set to true, the result will be queried even though it was queried
	 *        before. Once queried, the result will be cached.
	 * @return SMWDataItem[]|null
	 */
	public function getRawResult( $forceRefresh = false ) {
		if( ! $forceRefresh && $this->queryExecuted() ) {
			return $this->result;
		}

		$source = $this->getSource();
		if( $source === null ) {
			return null;
		}

		$result = null;
		$subject = \SMWDIWikiPage::newFromTitle( $source );
		$property = $this->query->getProperty()->getDataItem();

		// @ToDo: bad idea to use cache AND query, is the cache even needed after recent SMW changes?

		if( $this->query->getUseCache()
			&& $source === $this->parser->getTitle()
		) {
			// try to get data from current parser process:
			$output = $this->parser->getOutput();

			// only possible if store is set for page yet:
			if( isset( $output->mSMWData ) ) {
				$result = $output->mSMWData->getPropertyValues( $property );
			}
		}

		if(  empty ( $result ) ) {
			// get the result from a defined SMW data store:
			$result = $this->query->getStore()->getPropertyValues( $subject, $property );
		}

		$this->result = $result;
		return $result;
	}

	/**
	 * Same as ShortQuery::getSource() except this will resolve 'by ref' and current page source
	 * types. In case the source is an invalid 'by ref' source null will be returned.
	 * 
	 * @return Title|null
	 */
	public function getSource() {
		if( $this->source !== false ) {
			return $this->source;
		}

		$q = $this->query;
		switch( $q->getSourceType() ) {
			case ShortQuery::SOURCE_IS_TITLE:
				$source = $q->getSource();
				break;

			case ShortQuery::SOURCE_FROM_REF:
				// query the source properties value which is the actual source:
				$subQ = clone( $q ); // use same options for caching and store
				$subQ->setProperty( $q->getSource() );
				$subQ->setSource( null ); // current page is source
				/*  NO BREAK! */

			case ShortQuery::SOURCE_IS_SHORTQUERY:
				if( ! isset( $subQ ) ) {
					$subQ = $q->getSource();
				}
				$subQR = new self( $subQ, $this->parser );
				$di = $subQR->getRawResult(); // returns all SMWDataItems
				$this->sourceResult = $subQR; // cache this extra

				if( ! empty( $di ) ) {
					if( count( $di > 1 ) ) {
						// There should only be one DataItem for a short query to make sense!
						$this->addError( 'semex-shortquery-error-byref-has-many-values' );
					}
					$di = $di[0]; 
				} else {
					$source = null;
					break;
				}

				if( $di->getDIType() !== SMWDataItem::TYPE_WIKIPAGE ) {
					// should be of type 'Page' to be a proper reference
					$this->addError( 'semex-shortquery-error-byref-has-wrong-type' );
					$source = null;
					break;
				}
				$source = $di->getTitle();
				break;

			case ShortQuery::SOURCE_IS_ESTRING:
				// expressive string as source, e.g. "<?a::<?b::c>>"
				$expressiveSrc = $q->getSource();

				if( $expressiveSrc->hasUnresolvablePiece() ) {
					// e.g. in case of "<?a::<?b::c>>" whereas "c" doesn't exist.
					// in this case the whole query would be falsified
					$this->addError( 'semex-shortquery-error-failed-nested-queries' );
					$source = null;
				} else {
					$source = Title::newFromText( $expressiveSrc->getRawText() );
				}
				break;

			case ShortQuery::SOURCE_FROM_CONTEXT:
				$source = $this->parser->getTitle();
				break;
		}

		$this->source = $source;
		return $source;
	}

	public function getQuery() {
		return $this->query;
	}

	public function getParser() {
		return $this->parser;
	}

	/**
	 * Returns whether the short query has no result because the requests target page or the
	 * requested property on that page do not exist. This is also true if the query definition
	 * is faulty and prevents a proper query execution.
	 * 
	 * @return bool
	 */
	public function isEmpty() {
		$result = $this->getRawResult();
		return empty( $result );
	}

	/**
	 * Returns whether there have occurred errors of any kind wile processing the query.
	 * Note that even though this can have some errors, it isn't automatically a failure. Check
	 * isEmpty() to gain information about the queries success.
	 * 
	 * @return bool
	 */
	public function hasErrors() {
		$errors = $this->getErrors();
		return !empty( $errors );
	}

	/**
	 * Returns all errors occurred so far
	 * 
	 * @return string[]
	 */
	public function getErrors() {
		$errors = $this->errors;
		if( $this->isEmpty() ) {
			// We don't use this message internally since its a ShortQuery feature to change output
			// in case this one occurs
			$errors = array_merge( array( 'semex-shortquery-error-missing-property' ), $errors );
		}
		return $errors;
	}

	/**
	 * This will add a new error or a set of error descriptions to the object.
	 *
	 * @param string|string[] addError
	 */
	protected function addError( $error ) {
		if( is_array( $error ) ) {
			$this->errors = array_merge( $this->errors, $error );
		} else {
			$this->errors[] = $error;
		}
	}

	/**
	 * Returns a string containing all error messages as a tooltip, or an empty string if no
	 * errors occurred.
	 * 
	 * @return string
	 */
	public function getErrorText() {
		return smwfEncodeMessages( $this->getErrors() );
	}

	/**
	 * Same as getErrorText() except this won't output the message about the requested property not
	 * existing on the page.
	 */
	protected function getErrorTextForFormattedSQ() {
		smwfEncodeMessages( $this->errors );
	}

	/**
	 * Returns the result as unformatted plain text. In case the result consists of several data values,
	 * all of them will be put together separated by a comma.
	 * 
	 * @return string
	 */
	public function getRawText() {
		if( $this->isEmpty() ) {
			return '';
		}
		$values = array();
		foreach( $this->getRawResult() as $dataItem ) {
			$dataValue = SMWDataValueFactory::newDataItemValue( $dataItem, null );
			$values[] = trim( $dataValue->getWikiValue() );
		}
		return implode( ', ', $values );
	}

	/**
	 * Returns the result as a short wiki text representation without informational HTML markup,
	 * even though it would be usable within wiki markup.
	 * getWikiText() can be used to get the completely formatted wiki markup
	 * 
	 * @param mixed $linked Allows one of
	 *        ShortQueryOutputOptions::LINK_NONE
	 *        ShortQueryOutputOptions::LINK_ALL
	 *        ShortQueryOutputOptions::LINK_TOPIC
	 * @param bool $showErrors can be set to true to show errors. Off by default.
	 * 
	 * @return string
	 */
	public function getShortWikiText(
			$linked = ShortQueryOutputOptions::LINK_ALL,
			$showErrors = false
	) {
		if( $this->isEmpty() ) {
			return '';
		}
		$values = array();
		foreach( $this->getRawResult() as $dataItem ) {
			$dataValue = SMWDataValueFactory::newDataItemValue( $dataItem, null );
			$values[] = trim( $dataValue->getShortWikiText( $linked === ShortQueryOutputOptions::LINK_ALL ) );
		}
		$out = implode( ', ', $values );

		if( $out !== '' && $linked === ShortQueryOutputOptions::LINK_TOPIC ) {
			// wrap whole result in one link to the source
			$topic = $this->getSource();
			$out = "[[:{$topic}|{$out}]]";
		}

		if( $showErrors ) {
			// show all errors, also the one saying property doesn't exist ($out === '')
			$out .= $this->getErrorText();
		}

		return $out;
	}

	/**
	 * Returns the full wiki text output with full markup, normally wrapped in some lightweight HTML
	 * tags marked as successful Short Query. In case the query failed, there will be some similar 
	 * output with an abstract representation of the requested value.
	 * In any case, the output will be including all information for later JavaScript processing or
	 * use with '?to?!' parser function.
	 * 
	 * @param mixed $linked Allows one of
	 *        ShortQueryOutputOptions::LINK_NONE
	 *        ShortQueryOutputOptions::LINK_ALL
	 *        ShortQueryOutputOptions::LINK_TOPIC
	 * @param bool $showErrors
	 * 
	 * @return string
	 * 
	 * @todo: rename 'value' in 'rawResult' and 'abstractValue' in 'abstractResult'
	 */
	public function getWikiText(
			$linked = ShortQueryOutputOptions::LINK_ALL,
			$showErrors = true
	) {
		return $this->getWikiText_internal( $linked, $showErrors, false );
	}

	// $enforceAbstract parameter to reduce code in 'ShortQueryAbstractResult' sub-class
	protected function getWikiText_internal( $linked, $showErrors, $enforceAbstract = false ) {
		$out = '';

		// if abstract is enforced, we won't query at all!
		$showAbstract = $enforceAbstract || $this->isEmpty();
		$sqClasses = array( 'shortQuery' );

		if( $showAbstract ) {
			$sqClasses[] = 'abstractShortQuery';
			if( ! $enforceAbstract ) {
				$sqClasses[] = 'failedShortQuery';
			}
		}

		$out .= HTML::openElement( 'span', array( 'class' => implode( ' ', $sqClasses ) ) );

		// get all important information to re-create this object:
		$out .= $this->getSerialization();

		if( ! $showAbstract ) {
			// data for formatted result:
			// ( can't quote this since it might contain html data! TODO: perhaps we could just strip this as HTML )
			// ( this is a problem when using ?to?! having some invalid stuff in here for any reason )
			$out .= HTML::rawElement( 'span', array( 'class' => 'result'),  $this->getShortWikiText( $linked, $showErrors ) );
		}
		else {
			$out .= $this->getAbstractResult()->getShortWikiText( $linked, $showErrors );
		}

		if( $showErrors && !empty( $this->errors ) ) {
			// add errors, except the one saying that the whole thing is a failure:
			$out .= HTML::rawElement( 'span', array( 'class' => 'errors' ), $this->getErrorTextForFormattedSQ() );
		}

		$out .= HTML::closeElement( 'span' );
		return $out;
	}

	/**
	 * Returns a serialized string optimazied for usage as WikiText or as HTML markup without actually
	 * producing any visible output when used properly.
	 *
	 * @Note: This could be done nicer with HTML5
	 * 
	 * @return string
	 */
	public function getSerialization() {
		// query target page:
		$source = ( $this->getSource() === null ) ? ''	: $this->getSource()->getPrefixedText();
		$out = HTML::element( 'span', array( 'class' => 'source', 'title' => $source ) );

		// queried property:
		$out .= HTML::element( 'span', array( 'class' => 'type',   'title' => $this->query->getProperty()->getDataItem()->getLabel() ) );

		$storeName = $this->query->getStoreName();
		if( $storeName !== '' ) {
			// only add information about store if default store not in use
			$out .= HTML::element( 'span', array( 'class' => 'storeSource', 'title' => $storeName ) );
		}

		if( ! $this->isEmpty() ) {
			// data for raw result:
			$rawValues = '';
			foreach( $this->getRawResult() as $dataItem ) {
				// separate all data values by putting them in their own span each
				$dataValue = SMWDataValueFactory::newDataItemValue( $dataItem, null );
				$dataValue = trim( $dataValue->getWikiValue() );
				$rawValues .= HTML::element( 'span', array( 'title' => $dataValue ) );
				/*
				 * @ToDo: FIXME: Might be a good idea to simply use DataItem default serialization,
				 *               on the other hand, this would be kind of useless for JavaScript.
				 */
			}
			$out .= HTML::rawElement( 'span', array( 'class' => 'value' ), $rawValues );
		}

		return $out;
	}

	/**
	 * Returns the output in a pre-defined exactly specified way by a ShortQueryResultOptions
	 * object.
	 * 
	 * @ToDo: implement ShortQueryOutputOptions::getFormat() if required
	 * 
	 * @return string
	 */
	public function getOutput( ShortQueryOutputOptions $options ) {
		$useRaw   = $options->getFormat() === ShortQueryOutputOptions::FORMAT_RAW;
		$showInfo = $options->getShowInfo();
		$linked   = $options->getLink();
		$errors   = $options->getShowErrors();
		$abstract = $options->getShowAbstract();

		if(	$abstract === ShortQueryOutputOptions::NO_ABSTRACT
			&& $this->isEmpty()
		) {
			// don't display abstract values, so nothing to display except...
			if( $errors && !$useRaw ) {
				// ...display errors at least, including information why this "failed"
				return $this->getErrorText();
			} else {
				return '';
			}
		}

		// if only abstract info is required, get this results abstract representation
		$abstractInUse =
				$abstract === ShortQueryOutputOptions::ABSTRACT_ONLY
				|| ( $this->isEmpty() && $abstract === ShortQueryOutputOptions::ABSTRACT_IF_FAILURE );

		$result = $abstractInUse
				? $this->getAbstractResult()
				: $this;

		if( $useRaw ) {
			return ( $result->getRawText() );
		}

		if( $showInfo ) {
			// output result wrapped with max markup and information
			return $result->getWikiText( $linked, $errors );
		}
		else {
			// light version
			return $result->getShortWikiText( $linked, $errors );
		}
	}

	/**
	 * Factory function to get an existing result from its full wiki or HTML output by reading the
	 * produced tag information.
	 * 
	 * @param DOMNode $node
	 * @param Parser $parser
	 * @param bool $refreshData whether the result should be re-queried even though the original result
	 *        is available from the given DOM information.
	 * 
	 * @return ShortQueryResult
	 */
	public static function newFromDOM( DOMNode $node, Parser $parser, $refreshData = false ) {
		$prop = self::extractInfoFromDOM( $node, 'type' );
		$source = self::extractInfoFromDOM( $node, 'source' );

		if( $prop === null || $source === null ) {
			// ERROR
			throw new ShortQueryResultException( 'Insufficient input data.' );
		}

		$origQuery = ShortQuery::newFromParamsArray( array(
				'property' => $prop,
				'from' => $source,
				'source' => self::extractInfoFromDOM( $node, 'storeSource' ),
		) );

		$result = null;

		if( ! $refreshData ) {
			$result = array();

			// fill result from DOM information:
			$origResultContainer = self::extractInfoFromDOM( $node, 'value', true );

			if( $origResultContainer->hasChildNodes() ) {
				$propDi = $origQuery->getProperty()->getDataItem();

				// each DataValue inside its own <span title="data" />
				foreach( $origResultContainer->child_nodes() as $resultNode ) {
					if( ! $resultNode->hasAttributes() ) {
						continue;
					}
					$result = trim( $resultNode->attributes->getNamedItem( 'title' ) );
					if( $result === '' ) {
						continue;
					}
					$sqResult->result[] = SMWDataValueFactory::newPropertyObjectValue(
							$propDi, $result, $caption
					);
				}
			}
		}

		return new self( $origQuery, $parser, $result );
	}

	/**
	 * Creates a new ShortQueryResult from a serialized string given by
	 * ShortQueryResult::getSerialization().
	 * 
	 * @param string $serialization
	 * @param Parser $parser
	 * 
	 * @return ShortQueryResult
	 */
	public static function newFromSerialization( $serialization, Parser $parser ) {
		$strHtml = "<body>$serialization</body>";

		$xmlDoc = new DOMDocument();
		$xmlDoc->strictErrorChecking = false;

		wfSuppressWarnings();
		$validDom = $xmlDoc->loadXML( $part );
		wfRestoreWarnings();

		if( ! $validDom ) {
			// ERROR
			throw new ShortQueryResultException( "Invalid serialized string '$serialization' given." );
		}

		return ShortQueryResult::newFromDOM( $xmlDoc->documentElement, $parser );
	}

	/**
	 * Helper for getting information about the short query result from its DOM representation.
	 * 
	 * @param DOMNode $node
	 * @param string $info
	 * @param bool $getNode if set to true, this will return the DOMNode containing the
	 *        requested information instead of just returning the 'title' attribute content.
	 * 
	 * @return string|DOMNodeList|null
	 */
	protected static function extractInfoFromDOM( DOMNode $node, $info, $getNode = false ) {
		if( ! $node->hasChildNodes() ) {
			return null;
		}
		$xpath = new DOMXpath( $node );
		$nodes = $xpath->query( "/*/*[@class=\"{$info}\"][@title][1]" );

		if( $nodes->length < 1 ) {
			return null;
		}

		$node = $nodes->item( 0 );
		if( $getNode ) {
			return $node;
		}

		$result = trim( $node->attributes->getNamedItem( 'title' ) );
		if( $result === '' ) {
			return null;
		}
		return $result;
	}
}

/**
 * Exception to be thrown when short query result creation fails due to bad input.
 */
class ShortQueryResultException extends \MWException {
}
