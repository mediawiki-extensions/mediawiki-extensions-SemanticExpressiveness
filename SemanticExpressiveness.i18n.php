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
	'semex-shortquery-title' => '$1 of $2',
	'semex-shortquery-title-from-ref' => '$1 from ref $2',
	'semex-shortquery-hover-loading' => 'Loading',
	'semex-shortquery-hover-loading-failed' => 'Loading the content of the short query\'s target page has failed.',
	'semex-shortquery-error-missing-property' => 'No value defined for the queried property.',
	'semex-shortquery-error-byref-has-many-values' => 'The given reference property has more than one value, only the first one was taken as the query\'s target.',
	'semex-shortquery-error-byref-has-wrong-type' => 'The given reference property should be one of type "Page".',
	'semex-shortquery-error-failed-nested-queries' => 'Short query can not be executed because nested short query failed.',
	'semex-expressivestring-unresolvable' => 'Unresolvable markup',
);

/** Message documentation (Message documentation)
 * @author Daniel Werner
 * @author Kghbln
 * @author Shirayuki
 * @author Siebrand
 */
$messages['qqq'] = array(
	'semex-desc' => '{{desc|name=Semantic Expressiveness|url=http://www.mediawiki.org/wiki/Extension:Semantic_Expressiveness}}',
	'semex-shortquery-title' => 'Description of a simple query. Says which property got queried from which page.

Parameters:
* $1 - semantic property name
* $2 - page the query got the property value from',
	'semex-shortquery-title-from-ref' => 'Description of a query by a reference. Says which property got queried from which reference (a reference is a property of type "Page" defined on the same page).

Parameters:
* $1 - semantic property name
* $2 - another semantic property name of the same page',
	'semex-shortquery-hover-loading' => '{{Identical|Loading}}',
	'semex-shortquery-hover-loading-failed' => 'When hovering over a short query result, a tooltip will appear. This tooltip will try to load the content of the short queries target page. If this loading fails for some reason, this message will be displayed.',
	'semex-shortquery-error-missing-property' => "Message displayed in a tooltip when hovering over a failed short query where the reason for the failure is that the queried property has no value on the query's target page.",
	'semex-shortquery-error-byref-has-many-values' => '',
	'semex-shortquery-error-byref-has-wrong-type' => '{{Identical|Property}}
{{Identical|Type}}',
);

/** Asturian (asturianu)
 * @author Xuacu
 */
$messages['ast'] = array(
	'semex-desc' => 'Amiesta una sintaxis para consultes curties más espresives',
	'semex-shortquery-title' => '$1 de $2',
	'semex-shortquery-title-from-ref' => '$1 de la ref $2',
	'semex-shortquery-hover-loading' => 'Cargando',
	'semex-shortquery-hover-loading-failed' => 'Falló la carga del conteníu de la páxina de destín de la consulta curtia.',
	'semex-shortquery-error-missing-property' => 'Nun se definió nengún valor pa la propiedá consultada.',
	'semex-shortquery-error-byref-has-many-values' => "La propiedá de la referencia dada tien más d'un valor, tomóse namái el primeru como destín de la consulta.",
	'semex-shortquery-error-byref-has-wrong-type' => 'La propiedá de la referencia dada tien de ser de tipu "Páxina".',
	'semex-shortquery-error-failed-nested-queries' => 'No se pue executar la consulta curtia porque falló la consulta curtia añerada.',
	'semex-expressivestring-unresolvable' => 'Nun se pue resolver el llinguax de marques',
);

/** Breton (brezhoneg)
 * @author Fohanno
 * @author Fulup
 */
$messages['br'] = array(
	'semex-shortquery-title' => '$1 diwar $2',
	'semex-shortquery-hover-loading' => 'O kargañ...',
);

/** German (Deutsch)
 * @author Daniel Werner
 * @author Kghbln
 * @author Purodha
 */
