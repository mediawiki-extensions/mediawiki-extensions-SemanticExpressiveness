<?php
namespace SemEx;

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
 * @author Siebrand
 */
$messages['qqq'] = array(
	'semex-shortquery-title' => 'Parameters:
* $1: semantic property name
* $2: page the query got the property value from',
	'semex-shortquery-title-from-ref' => 'Parameters:
* $1: semantic property name
* $2: another semantic property name of the same page',
	'semex-shortquery-error-missing-property' => '{{identical|Property}}',
	'semex-shortquery-error-byref-has-many-values' => '{{Identical|Property}}',
	'semex-shortquery-error-byref-has-wrong-type' => '{{Identical|Property}}
{{Identical|Type}}',
);

/** Breton (brezhoneg)
 * @author Fulup
 */
$messages['br'] = array(
	'semex-shortquery-hover-loading' => 'O kargañ...',
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

/** Spanish (español)
 * @author Armando-Martin
 * @author Mr.Ajedrez
 */
$messages['es'] = array(
	'semex-desc' => 'Agrega una sintaxis para consultas cortas más expresivas',
	'semex-shortquery-title' => '$1 de $2',
	'semex-shortquery-title-from-ref' => '$1 de la referencia $2',
	'semex-shortquery-hover-loading' => 'Cargando',
	'semex-shortquery-hover-loading-failed' => 'Error al cargar el contenido de las páginas encontradas mediante consultas cortas.',
	'semex-shortquery-error-missing-property' => 'No se ha definido un valor para la propiedad requerida.',
	'semex-shortquery-error-byref-has-many-values' => 'La propiedad de referencia dada tiene más de un valor, solo se tomó el primero como objetivo de las consultas.',
	'semex-shortquery-error-byref-has-wrong-type' => 'La propiedad de referencia dada debería ser una del tipo "Página".',
	'semex-shortquery-error-failed-nested-queries' => 'No se pueden ejecutar las consultas cortas porque hubo un error en la consulta corta anidada.',
	'semex-expressivestring-unresolvable' => 'Marcado irresoluble',
);

/** French (français)
 * @author DavidL
 */
$messages['fr'] = array(
	'semex-desc' => 'Ajoute une syntaxe pour des requêtes courtes plus expressives',
	'semex-shortquery-title' => '$1 de $2',
	'semex-shortquery-title-from-ref' => '$1 référencé par $2',
	'semex-shortquery-hover-loading' => 'Chargement',
	'semex-shortquery-hover-loading-failed' => 'Le chargement du contenu des pages trouvées par la requête courte a échoué.',
	'semex-shortquery-error-missing-property' => 'Aucune valeur définie pour la propriété interrogée.',
	'semex-shortquery-error-byref-has-many-values' => "La propriété de référence donnée a plus d'une valeur, seule la première a été prise comme cible des requêtes.",
	'semex-shortquery-error-byref-has-wrong-type' => 'La propriété de référence donnée doit être de type « Page ».',
	'semex-shortquery-error-failed-nested-queries' => 'La requête courte ne peut être exécutée parce que la requête courte imbriquée a échoué.',
	'semex-expressivestring-unresolvable' => 'Balisage insoluble',
);

/** Franco-Provençal (arpetan)
 * @author ChrisPtDe
 */
$messages['frp'] = array(
	'semex-shortquery-title' => '$1 de $2',
	'semex-shortquery-title-from-ref' => '$1 refèrenciê per $2',
	'semex-shortquery-hover-loading' => 'Chargement',
);

/** Galician (galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'semex-desc' => 'Engade unha sintaxe para posibilitar unhas pescudas breves máis expresivas',
	'semex-shortquery-title' => '$1 de "$2"',
	'semex-shortquery-title-from-ref' => '$1 da referencia $2',
	'semex-shortquery-hover-loading' => 'Cargando',
	'semex-shortquery-hover-loading-failed' => 'Erro ao cargar os contidos das páxinas atopadas polas pescudas breves.',
	'semex-shortquery-error-missing-property' => 'Non hai definido ningún valor para a propiedade pescudada.',
	'semex-shortquery-error-byref-has-many-values' => 'A propiedade de referencia achegada ten máis dun valor; cóllese unicamente o primeiro como obxectivo das pescudas.',
	'semex-shortquery-error-byref-has-wrong-type' => 'A propiedade de referencia achegada debe ser do tipo "Páxina".',
	'semex-shortquery-error-failed-nested-queries' => 'Non se pode executar a pescuda breve porque houbo un erro na pescuda breve aniñada.',
	'semex-expressivestring-unresolvable' => 'Formato irresoluble',
);

/** Upper Sorbian (hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'semex-desc' => 'Přidawa syntaksu za bóle wuprajiwe krótke naprašowanja',
	'semex-shortquery-title' => '$1 z $2',
	'semex-shortquery-title-from-ref' => '$1 z referency $2',
	'semex-shortquery-hover-loading' => 'Začituje so',
	'semex-shortquery-hover-loading-failed' => 'Začitowanje wobsaha cilowych stronow krótkich naprašowanjow je so njeporadźiło.',
	'semex-shortquery-error-missing-property' => 'Za naprašowanu kajkosć njeje so žana hódnota definowała.',
	'semex-shortquery-error-byref-has-many-values' => 'Podata referencna kajkosć ma wjace hač jednu hódnotu, jenož prěnja je so jako cil naprašowanja wužiła.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Data referencna kajkosć měła typ "strona" měć.',
	'semex-shortquery-error-failed-nested-queries' => 'Krótke naprašowanje njeda so wuwjesć, dokelž wobsahowane krótke naprašowanje je so njeporadźiło.',
	'semex-expressivestring-unresolvable' => 'Njerozwjazujomna syntaksa',
);

/** Hungarian (magyar)
 * @author TK-999
 */
$messages['hu'] = array(
	'semex-desc' => 'Szintaxist hoz létre a rövid lekérdezések kifejezőbbé tevéséhez',
	'semex-shortquery-title' => '$1 a(z) $2 lapról',
	'semex-shortquery-hover-loading' => 'Betöltés',
	'semex-shortquery-hover-loading-failed' => 'A rövid lekérdezés céloldalai tartalmának betöltése nem sikerült.',
	'semex-shortquery-error-missing-property' => 'A lekérdezett tulajdonsághoz nincs megadva érték.',
	'semex-shortquery-error-byref-has-many-values' => 'Az adott tulajdonságnak egynél több értéke van, csak az elsőt tekintem a lekérdezés céljának.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Az adott tulajdonságnak "Lap" typusúnak kell lennie.',
	'semex-shortquery-error-failed-nested-queries' => 'A rövid lekérdezés nem hajtható végre, mert az egymásba ágyazott rövid lekérdezéseket nem sikerült végrehajtani.',
	'semex-expressivestring-unresolvable' => 'Feloldhatatlan kód',
);

/** Interlingua (interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'semex-desc' => 'Adde un syntaxe pro curte consultas plus expressive',
	'semex-shortquery-title' => '$1 de $2',
	'semex-shortquery-title-from-ref' => '$1 ab ref $2',
	'semex-shortquery-hover-loading' => 'Cargamento',
	'semex-shortquery-hover-loading-failed' => 'Le cargamento del contento del paginas de destination de consultas curte ha fallite.',
	'semex-shortquery-error-missing-property' => 'Nulle valor definite pro le proprietate requirite.',
	'semex-shortquery-error-byref-has-many-values' => 'Le proprietate de referentia date ha plus de un valor. Solmente le prime esseva prendite como le objectivo de consultas.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Le proprietate de referentia date debe esser del typo "Page".',
	'semex-shortquery-error-failed-nested-queries' => 'Le consulta curte non pote esser executate perque le consulta curte annidate ha fallite.',
	'semex-expressivestring-unresolvable' => 'Marcation irresolubile',
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

/** Japanese (日本語)
 * @author Shirayuki
 */
$messages['ja'] = array(
	'semex-desc' => '表現力豊かな短いクエリーの構文を追加する',
	'semex-shortquery-title' => '$1 ($2 より)',
	'semex-shortquery-hover-loading' => '読み込み中',
	'semex-shortquery-error-missing-property' => '問い合わせされたプロパティの値は定義されていません。',
	'semex-shortquery-error-failed-nested-queries' => '入れ子の短いクエリーに失敗したため、短いクエリーを実行できません。',
	'semex-expressivestring-unresolvable' => '解決できないマークアップ',
);

/** Georgian (ქართული)
 * @author David1010
 */
$messages['ka'] = array(
	'semex-shortquery-title' => '$1 $2-დან',
	'semex-shortquery-hover-loading' => 'იტვირთება',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'semex-shortquery-title' => '$1 vun $2',
	'semex-shortquery-hover-loading' => 'Lueden',
);

/** Macedonian (македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'semex-desc' => 'Додава синтакса за поизразливи кратки барања (пребарувања)',
	'semex-shortquery-title' => '$1 од $2',
	'semex-shortquery-title-from-ref' => '$1 од наводот $2',
	'semex-shortquery-hover-loading' => 'Вчитувам',
	'semex-shortquery-hover-loading-failed' => 'Вчитувањето на содржината на целните страници од краткото пребарување не успеа.',
	'semex-shortquery-error-missing-property' => 'Нема зададено вредност на бараното својство.',
	'semex-shortquery-error-byref-has-many-values' => 'Даденото својство на наводот има повеќе од една вредност. Затоа, како цел е земена само првата.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Даденото својство на наводот треба да биде од типот „Страница“.',
	'semex-shortquery-error-failed-nested-queries' => 'Краткото барање не може да се изврши бидејќи не работи вметнатото кратко барање,',
	'semex-expressivestring-unresolvable' => 'Синтаксата на ознаките не може да се обработи.',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'semex-desc' => 'Voegt syntaxis toe voor meer expressieve korte zoekopdrachten',
	'semex-shortquery-title' => '$1 van $2',
	'semex-shortquery-title-from-ref' => '$1 van referentie $2',
	'semex-shortquery-hover-loading' => 'Bezig met laden...',
	'semex-shortquery-hover-loading-failed' => "Het laden van de inhoud van de doelpagina's voor de korte zoekopdrachten is mislukt.",
	'semex-shortquery-error-missing-property' => 'Er is geen waarde ingesteld voor de opgevraagde eigenschap.',
	'semex-shortquery-error-byref-has-many-values' => 'De opgegeven referentie-eigenschap heeft meer dan één waarde. Alleen de eerst is genomen als het doel voor de zoekopdracht.',
	'semex-shortquery-error-byref-has-wrong-type' => 'De opgegeven referentie-eigenschap hoort van het type "Page" te zijn.',
	'semex-shortquery-error-failed-nested-queries' => 'Korte zoekopdracht kan niet uitgevoerd worden omdat de geneste korte zoekopdracht is mislukt.',
	'semex-expressivestring-unresolvable' => 'De opmaak wordt niet begrepen',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'semex-shortquery-hover-loading' => 'برسېرېدنې کې دی',
);

/** Portuguese (português)
 * @author SandroHc
 */
$messages['pt'] = array(
	'semex-shortquery-hover-loading' => 'A carregar',
);

/** Romanian (română)
 * @author Stelistcristi
 */
$messages['ro'] = array(
	'semex-shortquery-hover-loading' => 'Se încarcă',
);

/** Swedish (svenska)
 * @author Martinwiss
 */
$messages['sv'] = array(
	'semex-desc' => 'Lägger till en syntax för mer uttrycksfylla korta frågor',
	'semex-shortquery-title' => '$1 från $2',
	'semex-shortquery-title-from-ref' => '$1 från ref $2',
	'semex-shortquery-hover-loading' => 'Läser in...',
	'semex-shortquery-hover-loading-failed' => 'Det gick inte att läsa in de korta frågornas målsidor.',
	'semex-shortquery-error-missing-property' => 'Inget värde har definierats för den efterfrågade egenskapen.',
	'semex-shortquery-error-byref-has-many-values' => 'Den givna referensegenskapen har mer en ett värde, endast den första valdes som mål för frågan.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Den givna referensegenskapen ska vara av typen "Sida".',
	'semex-shortquery-error-failed-nested-queries' => 'Den korta frågan kunde inte köras eftersom ihopkopplade korta frågor fungerade inte.',
	'semex-expressivestring-unresolvable' => 'Kodning som inte går att klara upp',
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

/** Urdu (اردو)
 * @author පසිඳු කාවින්ද
 */
$messages['ur'] = array(
	'semex-shortquery-hover-loading' => 'لوڈ ہو رہا ہے',
);

/** Vietnamese (Tiếng Việt)
 * @author පසිඳු කාවින්ද
 */
$messages['vi'] = array(
	'semex-shortquery-hover-loading' => 'Đang tải...',
);

