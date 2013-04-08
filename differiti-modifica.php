<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

/*
 * Questo programma riceve la variabile $_GET['AP'] da "differiti-display.php".
 * Anzitutto è necessario ottenere informazioni in merito alla situazione del differito
 * (fiancate coinvolte, eventuale esistenza di PR o CH, ecc...).
 * Si interroga il database (vista `view_differiti`) per ottenere tali informazioni;
 * saranno poi passate ai file .php che lo richiedono.
 */

// Connessione al database MySQL
$cxn = connectToDifferitiDatabase();

// Ottenimento di informazioni ulteriori in merito allo stato del differito (idAP = $_GET['AP'])
$obtainInfoQuery = "SELECT `idapertura`, `ideli`, `numerofiancata`, `idnote`, `idchiusura` " .
	"FROM `view_differiti` " .
	"WHERE (`idapertura` = '{$_GET['AP']}')";

// echo "<p>InfoQuery: $obtainInfoQuery</p>\n";

$resultAPStatus = sendQuery($cxn, $obtainInfoQuery); // invio della query al database per ottenere la riga specifica del differito da modificare

$AP = $_GET['AP'];
while($riga = mysqli_fetch_array($resultAPStatus)) { // ciclo per assegnare i valori alle variabili richieste, prendendoli dal database
	$eli = $riga['numerofiancata'];
	$ideli = $riga['ideli'];
	$PR = $riga['idnote'];
	$CH = $riga['idchiusura'];
}

/* Solo debug: controllo se le variabili necessarie sono state ottenute con la query precedente
echo "<p>Debug variabili:</p>\n";
echo "<ul>";
echo "<li>eli: $eli</li>\n";
echo "<li>ideli: $ideli</li>\n";
echo "<li>AP: $AP</li>\n";
echo "<li>PR: $PR</li>\n";
echo "<li>CH: $CH</li>\n";
echo "</ul>";
*/ // Fine debug

// Stampa della tabella riepilogativa per un singolo differito (quello interessato)
$sql_AP = "= '".$AP."'";
if ($PR == "")
	$sql_PR = "is null";
else
	$sql_PR = "= '".$PR."'";

if ($CH == "")
	$sql_CH = "is null";
else
	$sql_CH = "= '".$CH."'";
$singleQuery = <<<SINGLEQUERY
SELECT
`numerofiancata` as Elicottero, `inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`, `provvedimentocorrettivoadottato` as provvedimento, `durataoreuomo` as `ore/uomo`, `datachiusura` as `data CH`, `firmach` as `firma CH`
FROM
`view_differiti`
WHERE
( (`idapertura` $sql_AP) AND (`idnote` $sql_PR) AND (`idchiusura` $sql_CH) )
SINGLEQUERY;

$caption = "<a id=\"caption\">Record che sarà interessato dalla modifica:";
$result = sendQuery($cxn, $singleQuery);
include("differiti-display-single.inc.php");
singoloStampaTabella($result, $caption);

// A seconda della situazione del differito il form propone, attraverso "radio button", le operazioni che è possibile effettuare su di esso.
include("differiti-modifica-formBuilder.inc.php");
buildForm(determineFormButtons($AP, $PR, $CH), $eli, $ideli, $AP, $PR, $CH);

// Chiusura della connessione al database MySQL
mysqli_close($cxn);

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