$messages['de'] = array(
	'semex-desc' => 'Ermöglicht eine vereinfachte Syntax für eingebettete Abfragen',
	'semex-shortquery-title' => '$1 von $2',
	'semex-shortquery-title-from-ref' => '$1 von Referenz $2',
	'semex-shortquery-hover-loading' => 'Lädt …',
	'semex-shortquery-hover-loading-failed' => 'Das Laden des Inhaltes der mit der Kurzabfrage abgefragten Seite schlug fehl.',
	'semex-shortquery-error-missing-property' => 'Für das abgefragte Attribut wurde kein Wert angegeben.',
	'semex-shortquery-error-byref-has-many-values' => 'Das angegebene Referenzattribut verfügt über mehr als einen Wert. Lediglich der erste Wert wurde als Abfrageziel genutzt.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Das angegebene Referenzattribut sollte vom Datentyp „Seite“ sein.',
	'semex-shortquery-error-failed-nested-queries' => 'Die Kurzabfrage konnte nicht ausgeführt werden, da eine in ihr enthaltene Kurzabfrage fehlgeschlagen ist.',
	'semex-expressivestring-unresolvable' => 'Die Abfragesyntax konnte nicht verarbeitet werden.',
);

/** Zazaki (Zazaki)
 * @author Erdemaslancan
 * @author Mirzali
 */
$messages['diq'] = array(
	'semex-shortquery-title' => '$2 ra $1',
	'semex-shortquery-title-from-ref' => '$2ref ra $1',
	'semex-shortquery-hover-loading' => 'Bar beno...',
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
	'semex-shortquery-hover-loading-failed' => 'Error al cargar el contenido de las páginas de destino encontradas por las consultas breves.',
	'semex-shortquery-error-missing-property' => 'No se ha definido un valor para la propiedad requerida.',
	'semex-shortquery-error-byref-has-many-values' => 'La propiedad de referencia dada tiene más de un valor, solo se tomó el primero como objetivo de la consulta.',
	'semex-shortquery-error-byref-has-wrong-type' => 'La propiedad de referencia dada debería ser una del tipo "Página".',
	'semex-shortquery-error-failed-nested-queries' => 'No se pueden ejecutar las consultas cortas porque hubo un error en la consulta corta anidada.',
	'semex-expressivestring-unresolvable' => 'Marcado irresoluble',
);

/** Persian (فارسی)
 * @author Armin1392
 * @author Reza1615
 */
$messages['fa'] = array(
	'semex-desc' => 'افزودن یک نحو برای صف‌های کوتاه گویاتر',
	'semex-shortquery-title' => '$1 از $2',
	'semex-shortquery-title-from-ref' => '$1از منبع $2',
	'semex-shortquery-hover-loading' => 'بارگیری',
	'semex-shortquery-hover-loading-failed' => 'بارگذاری محتوای هدف صف کوتاه صفحه انجام نشد.',
	'semex-shortquery-error-missing-property' => 'هیچ مقداری برای خاصیت پرسیدن تعریف تعریف نشده‌است.',
	'semex-shortquery-error-byref-has-many-values' => 'خاصیت منبع داده شده بیش از یک مقدار دارد، فقط اولی به عنوان هدف سوال قرار گرفته شده‌بود.',
	'semex-shortquery-error-byref-has-wrong-type' => 'خاصیت منبع داد شده باید یک نوع "صفحه" باشد.',
	'semex-shortquery-error-failed-nested-queries' => 'سوال کوتاه نمی‌تواند اجرا شود زیرا ساختن سوال کوتاه انجام نشد.',
	'semex-expressivestring-unresolvable' => 'نشانه‌گذاری نشدنى',
);

/** Finnish (suomi)
 * @author Nedergard
 */
