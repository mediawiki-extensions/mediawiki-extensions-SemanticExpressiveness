<?php
namespace SemEx;

/**
 * Hooks used by 'SemanticExpressiveness'
 *
 * @since 0.1
 *
 * @file
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */

Class Hooks {
	/**
	 * For requesting resource loader module for CSS and JavaScript.
	 * NOTE: We can't add this just within SemExShortQueryResult since it's possible to get a
	 *       short query result from another page which is stored within a SMW property!
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/OutputPageParserOutput
	 *
	 * @param \OutputPage &$out
	 * @param \ParserOutput $parseroutput
	 * @return bool true
	 */
	public static function onOutputPageParserOutput( \OutputPage &$out, \ParserOutput $parseroutput ) {
		$out->addModules( 'ext.semex' );
		return true;
	}

	/**
	 * Parses the whole articles wikitext for SemEx special syntax
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/InternalParseBeforeSanitize
	 * (new hook in MW 1.20)
	 *
	 * @since 0.1
	 *
	 * @param \Parser &$parser
	 * @param string &$text
	 *
	 * @return boolean
	 */
	public static function onInternalParseBeforeSanitize( \Parser &$parser, &$text ) {
		$exprString = new ExpressiveString( $text, $parser, SEMEX_EXPR_PIECE_SQ );
		$text = $exprString->getWikiText();
		return true;
	}

	/**
	 * Register PHPUnit tests.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList
	 *
	 * @since 0.1
	 *
	 * @param array &$files
	 *
	 * @return boolean
	 */
	public static function registerUnitTests( array &$files ) {
		// @codeCoverageIgnoreStart
		$testFiles = array(
			'ExpressiveString',
		);

		foreach ( $testFiles as $file ) {
			$files[] = __DIR__ . "/tests/phpunit/{$file}Test.php";
		}

		return true;
		// @codeCoverageIgnoreEnd
	}
}