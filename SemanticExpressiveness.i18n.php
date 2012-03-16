<?php
/**
 * Internationalisation file for 'SemanticExpressiveness' extension.
 *
 * @ingroup SemanticExpressiveness
 * @author Daniel Werner < danweetz@web.de >
 */

$messages = array();

/** English
 * @author Daniel Werner
 */
$messages['en'] = array(
	'semex-desc' => 'Adds a syntax for more expressive short queries.',
	'semex-shortquery-title' => '$1 from $2',
	'semex-shortquery-title-from-ref' => '$1 from ref $2',
	'semex-shortquery-hover-loading' => 'Loading',
	'semex-shortquery-hover-loading-failed' => 'Loading the short queries target pages content failed.',
	'semex-shortquery-error-missing-property' => 'No value defined for the queried property.',
	'semex-shortquery-error-byref-has-many-values' => 'The given reference property has more than one value, only the first one was taken as the queries target.',
	'semex-shortquery-error-byref-has-wrong-type' => 'The given reference property should be one of type "Page".',
	'semex-shortquery-error-failed-nested-queries' => 'Short query can not be executed because nested short query failed.',
	'semex-expressivestring-unresolvable' => 'Unresolvable markup',
);

/** Message documentation (Message documentation)
 * @author Daniel Werner
 */
$messages['qqq'] = array(
	'semex-shortquery-title' => '<$1: semantic property name> from <$2: page the query got the property value from>',
	'semex-shortquery-title-from-ref' => '<$1: semantic property name> from reference <$2: another semantic property name of the same page>',
);

/** German (Deutsch)
 * @author Daniel Werner
 */
$messages['de'] = array(
	'semex-shortquery-title' => '$1 von $2',
	'semex-shortquery-title-from-ref' => '$1 von Ref $2',
	'semex-shortquery-hover-loading' => 'Läd<i>t</i>',
	'semex-shortquery-hover-loading-failed' => 'Laden des Inhalts der Quell-Seite der Kurz-Abfrage schlug fehl.',
);
