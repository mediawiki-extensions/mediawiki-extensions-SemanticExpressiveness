<?php

/**
 * 'Semantic Expressiveness' introduces a short hand query syntax to query single values from the
 * same or another page. It aims to further reduce redundancy and to make wikitext and query
 * outputs more expressive by adding information where queried values come from.
 * 
 * Documentation: https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * Support:       https://www.mediawiki.org/wiki/Extension_talk:Semantic_Expressiveness
 * Source code:   https://svn.wikimedia.org/viewvc/mediawiki/trunk/extensions/SemanticExpressiveness
 * 
 * @version: 0.1 alpha
 * @license: ISC License
 * @author: Daniel Werner < danweetz@web.de >
 *
 * @file SemanticExpressiveness.php
 * @ingroup SemanticExpressiveness
 */

if( !defined( 'MEDIAWIKI' ) ) { die(); }

$wgExtensionCredits[ defined( 'SEMANTIC_EXTENSION_TYPE' ) ? 'semantic' : 'other' ][] = array(
	'path'           => __FILE__,
	'name'           => 'Semantic Expressiveness',
	'descriptionmsg' => 'semex-desc',
	'version'        => ExtSemEx::VERSION,
	'author'         => '[https://www.mediawiki.org/wiki/User:Danwe Daniel Werner]',
	'url'            => 'https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness',
);

$wgHooks['ParserFirstCallInit'   ][] = 'ExtSemEx::init';

// language files:
$wgExtensionMessagesFiles['SemEx'     ] = ExtSemEx::getDir() . '/SemanticExpressiveness.i18n.php';
$wgExtensionMessagesFiles['SemExMagic'] = ExtSemEx::getDir() . '/SemanticExpressiveness.i18n.magic.php';

// resources inclusion:
ExtSemEx::registerResourceModules();
$wgHooks['OutputPageParserOutput'][]      = 'ExtSemEx::onOutputPageParserOutput';
$wgHooks['InternalParseBeforeSanitize'][] = 'ExtSemEx::onInternalParseBeforeSanitize';


$incDir = ExtSemEx::getDir() . '/includes/';

// general inclusions:
$wgAutoloadClasses['SemExExpressiveString'             ] = $incDir . 'SemExExpressiveString.php';
$wgAutoloadClasses['SemExExpressiveStringPiece'        ] = $incDir . 'SemExExpressiveStringPiece.php';
$wgAutoloadClasses['SemExExpressiveStringPieceByRegex' ] = $incDir . 'SemExExpressiveStringPieceByRegex.php';
$wgAutoloadClasses['SemExExpressiveStringPieceSQ'      ] = $incDir . 'SemExExpressiveStringPieceSQ.php';
$wgAutoloadClasses['SemExExpressiveStringPieceSQResult'] = $incDir . 'SemExExpressiveStringPieceSQResult.php';
$wgAutoloadClasses['SemExExpressiveStringPieceWikiLink'] = $incDir . 'SemExExpressiveStringPieceWikiLink.php';
$wgAutoloadClasses['SemExExpressiveStringOutputOptions'] = $incDir . 'SemExExpressiveStringOutputOptions.php';
$wgAutoloadClasses['SemExShortQuery'                   ] = $incDir . 'SemExShortQuery.php';
$wgAutoloadClasses['SemExShortQueryProcessor'          ] = $incDir . 'SemExShortQueryProcessor.php';
$wgAutoloadClasses['SemExShortQueryResult'             ] = $incDir . 'SemExShortQueryResult.php';
$wgAutoloadClasses['SemExShortQueryAbstractResult'     ] = $incDir . 'SemExShortQueryAbstractResult.php';
$wgAutoloadClasses['SemExShortQueryOutputOptions'      ] = $incDir . 'SemExShortQueryOutputOptions.php';
$wgAutoloadClasses['SemExPFParamsBasedFactory'         ] = $incDir . 'SemExPFParamsBasedFactory.php';

// validator stuff:
$wgAutoloadClasses['SemExCriterionIsProperty'         ] = $incDir . 'validation/SemExCriterionIsProperty.php';
$wgAutoloadClasses['SemExCriterionIsQuerySource'      ] = $incDir . 'validation/SemExCriterionIsQuerySource.php';
$wgAutoloadClasses['SemExParamManipulationProperty'   ] = $incDir . 'validation/SemExParamManipulationProperty.php';
$wgAutoloadClasses['SemExParamManipulationQuerySource'] = $incDir . 'validation/SemExParamManipulationQuerySource.php';

// Parser function initializations:
$wgAutoloadClasses['SemExQueryPF'           ] = $incDir . 'parserhooks/SemExQueryPF.php';
$wgAutoloadClasses['SemExPlainQueryPF'      ] = $incDir . 'parserhooks/SemExPlainQueryPF.php';
$wgAutoloadClasses['SemExExpressiveStringPF'] = $incDir . 'parserhooks/SemExExpressiveStringPF.php';