$messages['fi'] = array(
	'semex-desc' => 'Lisää yksinkertaistetun syntaksin lyhyille kyselyille',
	'semex-shortquery-title' => '$1 sivulta $2',
	'semex-shortquery-title-from-ref' => '$1 käyttää viitettä $2',
	'semex-shortquery-hover-loading' => 'Ladataan',
	'semex-shortquery-hover-loading-failed' => 'Lyhyen kyselyn kohdesivun sisällön lataus epäonnistui.',
	'semex-shortquery-error-missing-property' => 'Kyselyn ominaisuudella ei ole arvoja.',
	'semex-shortquery-error-byref-has-many-values' => 'Valitulla viiteominaisuudella on monta arvoa, kysely otti huomioon vain ensimmäisen.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Valitun viiteominaisuuden tyypin pitäisi olla "Sivu".',
	'semex-shortquery-error-failed-nested-queries' => 'Lyhyttä kyselyä ei voida suorittaa, koska sisäkkäinen lyhyt kysely epäonnistui.',
	'semex-expressivestring-unresolvable' => 'Kooditusta oli mahdoton tulkita',
);

/** French (français)
 * @author DavidL
 * @author Gomoko
 * @author Nicolas NALLET
 */
$messages['fr'] = array(
	'semex-desc' => 'Ajoute une syntaxe pour des requêtes courtes plus expressives',
	'semex-shortquery-title' => '$1 sur $2',
	'semex-shortquery-title-from-ref' => '$1 référencé par $2',
	'semex-shortquery-hover-loading' => 'Chargement',
	'semex-shortquery-hover-loading-failed' => 'Le chargement du contenu de la page des résultats de la requête courte a échoué.',
	'semex-shortquery-error-missing-property' => 'Aucune valeur définie pour la propriété interrogée.',
	'semex-shortquery-error-byref-has-many-values' => "La propriété de référence fournie a plus d'une valeur, seule la première a été prise comme cible de la requête.",
	'semex-shortquery-error-byref-has-wrong-type' => 'La propriété de référence donnée doit être de type « Page ».',
	'semex-shortquery-error-failed-nested-queries' => 'La requête courte ne peut être exécutée parce que la requête courte imbriquée a échoué.',
	'semex-expressivestring-unresolvable' => 'Balisage insoluble',
);

/** Franco-Provençal (arpetan)
 * @author ChrisPtDe
 */
$messages['frp'] = array(
	'semex-shortquery-title' => '$1 de $2', # Fuzzy
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
	'semex-shortquery-hover-loading-failed' => 'Erro ao cargar os contidos das páxinas de destino atopadas polas pescudas breves.',
	'semex-shortquery-error-missing-property' => 'Non hai definido ningún valor para a propiedade pescudada.',
	'semex-shortquery-error-byref-has-many-values' => 'A propiedade de referencia achegada ten máis dun valor; cóllese unicamente o primeiro como obxectivo da pescuda.',
	'semex-shortquery-error-byref-has-wrong-type' => 'A propiedade de referencia achegada debe ser do tipo "Páxina".',
	'semex-shortquery-error-failed-nested-queries' => 'Non se pode executar a pescuda breve porque houbo un erro na pescuda breve aniñada.',
	'semex-expressivestring-unresolvable' => 'Formato irresoluble',
);

/** Upper Sorbian (hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'semex-desc' => 'Přidawa syntaksu za bóle wuprajiwe krótke naprašowanja',
	'semex-shortquery-title' => '$1 wot $2',
	'semex-shortquery-title-from-ref' => '$1 z referency $2',
	'semex-shortquery-hover-loading' => 'Začituje so',
	'semex-shortquery-hover-loading-failed' => 'Začitowanje wobsaha ciloweje strony krótkeho naprašowanja je so njeporadźiło.',
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
	'semex-shortquery-title' => '$1 a(z) $2 lapról', # Fuzzy
	'semex-shortquery-hover-loading' => 'Betöltés',
	'semex-shortquery-hover-loading-failed' => 'A rövid lekérdezés céloldalai tartalmának betöltése nem sikerült.', # Fuzzy
	'semex-shortquery-error-missing-property' => 'A lekérdezett tulajdonsághoz nincs megadva érték.',
	'semex-shortquery-error-byref-has-many-values' => 'Az adott tulajdonságnak egynél több értéke van, csak az elsőt tekintem a lekérdezés céljának.', # Fuzzy
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
	'semex-shortquery-hover-loading-failed' => 'Le cargamento del contento del pagina de destination del consulta curte ha fallite.',
	'semex-shortquery-error-missing-property' => 'Nulle valor definite pro le proprietate requirite.',
	'semex-shortquery-error-byref-has-many-values' => 'Le proprietate de referentia date ha plus de un valor. Solmente le prime ha essite prendite como le objectivo del consulta.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Le proprietate de referentia date debe esser del typo "Page".',
	'semex-shortquery-error-failed-nested-queries' => 'Le consulta curte non pote esser executate perque le consulta curte annidate ha fallite.',
	'semex-expressivestring-unresolvable' => 'Marcation irresolubile',
);

/** Italian (italiano)
 * @author Beta16
 */
