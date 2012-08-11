<?php
namespace SemEx;

/**
 * Hooks used by 'SemanticExpressiveness'
 *
 * @since 0.1
 *
 * @file SemanticExpressiveness.hooks.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */

Class Hooks {
	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/OutputPageParserOutput
	 */
	public static function onOutputPageParserOutput( \OutputPage &$out, \ParserOutput $parseroutput ) {
		// load CSS and JavaScript
		// we can't add this just within ShortQueryResult since it's possible to get a
		// short query result from another page which is stored within a SMW property!
		$out->addModules( 'ext.semex' );
		return true;
	}

	/*
	 * parses the whole articles wikitext for SemEx special syntax
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/InternalParseBeforeSanitize
	 * only works with MW 1.20
	 *
	 * @param \Parser &$parser
	 * @param string &$text
	 */
	public static function onInternalParseBeforeSanitize( \Parser &$parser, &$text ) {
		$exprString = new ExpressiveString( $text, $parser, SEMEX_EXPR_PIECE_SQ );
		$text = $exprString->getWikiText();
		return true;
	}
}