<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

# riprende l'intestazione e la visualizzazione della pagina precedente: qui sotto

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

# riprende l'intestazione e la visualizzazione della pagina precedente: qui sopra

$AP = $_GET['AP'];
$PR = $_GET['PR'];
$CH = $_GET['CH'];
$action = $_POST['action'];

echo "<p>AP = $AP</p>\n";
echo "<p>PR = $PR</p>\n";
echo "<p>CH = $CH</p>\n";
echo "<p>Azione da eseguire: $action</p>\n";

// Suddivisione in 8 casi:

// 1. - UPDATE-AP (modifica AP)
// 2. - DELETE-AP (elimina AP ed eventuali PR e CH)

// 3. - INSERT-PR (aggiunge una nota / PR)
// 4. - UPDATE-PR (edita una nota / PR esistente)
// 5. - DELETE-PR (elimina una nota / PR esistente)

// 6. - INSERT-CH (aggiunge una CH)
// 7. - UPDATE-CH (edita una CH esistente)
// 8. - DELETE-CH (elimina una CH esistente)

include("differiti-modifica-deleteQueryBuilder.inc.php");

if ($action == "UPDATE-AP") { // 1. - UPDATE-AP (modifica AP)
	echo "<h3>Sto per modificare un'apertura.</h3>";
}

else if ($action == "DELETE-AP") { // 2. - DELETE-AP (elimina AP ed eventuali PR e CH)
	echo "<h3>Sto per eliminare un record AP ed eventuali PR e CH collegati.</h3>";
	$query = deleteQueryBuilder("AP", $AP, $PR, $CH);
	echo "<h4>Query di eliminazione:<h4>\n";
	echo "<p>$query</p>\n";
}

else if ($action == "INSERT-PR") { // 3. - INSERT-PR (aggiunge una nota / PR)
	echo "<h3>Sto per inserire una nota (PR).</h3>";
}

else if ($action == "UPDATE-PR") { // 4. - UPDATE-PR (edita una nota / PR esistente)
	echo "<h3>Sto per aggiornare una nota.</h3>";
}

else if ($action == "DELETE-PR") { // 5. - DELETE-PR (elimina una nota / PR esistente)
	echo "<h3>Sto per eliminare una nota.</h3>";
	$query = deleteQueryBuilder("PR", $AP, $PR, ""); // qui $CH non serve
	echo "<h4>Query di eliminazione:<h4>\n";
	echo "<p>$query</p>\n";
}

else if ($action == "INSERT-CH") { // 6. - INSERT-CH (aggiunge una CH)
	echo "<h3>Sto per chiudere un differito.</h3>";
}

else if ($action == "UPDATE-CH") { // 7. - UPDATE-CH (edita una CH esistente)
	echo "<h3>Sto per modificare la chiusura un differito.</h3>";
}

else if ($action == "DELETE-CH") { // 8. - DELETE-CH (elimina una CH esistente)
	echo "<h3>Sto per annullare la chiusura di un differito.</h3>";
	$query = deleteQueryBuilder("CH", $AP, "", $CH); // qui $PR non serve
	echo "<h4>Query di eliminazione:<h4>\n";
	echo "<p>$query</p>\n";
}

else
	echo "<h3>Errore! Non riconosco l'azione che vuoi eseguire! Controllare il codice!</h3>";

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