$wgHooks['ParserFirstCallInit'][] = 'SemExExpressiveStringPF::staticInit';

unset( $incDir );


define( 'SEMEX_EXPR_PIECE_STRING',   SemExExpressiveStringPiece::getType() );
define( 'SEMEX_EXPR_PIECE_SQRESULT', SemExExpressiveStringPieceSQResult::getType() );
define( 'SEMEX_EXPR_PIECE_SQ',       SemExExpressiveStringPieceSQ::getType() );
define( 'SEMEX_EXPR_PIECE_WIKILINK', SemExExpressiveStringPieceWikiLink::getType() );


class ExtSemEx {
	
	const VERSION = '0.1 alpha';
	
	static function init( &$parser ) {
		$parser->setFunctionHook( '?',  array( 'SemExQueryPF', 'render' ), SFH_NO_HASH );
		$parser->setFunctionHook( '?!', array( 'SemExPlainQueryPF', 'render' ), SFH_NO_HASH );
		//$parser->setFunctionHook( '?to?!', array( __CLASS__, 'parserFunc_QueryToPlainQuery' ), SFH_NO_HASH );
		return true;
	}
	
	/**
	 * Returns the extensions base installation directory.
	 * @return string
	 */
	public static function getDir() {
		static $dir = null;		
		if( $dir === null ) {
			$dir = dirname( __FILE__ );
		}
		return $dir;
	}
		
	/**
	 * Get the RPG-Dev-Tools installation directory path as seen from the web.
	 * @return string
	 */
	public static function getScriptPath() {
		static $path = null;	
		if( $path === null ) {
			global $wgVersion, $wgScriptPath, $wgExtensionAssetsPath;
			
			$dir = str_replace( '\\', '/', self::getDir() );
			$dirName = substr( $dir, strrpos( $dir, '/' ) + 1 );
			
			$path = (
				( version_compare( $wgVersion, '1.16', '>=' ) && isset( $wgExtensionAssetsPath ) && $wgExtensionAssetsPath )
					? $wgExtensionAssetsPath
					: $wgScriptPath . '/extensions'
			) . "/$dirName";
		}
		return $path;
	}
	
	/**
	 * Registers JavaScript and CSS to ResourceLoader.
	 */
	public static function registerResourceModules() {
		global $wgResourceModules;
		
		$moduleTemplate = array(
			'localBasePath' => self::getDir(),
			'remoteBasePath' => self::getScriptPath(),
			'group' => 'ext.semex'
		);
		$wgResourceModules['ext.semex'] = $moduleTemplate + array(
			'scripts' => array(
				'resources/ext.semex.js',
				'resources/ext.semex.ShortQueryResult.js',
				'resources/ext.semex.ui.js',
				'resources/ext.semex.ui.InlineMeasurer.js',
				'resources/ext.semex.ui.ContextPopup.js',
				'resources/ext.semex.ui.TitledContextPopup.js',
				'resources/ext.semex.ui.ShortQueryHover.js',
				'resources/ext.semex.ui.ShortQueryHover.Cache.js',
				'resources/ext.semex.ui.ShortQueryHover.initialize.js',
			),
			'styles' => array(
				'resources/ext.semex.css',
				'resources/ext.semex.ui.ContextPopup.css',
				'resources/ext.semex.ui.TitledContextPopup.css',
				'resources/ext.semex.ui.ShortQueryHover.css',
			),
			'messages' => array(
				'semex-shortquery-title',
				'semex-shortquery-hover-loading',
				'semex-shortquery-hover-loading-failed'
			),
		);
	}
	
	public static function onOutputPageParserOutput() {
		// load CSS and JavaScript
		// we can't add this just within SemExShortQueryResult since it's possible to get a
		// short query result from another page which is stored within a SMW property!
		global $wgOut;
		$wgOut->addModules( 'ext.semex' );	
		return true;
	}
	
	/*
	 * NOTE: this hook requires the fix for bug #34678, https://bugzilla.wikimedia.org/show_bug.cgi?id=34678
	 */
	public static function onInternalParseBeforeSanitize( Parser &$parser, &$text ) {		
		$exprString = new SemExExpressiveString( $text, $parser, SEMEX_EXPR_PIECE_SQ );
		$text = $exprString->getWikiText();
				
		/*
		 * Sanitize the whole thing, otherwise HTML and JS code injection would be possible.
		 * Basically the same is happening in Parser::internalParse() right before 'InternalParseBeforeLinks' hook is called.
		 */
		/*
		$text = Sanitizer::removeHTMLtags(
				$text,
				array( &$parser, 'attributeStripCallback' ),
				false,
				array_keys( $parser->mTransparentTagHooks )
		);
		 */
		return true;
	}
}
