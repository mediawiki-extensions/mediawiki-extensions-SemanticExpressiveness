<?php

/**
 * Class representing the result of a 'Semantic Expressiveness' short query as abstract value.
 * 
 * @since 0.1
 * 
 * @file SemExShortQueryAbstractResult.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class SemExShortQueryAbstractResult extends SemExShortQueryResult {
	
	public function getAbstractResult() {
		// this is the abstract result already
		return $this;
	}
	
	/**
	 * Returns an abstract representation of the result which expresses what exactly the query is all
	 * about. Does some extra formatting for pretty wiki text compared to getAbstractRawText(). If all
	 * the short query classes and outer wrapping for the context popup functionality is needed, use
	 * getWikiText() instead.
	 * 
	 * @param mixed $linked Allows one of
	 *        SemExShortQueryOutputOptions::LINK_NONE
	 *        SemExShortQueryOutputOptions::LINK_ALL
	 *        SemExShortQueryOutputOptions::LINK_TOPIC
	 * @param bool $showErrors can be set to true to show errors. Off by default.
	 * 
	 * @return string
	 */
	public function getShortWikiText(
			$linked = SemExShortQueryOutputOptions::LINK_ALL,
			$showErrors = false
	) {
		$parts = $this->getAbstractParts( false, $linked === SemExShortQueryOutputOptions::LINK_ALL );
		$source = $parts['source'];
		$fromRef = $parts['fromref'];
		$property = $parts['property'];
		
		$out =
			'<b>&lt;</b>' .
			$this->buildAbstractText( $property, $source, $fromRef ) .
			'<b>&gt;</b>';
		
		$out = "<span class=\"abstractShortQueryValue\">$out</span>";
		
		if( $linked === SemExShortQueryOutputOptions::LINK_TOPIC ) {
			// wrap whole result in one link to the source
			$topic = $this->getSource();
			$out = "[[:{$topic}|{$out}]]";
		}
				
		if( $showErrors ) {
			// don't get any error about the queries general success, just about invalid input
			$out .= $this->getErrorTextForFormattedSQ();
		}
		
		return $out;
	}
	
	public function getWikiText(
			$linked = SemExShortQueryOutputOptions::LINK_ALL,
			$showErrors = true
	) {
		return $this->getWikiText_internal( $linked, $showErrors, true );
	}
	
	/**
	 * Returns an abstract representation of the result which expresses what exactly the query is all
	 * about.
	 * 
	 * @return string
	 */
	public function getRawText() {
		$parts = $this->getAbstractParts( true, false );
		$text = $this->buildAbstractText(
				$parts['property'],
				$parts['source'],
				$parts['fromref']
		);
		return "<$text>";
	}
	
	/**
	 * Helper function to put together the abstract string
	 */
	protected function buildAbstractText( $property, $source, $fromRef = false ) {
		if( $source === null ) {
			// query from current page
			// don't display any further info
			$ret = $property;
		} elseif( $fromRef ) {
			// query 'from ref'
			$ret = wfMsgForContent( 'semex-shortquery-title-from-ref', $property, $source );
		} else {
			// query from any other known page or expressive string
			$ret = wfMsgForContent( 'semex-shortquery-title', $property, $source );
		}
		return $ret;
	}
	
	/**
	 * Returns all necessary information part of abstract information about the query.
	 * 
	 * @param bool $raw
	 * @param bool $linked
	 * 
	 * @return string[] with keys 'property', 'fromref' and 'source'
	 */
	protected function getAbstractParts( $raw, $linked ) {
		$parts = array();
		$parts['fromref'] = false;
		
		$property = $this->query->getProperty()->getDataItem()->getLabel();		
		if( $linked ) {
			$propTitle = Title::makeTitle( SMW_NS_PROPERTY, $property );
			$property = "[[:{$propTitle->getPrefixedText()}|$property]]";
		}
		if( ! $raw ) {
			$property = "<span class=\"property\">$property</span>";
		}
		$parts['property'] = $property;		
		
		if(
			$this->getSource() !== null
			&& $this->parser->getTitle()->getPrefixedText() === $this->getSource()->getPrefixedText()
		) {
			// query from current page
			$parts['source'] = null;
		}
		elseif( $this->query->getSourceType() === SemExShortQuery::SOURCE_FROM_REF ) {
			// query 'from ref'
			$parts['fromref'] = true;
			$parts['source'] = $this->query->getSource()->getDataItem()->getLabel();			
			if( ! $raw ) {				
				$parts['source'] = "<u>{$parts['source']}</u>";
			}
		}
		else {
			if( $this->getSource() === null ) {
				if( $this->sourceResult !== null ) {
					// source from SemExShortQuery object. Put it into an expressive string so we can get
					// the expressive representation without troubles
					$source = new SemExExpressiveString( '', $this->parser, SEMEX_EXPR_PIECE_SQRESULT );
					$source->addPieces( new SemExExpressiveStringPieceSQResult(
							$this->sourceResult // take cached version so we don't have to query again
					) );
				}
				else {
					// source must be from unresolved expressive string
					$source = $this->query->getSource();
				}
				
				$source = ( $raw )
					? $source->getRawText( true )
					: $source->getWikiText( true, $linked );
			}
			else {
				// query from any other known page
				$source = $this->getSource()->getPrefixedText();
				if( $linked ) {
					$source = "[[:$source]]";
				}
			}
			$parts['source'] = $source;
		}
		if( $parts['source'] !== null && ! $raw ) {
			$parts['source'] = "<span class=\"source\">{$parts['source']}</span>";
		}
		
		return $parts;
	}
}
