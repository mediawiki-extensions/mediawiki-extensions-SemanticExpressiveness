<?php
/**
 * File for 'SemanticExpressiveness' resourceloader modules.
 * When included this returns an array with all the modules introduced by 'SemanticExpressiveness'.
 *
 * @since 0.1
 *
 * @file Resources.php
 * @ingroup SemanticExpressiveness
 *
 * @licence GNU GPL v2+
 * @author Daniel Werner
 */
return call_user_func( function() {

	$moduleTemplate = array(
		'localBasePath' => __DIR__,
		'remoteExtPath' => 'SemanticExpressiveness/resources',
	);

	return array(
		// common styles independent from JavaScript being enabled or disabled
		'ext.semex' => $moduleTemplate + array(
			'scripts' => array(
				'ext.semex.js',
				'ext.semex.ShortQueryResult.js',
				'ext.semex.ui.js',
				'ext.semex.ui.InlineMeasurer.js',
				'ext.semex.ui.ContextPopup.js',
				'ext.semex.ui.TitledContextPopup.js',
				'ext.semex.ui.ShortQueryHover.js',
				'ext.semex.ui.ShortQueryHover.Cache.js',
				'ext.semex.ui.ShortQueryHover.initialize.js',
			),
			'styles' => array(
				'ext.semex.css',
				'ext.semex.ui.ContextPopup.css',
				'ext.semex.ui.TitledContextPopup.css',
				'ext.semex.ui.ShortQueryHover.css',
			),
			'messages' => array(
				'semex-shortquery-title',
				'semex-shortquery-hover-loading',
				'semex-shortquery-hover-loading-failed'
			)
		)
	);
} );
