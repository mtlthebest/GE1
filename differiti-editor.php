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
$cxn = connectToDatabase($host, $user, $password, $database);
echo "<p>Connessione al db effettuata.</p>";

# Step B. - PRESENTAZIONE DEL RECORD DA EDITARE (casi interessati solo UPDATE o DELETE: 4, 5, 6, 7, 8 e 9)
if ($_GET['action'] == "UPDATE" || $_GET['action'] == "DELETE") {
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
	$caption = "Record che sarà interessato dalla modifica: " .
		"(idAP = \"{$_GET['AP']}\" / idPR = \"{$_GET['PR']}\" / idCH = \"{$_GET['CH']}\")";
	$result = sendQuery($cxn, $singleQuery);
	include("differiti-display-single.inc.php"); // funzioni di stampa della tabella per il singolo differito
	singoloStampaTabella($result, $caption);
}

# Step C. - PRESENTAZIONE DEL FORM DI CARICAMENTO O MODIFICA
echo "<form class=\"modifyDifferiti\" action=\"differiti-esegui-query.php?" . // pagina .php richiamata dal form e CSS
	"eli={$_GET['eli']}&ideli={$_GET['ideli']}&table={$_GET['table']}&action={$_GET['action']}&" . // parametri
	"AP={$_GET['AP']}&PR={$_GET['PR']}&CH={$_GET['CH']}" . // parametri relativi agli ID dei record nelle tabelle
	"\" method=\"post\">\n"; // metodo utilizzato: POST

// impostazione fieldset e legenda
echo "<fieldset>\n";
$legend = legendFactory($_GET['action'], $_GET['eli'], $_GET['table']);
echo "<legend>$legend</legend>\n";

// campi del form per l'inserimento o la modifica dei dati
include("differiti-editor-form-factory.inc.php");
echo editorFormFactory($cxn, $_GET['eli'], $_GET['ideli'], $_GET['action'], $_GET['table'], $_GET['AP'], $_GET['PR'], $_GET['CH']); // la connessione $cxn è necessaria per la costruzione dei menu a tendina del form

# Step D. - PULSANTE DI CONFERMA (fa parte del form)
if ($_GET['action'] == "INSERT") $butt = "l'inserimento del nuovo record";
	else if ($_GET['action'] == "UPDATE") $butt = "l'aggiornamento del record";
	else if ($_GET['action'] == "DELETE") $butt = "l'eliminazione del record";
	else {
		echo "<p>Errore! Problema creazione pulsante di conferma per il form di inserimento/modifica dati.</p>\n";
		$butt = "???";
	}
echo "<p><img src=\"edit_icon_lefthanded.png\" alt=\"\" /><input type=\"submit\" value=\"Conferma $butt\" /></p>\n";

# Step E. - CHIUSURA DEL FORM
// chiusura fieldset
echo "</fieldset>\n";
echo "</form>\n";

# Step F. - CHIUSURA DELLA CONNESSIONE AL DATABASE MySQL
mysqli_close($cxn);
echo "<p>Connessione al db chiusa.</p>";

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
