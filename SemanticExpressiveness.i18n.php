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
	'semex-desc' => 'Adds a syntax for more expressive short queries',
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
 * @author Kghbln
 */
$messages['qqq'] = array(
	'semex-shortquery-title' => '<$1: semantic property name> from <$2: page the query got the property value from>',
	'semex-shortquery-title-from-ref' => '<$1: semantic property name> from reference <$2: another semantic property name of the same page>',
	'semex-shortquery-error-missing-property' => '{{identical|Property}}',
	'semex-shortquery-error-byref-has-many-values' => '{{Identical|Property}}',
	'semex-shortquery-error-byref-has-wrong-type' => '{{Identical|Property}}
{{Identical|Type}}',
);

/** German (Deutsch)
 * @author Daniel Werner
 * @author Kghbln
 */
$messages['de'] = array(
	'semex-desc' => 'Ermöglicht eine Syntax für aussagekräftigere Kurzabfragen',
	'semex-shortquery-title' => '$1 von $2',
	'semex-shortquery-title-from-ref' => '$1 von Referenz $2',
	'semex-shortquery-hover-loading' => 'Lädt …',
	'semex-shortquery-hover-loading-failed' => 'Das Laden der Inhalte, der mit der Kurzabfrage abgefragten Seiten, schlug fehl.',
	'semex-shortquery-error-missing-property' => 'Für das abgefragte Attribut wurde kein Wert angegeben.',
	'semex-shortquery-error-byref-has-many-values' => 'Das angegebene Referenzattribut verfügt über mehr als einen Wert. Lediglich der erste Wert wurde als Abfrageziel genutzt.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Das angegebene Referenzattribut sollte vom Datentyp „Seite“ sein.',
	'semex-shortquery-error-failed-nested-queries' => 'Die Kurzabfrage konnte nicht ausgeführt werden, da eine in ihr enthaltene Kurzabfrage fehlgeschlagen ist.',
	'semex-expressivestring-unresolvable' => 'Die Abfragesyntax konnte nicht verarbeitet werden.',
);

/** Italian (italiano)
 * @author Beta16
 */
$messages['it'] = array(
	'semex-desc' => 'Aggiunge una sintassi per interrogazioni brevi più espressive.',
	'semex-shortquery-title' => '$1 da $2',
	'semex-shortquery-title-from-ref' => '$1 da riferimento $2',
	'semex-shortquery-hover-loading' => 'Caricamento',
	'semex-shortquery-hover-loading-failed' => "Caricamento non riuscito della pagina di destinazione dell'interrogazione breve.",
	'semex-shortquery-error-missing-property' => 'Nessun valore definito per la proprietà interrogata.',
	'semex-shortquery-error-byref-has-many-values' => "La proprietà di riferimento indicata ha più di un valore, solo il primo è stato considerato per l'interrogazione.",
	'semex-shortquery-error-byref-has-wrong-type' => 'La proprietà di riferimento indicata dovrebbe essere di tipo "Page".',
	'semex-shortquery-error-failed-nested-queries' => "L'interrogazione breve non può essere eseguita poiché l'interrogazione breve nidificata è fallita.",
	'semex-expressivestring-unresolvable' => 'Markup non risolvibile',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'semex-desc' => 'Nagdaragdag ng isang palaugnayan para sa mas mapagpahayag na maiikling mga pagtatanong',
	'semex-shortquery-title' => '$1 mula sa $2',
	'semex-shortquery-title-from-ref' => '$1 mula sa sangguniang $2',
	'semex-shortquery-hover-loading' => 'Ikinakarga',
	'semex-shortquery-hover-loading-failed' => 'Nabigo ang pagkakarga ng nilalaman ng mga pahinang puntirya ng maiiksing mga pagtatanong.',
	'semex-shortquery-error-missing-property' => 'Walang inilarawang halaga para sa itinatanong na katangiang-ari.',
	'semex-shortquery-error-byref-has-many-values' => 'Ang ibinigay na katangiang-ari ng sanggunian ay mas mahigit kaysa sa isang halaga, tanging ang nauna lamang ang kinuha bilang pinupukol ng mga pagtatanong.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Ang ibinigay na katangiang-ari ng sanggunian ay dapat na isang may uring "Pahina".',
	'semex-shortquery-error-failed-nested-queries' => 'Hindi maisasakatuparan ang maiksing pagtatanong dahil nabigo ang nakapugad na maiksing pagtatanong.',
	'semex-expressivestring-unresolvable' => 'Hindi malulutasang pagmamarka',
);

