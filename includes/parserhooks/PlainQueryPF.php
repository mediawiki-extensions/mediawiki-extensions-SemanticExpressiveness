<?php
namespace SemEx;

/**
 * Class for ?!' short query parser function
 * 
 * @since 0.1
 * 
 * @file PlainQueryPF.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class PlainQueryPF extends QueryPF {
	public static function getParameters() {
		$params = parent::getParameters();
		$params['format']->setDefault( 'raw' );
		return $params;
	}
}