$messages['it'] = array(
	'semex-desc' => 'Aggiunge una sintassi per interrogazioni brevi più espressive.',
	'semex-shortquery-title' => '$1 di $2',
	'semex-shortquery-title-from-ref' => '$1 da riferimento $2',
	'semex-shortquery-hover-loading' => 'Caricamento',
	'semex-shortquery-hover-loading-failed' => "Il caricamento del contenuto della pagina di destinazione dell'interrogazione breve non è riuscito.",
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
	'semex-desc' => 'より表現力豊かな短いクエリのための構文を追加する',
	'semex-shortquery-title' => '$2 の $1',
	'semex-shortquery-hover-loading' => '読み込み中',
	'semex-shortquery-hover-loading-failed' => '短いクエリの対象ページの内容の読み込みに失敗しました。',
	'semex-shortquery-error-missing-property' => '問い合わせされたプロパティの値は定義されていません。',
	'semex-shortquery-error-byref-has-many-values' => '指定した参照プロパティには複数の値があります。最初の値のみをクエリの対象として使用します。',
	'semex-shortquery-error-byref-has-wrong-type' => '指定した参照プロパティは「ページ」型のいずれかである必要があります。',
	'semex-shortquery-error-failed-nested-queries' => '入れ子の短いクエリに失敗したため、短いクエリを実行できません。',
	'semex-expressivestring-unresolvable' => '解決できないマークアップ',
);

/** Georgian (ქართული)
 * @author David1010
 */
$messages['ka'] = array(
	'semex-shortquery-title' => '$1 $2-თვის',
	'semex-shortquery-hover-loading' => 'იტვირთება',
);

/** Korean (한국어)
 * @author Priviet
 */
$messages['ko'] = array(
	'semex-desc' => '구체적이고 짧은 쿼리 구문을 추가',
	'semex-shortquery-title' => '$2의 $1',
	'semex-shortquery-title-from-ref' => '$2 참조에서 $1',
	'semex-shortquery-hover-loading' => '불러오는 중',
	'semex-shortquery-hover-loading-failed' => '쿼리의 대상 문서의 내용을 불러오는 데 실패했습니다.',
	'semex-shortquery-error-missing-property' => '쿼리된 속성에 정의된 값이 없습니다.',
	'semex-shortquery-error-byref-has-many-values' => '주어진 참조 속성이 하나 이상의 값을 가지고 첫 번째 속성을 쿼리의 대상으로 가져야 합니다.',
	'semex-shortquery-error-byref-has-wrong-type' => '주어진 참조 속성은 "문서"의 형태 중 하나여야 합니다.',
	'semex-shortquery-error-failed-nested-queries' => '중첩된 단순 쿼리가 실패하여 단순 쿼리를 실행할 수 없습니다.',
	'semex-expressivestring-unresolvable' => '해결할 수 없는 마크업',
);

/** Colognian (Ripoarisch)
 * @author Purodha
 */
