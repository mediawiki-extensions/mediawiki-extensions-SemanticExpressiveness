<?php
namespace SemEx;

/**
 * 'Semantic Expressiveness' introduces a short hand query syntax to query single values from the
 * same or another page. It aims to further reduce redundancy and to make wikitext and query
 * outputs more expressive by adding information where queried values come from.
 *
 * Documentation: https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness
 * Support:       https://www.mediawiki.org/wiki/Extension_talk:Semantic_Expressiveness
 * Source code:   https://phabricator.wikimedia.org/diffusion/ESEX/
 *
 * @license: ISC License
 * @author: Daniel Werner < danweetz@web.de >
 *
 * @file
 * @ingroup SemanticExpressiveness
 */

// Ensure that the script cannot be executed outside of MediaWiki.
if ( !defined( 'MEDIAWIKI' ) ) {
    die( 'This is an extension to MediaWiki and cannot be run standalone.' );
}

// Display extension properties on MediaWiki.
$wgExtensionCredits['semantic'][] = array(
	'path' => __FILE__,
	'name' => 'Semantic Expressiveness',
	'descriptionmsg' => 'semex-desc',
	'version' => Ext::VERSION,
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Danwe Daniel Werner]',
		'...'
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness',
	'license-name' => 'ISC'
);

// register hooks:
$wgHooks['ParserFirstCallInit'][] = 'SemEx\Ext::init';

// language files:
$wgMessagesDirs['SemanticExpressiveness'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['SemExMagic'] = Ext::getDir() . '/SemanticExpressiveness.i18n.magic.php';

// Resource Loader Modules:
$wgResourceModules = array_merge( $wgResourceModules, include( Ext::getDir() . '/resources/Resources.php' ) );

// Hooks:
$wgAutoloadClasses['SemEx\Hooks'] = Ext::getDir() . '/SemanticExpressiveness.hooks.php';
$wgHooks['OutputPageParserOutput'][]      = 'SemEx\Hooks::onOutputPageParserOutput';
$wgHooks['InternalParseBeforeSanitize'][] = 'SemEx\Hooks::onInternalParseBeforeSanitize';
$wgHooks['UnitTestsList'][]               = 'SemEx\Hooks::registerUnitTests';


$incDir = Ext::getDir() . '/includes/';

// general inclusions:
$wgAutoloadClasses['SemEx\ExpressiveString'             ] = $incDir . 'ExpressiveString.php';
$wgAutoloadClasses['SemEx\ExpressiveStringPiece'        ] = $incDir . 'ExpressiveStringPiece.php';
$wgAutoloadClasses['SemEx\ExpressiveStringPieceByRegex' ] = $incDir . 'ExpressiveStringPieceByRegex.php';
$wgAutoloadClasses['SemEx\ExpressiveStringPieceSQ'      ] = $incDir . 'ExpressiveStringPieceSQ.php';
$wgAutoloadClasses['SemEx\ExpressiveStringPieceSQResult'] = $incDir . 'ExpressiveStringPieceSQResult.php';
$wgAutoloadClasses['SemEx\ExpressiveStringPieceWikiLink'] = $incDir . 'ExpressiveStringPieceWikiLink.php';
$wgAutoloadClasses['SemEx\ExpressiveStringOutputOptions'] = $incDir . 'ExpressiveStringOutputOptions.php';
$wgAutoloadClasses['SemEx\ShortQuery'                   ] = $incDir . 'ShortQuery.php';
$wgAutoloadClasses['SemEx\ShortQueryProcessor'          ] = $incDir . 'ShortQueryProcessor.php';
$wgAutoloadClasses['SemEx\ShortQueryResult'             ] = $incDir . 'ShortQueryResult.php';
$wgAutoloadClasses['SemEx\ShortQueryResultException'    ] = $incDir . 'ShortQueryResult.php';
$wgAutoloadClasses['SemEx\ShortQueryAbstractResult'     ] = $incDir . 'ShortQueryAbstractResult.php';
$wgAutoloadClasses['SemEx\ShortQueryOutputOptions'      ] = $incDir . 'ShortQueryOutputOptions.php';
$wgAutoloadClasses['SemEx\PFParamsBasedFactory'         ] = $incDir . 'PFParamsBasedFactory.php';

// Validator stuff:
$wgAutoloadClasses['SemEx\CriterionIsProperty'         ] = $incDir . 'validation/CriterionIsProperty.php';
$wgAutoloadClasses['SemEx\CriterionIsQuerySource'      ] = $incDir . 'validation/CriterionIsQuerySource.php';
$wgAutoloadClasses['SemEx\ParamManipulationProperty'   ] = $incDir . 'validation/ParamManipulationProperty.php';
$wgAutoloadClasses['SemEx\ParamManipulationQuerySource'] = $incDir . 'validation/ParamManipulationQuerySource.php';

// Parser function initializations:
$wgAutoloadClasses['SemEx\QueryPF'           ] = $incDir . 'parserhooks/QueryPF.php';
$wgAutoloadClasses['SemEx\PlainQueryPF'      ] = $incDir . 'parserhooks/PlainQueryPF.php';
$wgAutoloadClasses['SemEx\ExpressiveStringPF'] = $incDir . 'parserhooks/ExpressiveStringPF.php';

$wgHooks['ParserFirstCallInit'][] = 'SemEx\ExpressiveStringPF::staticInit';

unset( $incDir );


define( 'SEMEX_EXPR_PIECE_STRING',   ExpressiveStringPiece::getType() );
define( 'SEMEX_EXPR_PIECE_SQRESULT', ExpressiveStringPieceSQResult::getType() );
define( 'SEMEX_EXPR_PIECE_SQ',       ExpressiveStringPieceSQ::getType() );
define( 'SEMEX_EXPR_PIECE_WIKILINK', ExpressiveStringPieceWikiLink::getType() );


class Ext {

	const VERSION = '0.3.0-alpha';

	static function init( &$parser ) {
		$parser->setFunctionHook( '?',  array( 'SemEx\QueryPF', 'render' ), \Parser::SFH_NO_HASH );
		$parser->setFunctionHook( '?!', array( 'SemEx\PlainQueryPF', 'render' ), \Parser::SFH_NO_HASH );
		//$parser->setFunctionHook( '?to?!', array( __CLASS__, 'parserFunc_QueryToPlainQuery' ), \Parser::SFH_NO_HASH );
		return true;
	}

	/**
	 * Returns the extensions base installation directory.
	 * @return string
	 */
	public static function getDir() {
		static $dir = __DIR__;
		return $dir;
	}
}
