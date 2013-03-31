<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

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

// sequenza programma principale
$cxn = connectToDatabase($host, $user, $password, $database);
$query = <<<SINGLEQUERY
SELECT
`numerofiancata` as Elicottero, `inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`, `provvedimentocorrettivoadottato` as provvedimento, `durataoreuomo` as `ore/uomo`, `datachiusura` as `data CH`, `firmach` as `firma CH`
FROM
`view_differiti`
WHERE
( (`idapertura` $AP) AND (`idnote` $PR) AND (`idchiusura` $CH) )
SINGLEQUERY;

$caption = "Record che sarà interessato dalla modifica: (idAP = \"{$_GET['AP']}\" / idPR = \"{$_GET['PR']}\" / idCH = \"{$_GET['CH']}\")";
$result = sendQuery($cxn, $query);

include("differiti-display-single.inc.php"); // funzioni dedicate alla stampa della tabella per il singolo differito
singoloStampaTabella($result, $caption);
include("differiti-modifica-formBuilder.inc.php");
buildForm(determineFormButtons($_GET['AP'], $_GET['PR'], $_GET['CH']), $_GET['AP'], $_GET['PR'], $_GET['CH']);

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
