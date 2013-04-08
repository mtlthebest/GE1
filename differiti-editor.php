<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

# PARAMETRI IN INGRESSO
// $_GET['eli']:    numero di fiancata dell'elicottero
// $_GET['ideli']:  id univoco associato a ogni elicottero
// $_GET['action']: azione che si intende eseguire (query di tipo INSERT, UPDATE o DELETE)
// $_GET['table']:  tabella su cui si vuole eseguire l'inserimento o la modifica di un record
// $_GET['AP']:     id relativo all'apertura (AP) del differito
// $_GET['PR']:     id relativo alla prosecuzione (PR) del differito
// $_GET['CH']:     id relativo alla chiusura (CH) del differito

if (!isset($_GET['table'])) // ottiene $table da GET o POST a seconda dell'URL di provenienza
	$table = substr($_POST['action'], -2);
else
	$table = $_GET['table'];

if (!isset($_GET['action'])) // ottiene $action da GET o POST a seconda dell'URL di provenienza
	$action = substr_replace($_POST['action'], "", -3);
else
	$action = $_GET['action'];

/* debug variabili
echo "<p>Debug variabili:</p>\n";
echo "<ul>";
echo "<li>eli: {$_GET['eli']}</li>\n";
echo "<li>ideli: {$_GET['ideli']}</li>\n";
echo "<li>action: $action</li>\n";
echo "<li>table: $table</li>\n";
echo "<li>AP: {$_GET['AP']}</li>\n";
echo "<li>PR: {$_GET['PR']}</li>\n";
echo "<li>CH: {$_GET['CH']}</li>\n";
echo "</ul>";
*/ // fine debug

# SUDDIVISIONE IN CASI (devono essere tutti gestiti dal programma .php):

// 1. - INSERT-AP
// 2. - INSERT-PR
// 3. - INSERT-CH

// 4. - UPDATE-AP
// 5. - UPDATE-PR
// 6. - UPDATE-CH

// 7. - DELETE-AP
// 8. - DELETE-PR
// 9. - DELETE-CH

# Step A. - CONNESSIONE AL DATABASE MySQL (comune a tutti i casi)
$cxn = connectToDifferitiDatabase();

# Step B. - PRESENTAZIONE DEL RECORD DA EDITARE (casi interessati: tutti tranne INSERT-AP)
if ( !($action == "INSERT" && $table == "AP") ) {
	// visualizzazione tabella
	// sono necessari: $caption, $query
	if ($_GET['AP'] == "")
		$AP = "is null";
	else
		$AP = "= '".$_GET['AP']."'";

	if ($_GET['PR'] == "")
		$PR = "is null";
	else
		$PR = "= '".$_GET['PR']."'";

	if ($_GET['CH'] == "")
		$CH = "is null";
	else
		$CH = "= '".$_GET['CH']."'";
	$singleQuery = <<<SINGLEQUERY
SELECT
`numerofiancata` as Elicottero, `inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`, `provvedimentocorrettivoadottato` as provvedimento, `durataoreuomo` as `ore/uomo`, `datachiusura` as `data CH`, `firmach` as `firma CH`
FROM
`view_differiti`
WHERE
( (`idapertura` $AP) AND (`idnote` $PR) AND (`idchiusura` $CH) )
SINGLEQUERY;
	$caption = "<a id=\"caption\"></a>Record che sarà interessato dalla modifica:";
	$result = sendQuery($cxn, $singleQuery);
	include("differiti-display-single.inc.php"); // funzioni di stampa della tabella per il singolo differito
	singoloStampaTabella($result, $caption);
}

# Step C. - PRESENTAZIONE DEL FORM DI CARICAMENTO O MODIFICA
echo "<form class=\"editDifferiti\" action=\"differiti-esegui-query.php?" . // pagina .php richiamata dal form e CSS
	"eli={$_GET['eli']}&ideli={$_GET['ideli']}&table=$table&action=$action&" . // parametri
	"AP={$_GET['AP']}&PR={$_GET['PR']}&CH={$_GET['CH']}#summary" . // parametri relativi agli ID dei record nelle tabelle
	"\" method=\"post\">\n"; // metodo utilizzato: POST

// impostazione fieldset e legenda
echo "<fieldset>\n";
$legend = legendFactory($action, $_GET['eli'], $table);
echo "<legend><a id=\"legend\">$legend</legend>\n";

// campi del form per l'inserimento o la modifica dei dati
include("differiti-editor-form-factory.inc.php");
// la connessione $cxn è necessaria per la costruzione dei menù a tendina del form
echo editorFormFactory($cxn, $_GET['eli'], $_GET['ideli'], $action, $table, $_GET['AP'], $_GET['PR'], $_GET['CH']);

# Step D. - PULSANTE DI CONFERMA (fa parte del form)
if ($action == "INSERT") $butt = "l'inserimento del nuovo record";
	else if ($action == "UPDATE") $butt = "l'aggiornamento del record";
	else if ($action == "DELETE") $butt = "l'eliminazione del record";
	else {
		echo "<p>Errore! Problema creazione pulsante di conferma per il form di inserimento/modifica dati.</p>\n";
		$butt = "???";
	}
echo "<p><img src=\"edit_icon_lefthanded.png\" alt=\"\" /><input type=\"submit\" value=\"Conferma $butt\" /></p>\n";

# Step E. - CHIUSURA DEL FORM
// chiusura fieldset
echo "</fieldset>\n";
echo "</form>\n";
$javascript = <<<JAVASCRIPT
<script type="text/javascript">
//<![CDATA[
    /*<[CDATA[*/
     var dpck   = new DatePicker({
      relative  : 'data',
      language  : 'it'
      });
    /*]]>*/
//]]>
</script>
JAVASCRIPT;
echo $javascript; // implementazione calendario

# Step F. - CHIUSURA DELLA CONNESSIONE AL DATABASE MySQL
mysqli_close($cxn);

function legendFactory($azione, $fiancata, $tabella) {
	if ($azione == "INSERT") $act = "Inserimento di un record nel";
	else if ($azione == "UPDATE") $act = "Aggiornamento di un record nel";
	else if ($azione == "DELETE") $act = "Eliminazione di un record dal";
	else {
		echo "<p>Errore! Problema nella creazione della legenda per il form di inserimento/modifica dati.</p>\n";
		return "???";
	}
	if ($tabella == "AP") $tab = "AP / apertura";
	else if ($tabella == "PR") $tab = "PR / note o prosecuzione";
	else if ($tabella == "CH") $tab = "CH / chiusura";
	else {
		echo "<p>Errore! Problema nella creazione della legenda per il form di inserimento/modifica dati.</p>\n";
		return "???";
	}
	return "$act database dei differiti (tipo: $tab) per la fiancata $fiancata";
}

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
