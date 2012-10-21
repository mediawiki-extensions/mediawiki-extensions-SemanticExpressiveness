<?php

namespace SemEx\Test;
use SemEx\ExpressiveString, \Parser;

/**
 * Tests for ExpressiveString class.
 *
 * @file
 * @since 0.1
 *
 * @ingroup SemanticExpressiveness
 * @ingroup Test
 *
 * @group SemEx
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class ExpressiveStringTest extends \MediaWikiTestCase {
	/**
	 * Tests several aspects of the expressive string. First gets all pieces of the string and tests
	 * whether they are recognized as expressive or normal strings. Then it tests a few general
	 * functions of the expressive string. The following are tested here:
	 * - getPieces()
	 * - hasExpressiveness()
	 * - getExpressivePieces()
	 *
	 * @group WikibaseUtils
	 * @dataProvider getPiecesProvider
	 */
	public function testExpressiveness( $string, $expected ) {
		$parser = new Parser();
		$exprString = new ExpressiveString( $string, $parser );
		$totalExprPieces = 0;

		$pieces = $exprString->getPieces();

		// number of expected pieces equal to actual number of pieces?
		$this->assertEquals( count( $pieces ), count( $expected ) );

		// all types as expected?
		foreach( $pieces as $key => $piece ) {
			if( is_string( $expected[ $key ] ) ) {
				$expectedType = SEMEX_EXPR_PIECE_STRING;
				$expectedText = $expected[ $key ];
			} else {
				$totalExprPieces++;
				$expectedType = $expected[ $key ][0];
				$expectedText = $expected[ $key ][1];
			}
			$this->assertEquals( $piece->gettype(), $expectedType );
			$this->assertEquals( $piece->getRawText(), $expectedText );
		}

		// test hasExpressiveness()
		$this->assertEquals( $exprString->hasExpressiveness(), $totalExprPieces > 0 );

		// test getExpressivePieces()
		$this->assertEquals(
			count( $exprString->getExpressivePieces() ),
			$totalExprPieces
		);
	}

	public function getPiecesProvider() {
		return array(
			array(
				' Abc    ',
				array( ' Abc    ' )
			),
			array(
				'<?Foo><?Baa>',
				array( array( SEMEX_EXPR_PIECE_SQ, '' ), array( SEMEX_EXPR_PIECE_SQ, '' ) )
			),
			array(
				'<?Name::<?Name>> Test',
				array( array( SEMEX_EXPR_PIECE_SQ, '' ), ' Test' )
			),
			array(
				' <?Name::Foo::Baa> Test',
				array( ' ', array( SEMEX_EXPR_PIECE_SQ, '' ), ' Test' )
			),
			array(
				'Test [[Link|Text]]   [[Link]]  ',
				array(
					'Test ',
					array( SEMEX_EXPR_PIECE_WIKILINK, 'Text' ),
					'   ',
					array( SEMEX_EXPR_PIECE_WIKILINK, 'Link' ),
					'  '
				)
			),
			array(
				'Aaa   <?Bbb>   Ccc',
				array( 'Aaa   ', array( SEMEX_EXPR_PIECE_SQ, '' ), '   Ccc' )
			),
			array(
				'<?Xxx> Yyy _ [[ZZZ]] ',
				array( array( SEMEX_EXPR_PIECE_SQ, '' ), ' Yyy _ ', array( SEMEX_EXPR_PIECE_WIKILINK, 'ZZZ' ), ' ' )
			)
		);
	}
}
