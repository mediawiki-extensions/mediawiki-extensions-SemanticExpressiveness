<?php

/**
 * Class for ?!' short query parser function
 * 
 * @since 0.1
 * 
 * @file SemExPlainQueryPF.php
 * @ingroup SemanticExpressiveness
 *
 * @author Daniel Werner < danweetz@web.de >
 */
class SemExPlainQueryPF extends SemExQueryPF {
	public static function getParameters() {
		$params = parent::getParameters();
		$params['format']->setDefault( 'raw' );
		return $params;
	}
}