$messages['ksh'] = array(
	'semex-desc' => 'Brängk en eijfache Schprooch för enjeboute koote Affroore för et Söhke.',
	'semex-shortquery-title' => '$1 vun $2',
	'semex-shortquery-title-from-ref' => '„$1“ mem Bezoch op „$2“',
	'semex-shortquery-hover-loading' => 'Ben am Lade&nbsp;…',
	'semex-shortquery-hover-loading-failed' => 'Wat bei dä koote Aanfroor_erus kohm kunnte mer nit afroofe.',
	'semex-shortquery-error-missing-property' => 'För di Eijeschaff es op dä Sigg keine Wäät aanjejovve.',
	'semex-shortquery-error-byref-has-many-values' => 'Di aanjejovve Bezochs_Eijeschaff hät mieh wi eine Wäät. Mer han bloß der eezde dervun als e Ziel för di Frooch jenumme.',
	'semex-shortquery-error-byref-has-wrong-type' => 'De Eijeschaff för dä Bezoch sullt vun dä Zoot „Sigg“ sin.',
	'semex-shortquery-error-failed-nested-queries' => 'Di koote Frooch kunnt nit beärbeit wääde, weil dren änthallde koote Froore dernävve jejange sin.',
	'semex-expressivestring-unresolvable' => 'Di Keu_Befähle kam_mer nit verschtonn.',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'semex-shortquery-title' => '$1 vu(n) $2',
	'semex-shortquery-title-from-ref' => '$1 referenzéiert vu(n) $2',
	'semex-shortquery-hover-loading' => 'Lueden',
);

/** Mizo (Mizo ţawng)
 * @author RMizo
 */
$messages['lus'] = array(
	'semex-shortquery-title' => '$2-a $1',
	'semex-shortquery-title-from-ref' => 'Kawhna $2 aṭanga $1',
	'semex-shortquery-hover-loading' => 'Nghak lawks...',
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
	'semex-shortquery-error-byref-has-many-values' => 'Даденото својство на наводот има повеќе од една вредност. Затоа, како цел на барањето е земена само првата.',
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
	'semex-shortquery-error-missing-property' => 'Er is geen waarde ingesteld voor de opgegeven eigenschap.',
	'semex-shortquery-error-byref-has-many-values' => 'De opgegeven referentie-eigenschap heeft meer dan één waarde. Alleen de eerst is genomen als het doel voor de zoekopdracht.',
	'semex-shortquery-error-byref-has-wrong-type' => 'De opgegeven referentie-eigenschap hoort van het type "Page" te zijn.',
	'semex-shortquery-error-failed-nested-queries' => 'Korte zoekopdracht kan niet uitgevoerd worden omdat de geneste korte zoekopdracht is mislukt.',
	'semex-expressivestring-unresolvable' => 'De opmaak wordt niet begrepen',
);

/** Polish (polski)
 * @author Chrumps
 */
$messages['pl'] = array(
	'semex-shortquery-title' => '$1 z $2',
);

/** Piedmontese (Piemontèis)
 * @author Borichèt
 * @author Dragonòt
 */
