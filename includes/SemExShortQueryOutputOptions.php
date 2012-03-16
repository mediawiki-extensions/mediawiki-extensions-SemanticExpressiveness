<?php

/**
 * Class for defining options for a 'Semantic Expressiveness' short query.
 * SemExShortQueryProcessor::getResultFromQuery() can be used to get a short queries result using
 * a SemExShortQuery and these options.
 * SemExShortQueryResult::getOutput() can also be used to get the formatted output specified by a
 * SemExShortQuery object.
 * 
 * @since 0.1
 * 
 * @file SemExShortQueryResultOptions.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class SemExShortQueryOutputOptions extends SemExExpressiveStringOutputOptions {
	
	protected static $pfParamsValidatorElement = 'short query output options';
	
	/**
	 * Link depending on the properties Type. Values coming from properties of Type 'Page' will
	 * display a link. If the queried value consists of multiple values or is a set of data
	 * bundled in one property, the linking will happen individually for each part if appropriate.
	 */
	const LINK_ALL = true;
	
	/**
	 * The output will be wrapped inside a link to the queries source page, even if the property
	 * value is a value of type 'Page' which would bring its own link to another page. This makes
	 * sense for properties which are not of type 'Page' but kind of represent the whole article,
	 * e.g. a 'Name' property.
	 */
	const LINK_TOPIC = 2;
	
	
	/**
	 * Defines whether the short query result should contain a link to the queried property
	 * or the target page. Following values are possible:
	 * 
	 * self::LINK_NONE  - No links at all.
	 * self::LINK_ALL   - Link depending on the properties Type. Type 'Page' will display link.
	 * self::LINK_TOPIC - The property value will be wrapped inside a link to the source page,
	 *                    even if the property value is a value of type 'Page' which would
	 *                    bring its own link to another page.
	 * 
	 * null - Now the option depends on getShowInfo(). If it is true, then this will behave if as
	 *        self::LINK_ALL were set, otherwise it will be self::LINK_NONE.
	 * 
	 * @param mixed $val
	 * @return string previous value
	 */
	public function setLink( $val ) {
		return parent::setLink( $val );
	}
	
	/**
	 * @see SemExPFParamsBasedFactory::newFromValidatedParams()
	 */
	public static function newFromValidatedParams( array $params ) {
		$sqOpt = parent::newFromValidatedParams( $params );
		
		$link = $params['link'];
		if( $link == 'topic' || $link == 'source' ) {
			$sqOpt->setLink( self::LINK_TOPIC );
		}
		
		return $sqOpt;
	}
	
	/**
	 * @see SemExExpressiveStringOutputOptions::getPFParams()
	 * 
	 * @return array
	 */
	public static function getPFParams() {
		$params = parent::getPFParams();
		
		// add 'topic' to the allowed 'link' parameter values:		
		$linkCriteria = $params['link']->getCriteria();
		$linkCriteria = array_merge( $linkCriteria[0]->getAllowedValues(), array( 'topic', 'source' ) );
		
		$newLink = new Parameter( 'link' );		
		$newLink->addCriteria( new CriterionInArray( $linkCriteria ) );
		$newLink->setDefault( $params['link']->getDefault() );
		
		// overwrite old parameter definition:
		$params['link'] = $newLink;
				
		return $params;
	}
}
