<?php
namespace SemEx;
use SMWPropertyValue, Title;

/**
 * Class describing a 'Semantic Expressiveness' short query.
 * ShortQueryProcessor::getResultFromQuery() can be used to get a short queries result.
 * 
 * @since 0.1
 * 
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ShortQuery extends PFParamsBasedFactory {

	protected static $pfParamsValidatorElement = 'SemEx Short Query';

	protected $property;
	protected $source;
	protected $store = null;
	protected $useCache = true;

	const SOURCE_IS_TITLE = 1;
	const SOURCE_FROM_CONTEXT = 2; // source from current page (depending on Parser object)
	const SOURCE_FROM_REF = 3; // source from another property of the current page
	const SOURCE_IS_SHORTQUERY = 4;
	const SOURCE_IS_ESTRING = 5; // ESTRING = ExpressiveString

	/**
	 * Constructor
	 * 
	 * @param SMWPropertyValue $property the property which should be queried
	 * @param Title|SMWPropertyValue|ExpressiveString|null where the property should be queried from.
	 *        See setSource() for details.
	 */
	public function __construct( SMWPropertyValue $property, $source = null ) {
		$this->property = $property;
		$this->source = $source; // null implies current page
	}

	/**
	 * Sets the source from where a specific property should be queried from. This can consist of different
	 * types of values. The possible types and their meanings:
	 *                   Title: The titles will be searched for the source property.
	 *        SMWPropertyValue: SMW property which should be of type 'Page'. In that case the current pages
	 *                          property value will be taken as source for the query.
	 *         ShortQuery: The result of another short query is taken as source.
	 *   ExpressiveString: Source is a expressive string which can contain further short queries. This
	 *                          makes the short query highly expressive as it's possible to trace the source
	 *                          of what should be queried.
	 *                    null: implies that the property should be queried from the query processors current
	 *                          context page.
	 * 
	 * @param Title|SMWPropertyValue|ShortQuery|ExpressiveString|null $source
	 */
	public function setSource( $source ) {
		$this->source = $source;
	}

	/**
	 * Returns the source the specific property should be queried from.
	 * @return Title|SMWPropertyValue|ExpressiveString|null
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * Returns the kind of source the query will get the requested property from. the following types
	 * are possible:
	 * 
	 * self::SOURCE_IS_TITLE      - Implies that the property will be taken from a certain page
	 * self::SOURCE_FROM_REF      - Implies that the property will be taken from a page where another
	 *                              property on the queries context page refers to.
	 * self::SOURCE_FROM_CONTEXT  - Implies that the property will be taken from the page where the
	 *                              query is defined at.
	 * self::SOURCE_IS_ESTRING    - Implies that the property will be taken from an expressive string,
	 *                              possibly containing further short queries.
	 * self::SOURCE_IS_SHORTQUERY - Implies that the property will be taken from the result of another
	 *                              short query.
	 * 
	 * @return boolean
	 */
	public function getSourceType() {
		if( $this->source === null ) {
			return self::SOURCE_FROM_CONTEXT;
		}
		if( $this->source instanceof SMWPropertyValue ) {
			return self::SOURCE_FROM_REF;
		}
		if( $this->source instanceof self ) {
			return self::SOURCE_IS_SHORTQUERY;
		}
		if( $this->source instanceof ExpressiveString ) {
			return self::SOURCE_IS_ESTRING;
		}
		return self::SOURCE_IS_TITLE;
	}

	/**
	 * Function to define for which property should be queried.
	 * @param SMWPropertyValue $property
	 */
	public function setProperty( SMWPropertyValue $property ) {
		$this->property = $property;
	}

	/**
	 * Returns the property for which the query is asking.
	 * @return SMWPropertyValue
	 */
	public function getProperty() {
		return $this->property;
	}

	/**
	 * Defines whether queries having the same source as the page they are defined on should
	 * consider properties collected during the page rendering which are not stored within the
	 * database yet.
	 * 
	 * @param bool $val
	 * @return bool previous value
	 */
	public function setUseCache( $val ) {
		return wfSetVar( $this->useCache,  $val );
	}

	/**
	 * @see ShortQuery::setUseCache()
	 * @return bool
	 */
	public function getUseCache() {
		return $this->useCache;
	}

	/**
	 * Sets the Store which should be the source for the query.
	 * Consider that depending on the cache option, the store won't even have an effect.
	 * 
	 * @param SMWStore $val
	 * @return SMWStore
	 */
	public function setStore( SMWStore $val ) {
		return wfSetVar( $this->store, $val );
	}

	/**
	 * @see ShortQuery::setStore()
	 * @return SMWStore
	 */
	public function getStore() {
		if( $this->store === null ) {
			$this->store = smwfGetStore();
		}
		return $this->store;
	}

	/**
	 * Returns the associated store name which identifies the store within $smwgQuerySources
	 * A empty string refers to the default store. Returns false in case the store in use is
	 * not known to $smwgQuerySources (which should not happen normally)
	 * 
	 * @return string|false
	 */
	public function getStoreName() {
		if( $this->store === null
			|| $this->store === smwfGetStore()
		) {
			return ''; // default store
		}

		global $smwgQuerySources;

		$storeClass = get_class( $this->store );
		$index = array_search( $storeClass, $smwgQuerySources ); // false if unknown

		return $index;
	}

	/**
	 * @see PFParamsBasedFactory::newFromValidatedParams()
	 */
	public static function newFromValidatedParams( array $params ) {
		$query = new self( $params['property'] );

		if( $params['from ref'] ) {
			$query->setSource( $params['from ref'] );
		}
		elseif( $params['from'] ) {
			$query->setSource( $params['from'] );
		}

		$query->setUseCache( $params['cache'] );
		$query->setStore( $params['source'] );

		return $query;
	}

	/**
	 * Returns a description of all allowed function Parameters representing a ShortQuery.
	 */
	public static function getPFParams() {
		$params = array();

		$params['property'] = new Parameter( 'property' );
		$params['property']->addCriteria( new CriterionIsProperty() );
		$params['property']->addManipulations( new ParamManipulationProperty() );

		$params['from'] = new Parameter( 'from', Parameter::TYPE_TITLE );
		$params['from']->setDefault( false, false );

		$params['from ref'] = new Parameter( 'from ref' );
		$params['from ref']->addCriteria( // only allow properties of type 'Page' for this!
			new CriterionIsProperty( '_wpg' )
		);
		$params['from ref']->addManipulations( new ParamManipulationProperty() );
		$params['from ref']->setDefault( false, false );

		$params['cache'] = new Parameter( 'cache', Parameter::TYPE_BOOLEAN );
		$params['cache']->setDefault( true );

		// this has nothing to do with set/getSource but delivers the value for set/getStore:
		$params['source'] = new Parameter( 'source' );
		$params['source']->addCriteria( new CriterionIsQuerySource() );
		$params['source']->addManipulations( new ParamManipulationQuerySource() );
		$params['source']->setDefault( smwfGetStore(), false );

		return $params;
	}
}