$messages['pms'] = array(
	'semex-desc' => "A gionta na sintassi për dj'anterogassion curte pi espressive",
	'semex-shortquery-title' => '$1 ëd $2',
	'semex-shortquery-title-from-ref' => '$1 da la corëspondensa $2',
	'semex-shortquery-hover-loading' => 'A caria',
	'semex-shortquery-hover-loading-failed' => "La caria dël contnù dla pàgina dj'arzultà dl'anterogassion curta a l'ha falì.",
	'semex-shortquery-error-missing-property' => 'Gnun valor definì për la propietà ciamà.',
	'semex-shortquery-error-byref-has-many-values' => "La propietà d'arferiment ciamà a l'ha pi d'un valor, mach ël prim a l'é stàit pijà com destinassion dl'anterogassion.",
	'semex-shortquery-error-byref-has-wrong-type' => "La propietà d'arferiment dàita a dovrìa esse ëd sòrt «Pàgina».",
	'semex-shortquery-error-failed-nested-queries' => "J'anterogassion curte a peulo pa esse fàite përchè l'anterogassion curta anidà a l'ha falì.",
	'semex-expressivestring-unresolvable' => 'Marcadura pa arzolvìbil.',
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

/** tarandíne (tarandíne)
 * @author Joetaras
 */
$messages['roa-tara'] = array(
	'semex-desc' => "Aggiunge 'na sindasse pe 'nderrogaziune cchiù corte e espressive",
	'semex-shortquery-title' => '$1 de $2',
	'semex-shortquery-title-from-ref' => '$1 da rif $2',
	'semex-shortquery-hover-loading' => 'Stoche a careche',
	'semex-shortquery-hover-loading-failed' => "'U carecamende d'u condenute d'a pàgene de destinazione de l'inderrogazione corte ave fallite.",
	'semex-shortquery-error-missing-property' => "Nisciune valore definite pa probbietà 'nderrogate.",
	'semex-shortquery-error-byref-has-many-values' => "'A probbietà de refèrimende date ave cchiù de 'nu valore, sulamende 'u prime avène pigghiate cumme destinazione de l'inderrogazione.",
	'semex-shortquery-error-byref-has-wrong-type' => '\'A probbietà de refèrimende date avessa essere de tipe "Pàgene".',
	'semex-shortquery-error-failed-nested-queries' => "L'inderrogazione corte non ge pò essere eseguite purcé l'inderrogazione nidificate ave fallite.",
	'semex-expressivestring-unresolvable' => 'Marcatore irrisolvibbile',
);

/** Russian (русский)
 * @author Okras
 */
$messages['ru'] = array(
	'semex-desc' => 'Добавляет синтаксис для более выразительных коротких запросов',
	'semex-shortquery-title' => '$1 из $2',
	'semex-shortquery-title-from-ref' => '$1 из ссылки $2',
	'semex-shortquery-hover-loading' => 'Загрузка',
	'semex-shortquery-hover-loading-failed' => 'Загрузка содержимого целевой страницы короткого запроса не удалась.',
	'semex-shortquery-error-missing-property' => 'Значение для запрашиваемого свойства не определено.',
	'semex-shortquery-error-byref-has-many-values' => 'Данное ссылочное свойство имеется более одного значения, только первое из них было взято в качестве цели запроса.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Заданное ссылочное свойство должно быть одним из имеющих тип «Страница».',
	'semex-shortquery-error-failed-nested-queries' => 'Короткий запрос не может быть выполнен, поскольку вложенный короткий запрос завершился неудачно.',
	'semex-expressivestring-unresolvable' => 'Неразрешимая разметка',
);

/** Sinhala (සිංහල)
 * @author පසිඳු කාවින්ද
 */
$messages['si'] = array(
	'semex-shortquery-title' => '$1 න් $2',
	'semex-shortquery-title-from-ref' => '$1 ගෙන් ref $2',
	'semex-shortquery-hover-loading' => 'ප්‍රවේශනය වෙමින්',
	'semex-expressivestring-unresolvable' => 'නොවිසඳිය හැකි අධිකය',
);

/** Swedish (svenska)
 * @author Martinwiss
 * @author WikiPhoenix
 */
$messages['sv'] = array(
	'semex-desc' => 'Lägger till en syntax för mer uttrycksfylla korta frågor',
	'semex-shortquery-title' => '$1 från $2',
	'semex-shortquery-title-from-ref' => '$1 från ref $2',
	'semex-shortquery-hover-loading' => 'Läser in...',
	'semex-shortquery-hover-loading-failed' => 'Det gick inte att läsa in de korta frågornas målsida.',
	'semex-shortquery-error-missing-property' => 'Inget värde har definierats för den efterfrågade egenskapen.',
	'semex-shortquery-error-byref-has-many-values' => 'Den givna referensegenskapen har mer en ett värde, endast den första valdes som frågans mål.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Den givna referensegenskapen ska vara av typen "Sida".',
	'semex-shortquery-error-failed-nested-queries' => 'Den korta frågan kunde inte köras eftersom ihopkopplade korta frågor fungerade inte.',
	'semex-expressivestring-unresolvable' => 'Kodning som inte går att klara upp',
);

/** Tamil (தமிழ்)
 * @author Shanmugamp7
 */
$messages['ta'] = array(
	'semex-shortquery-hover-loading' => 'ஏற்றப்படுகிறது',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'semex-desc' => 'Nagdaragdag ng isang palaugnayan para sa mas mapagpahayag na maiikling mga pagtatanong',
	'semex-shortquery-title' => '$1 mula sa $2', # Fuzzy
	'semex-shortquery-title-from-ref' => '$1 mula sa sangguniang $2',
	'semex-shortquery-hover-loading' => 'Ikinakarga',
	'semex-shortquery-hover-loading-failed' => 'Nabigo ang pagkakarga ng nilalaman ng mga pahinang puntirya ng maiiksing mga pagtatanong.', # Fuzzy
	'semex-shortquery-error-missing-property' => 'Walang inilarawang halaga para sa itinatanong na katangiang-ari.',
	'semex-shortquery-error-byref-has-many-values' => 'Ang ibinigay na katangiang-ari ng sanggunian ay mas mahigit kaysa sa isang halaga, tanging ang nauna lamang ang kinuha bilang pinupukol ng mga pagtatanong.', # Fuzzy
	'semex-shortquery-error-byref-has-wrong-type' => 'Ang ibinigay na katangiang-ari ng sanggunian ay dapat na isang may uring "Pahina".',
	'semex-shortquery-error-failed-nested-queries' => 'Hindi maisasakatuparan ang maiksing pagtatanong dahil nabigo ang nakapugad na maiksing pagtatanong.',
	'semex-expressivestring-unresolvable' => 'Hindi malulutasang pagmamarka',
);

/** Ukrainian (українська)
 * @author Andriykopanytsia
 * @author Steve.rusyn
 * @author SteveR
 */
$messages['uk'] = array(
	'semex-desc' => 'Додає синтаксис для виразніших коротких запитів',
	'semex-shortquery-title' => '$1 з $2',
	'semex-shortquery-title-from-ref' => '$1 від посилання $2',
	'semex-shortquery-hover-loading' => 'Завантаження',
	'semex-shortquery-hover-loading-failed' => 'Не вдалося завантажити вміст цільової сторінки короткого запиту.',
	'semex-shortquery-error-missing-property' => 'Значення не визначено для запитаних властивості.',
	'semex-shortquery-error-byref-has-many-values' => 'Властивість поданого посилання має більше одного значення, тому тільки перше значення взяте як ціль запиту.',
	'semex-shortquery-error-byref-has-wrong-type' => 'Властивість даного посилання має бути типу „Сторінка“.',
	'semex-shortquery-error-failed-nested-queries' => 'Короткий запит не може бути виконаний, бо короткий вкладений запит провалився.',
	'semex-expressivestring-unresolvable' => 'Невирішена розмітка',
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

/** Simplified Chinese (中文（简体）‎)
 * @author Liuxinyu970226
 * @author Yfdyh000
 */
$messages['zh-hans'] = array(
	'semex-desc' => '添加一个更富有表现力的短查询语法',
	'semex-shortquery-title' => '$2的$1',
	'semex-shortquery-title-from-ref' => '从ref$2的$1',
	'semex-shortquery-hover-loading' => '正在载入',
	'semex-shortquery-hover-loading-failed' => '加载短查询的目标页面内容已失败。',
	'semex-shortquery-error-missing-property' => '查询的属性没有定义的值。',
	'semex-shortquery-error-byref-has-many-values' => '提供的参考属性有多个值，只采用了第一个作为查询目标。',
	'semex-shortquery-error-byref-has-wrong-type' => '提供的参考属性的类型应该为“页面”。',
	'semex-shortquery-error-failed-nested-queries' => '由于嵌套短查询失败，不能进行短查询。',
	'semex-expressivestring-unresolvable' => '无法解析的标记',
);
