<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// Parametri in ingresso
$eli = $_GET['eli'];       // numero di fiancata dell'elicottero
$ideli = $_GET['ideli'];   // id univoco associato a ogni elicottero
$action = $_GET['action']; // azione che si intende eseguire (query di tipo INSERT, UPDATE o DELETE)
$table = $_GET['table'];   // tabella su cui si vuole eseguire l'inserimento o la modifica di un record
$AP = $_GET['AP'];         // id relativo all'apertura (AP) del differito
$PR = $_GET['PR'];         // id relativo alla prosecuzione (PR) del differito
$CH = $_GET['CH'];         // id relativo alla chiusura (CH) del differito

// Debug variabili
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
// Fine debug variabili

include("differiti-editor-query-factory.inc.php");
include("differiti-display-single.inc.php"); // funzioni di stampa della tabella per il singolo differito

# 1. - Titolo dell'azione che si sta eseguendo (utilizza i dati forniti da $_GET e $_POST)
echo "<p>1. - Titolo dell'azione che si sta compiendo: ...</p>\n";

# 2. - Connessione al database MySQL
$cxn = connectToDatabase($host, $user, $password, $database);
echo "<p>2. - Connessione al database MySQL eseguita.</p>\n";

# 3. - Visualizzazione record pre-modifica
if ( !($action == "INSERT" && $table == "AP") ) {
	$premodQuery = viewSingleRecordQueryFactory($AP);
	$caption = "Record prima della modifica:";
	$premodQueryResult = sendQuery($cxn, $premodQuery);
	singoloStampaTabella($premodQueryResult, $caption);
}
echo "<p>3. - Visualizzato record pre-modifica.</p>\n";

# 4. - Creazione della query utilizzando i dati forniti da $_GET e $_POST
if ($action == 'INSERT') {
	$query = insertQueryFactory($cxn, $eli, $ideli, $table, $AP, $PR, $CH, $_POST);
	$caption = "Record dopo l'inserimento:";
}
else if ($action == 'UPDATE') {
	$query = updateQueryFactory($cxn, $eli, $ideli, $table, $AP, $PR, $CH, $_POST);
	$caption = "Record dopo la modifica:";
}
else if ($action == 'DELETE') {
	$query = deleteQueryFactory($cxn, $eli, $ideli, $table, $AP, $PR, $CH, $_POST);
	$caption = "Record dopo l'eliminazione:";
}
else {
	$query = "";
	echo "<p>Errore durante la costruzione della query SQL.</p>\n";
}
echo "<p>4. - Query SQL per la modifica del database creata.</p>\n";

# 5. - Visualizzazione codice SQL della query che viene eseguita
echo "<p>5. - Visualizzazione query SQL:</p>\n";
echo "<p style=\"font-family: monospace; text-align: center\">$query;</p>";

# 6. - Esecuzione della query SQL
$executeQueryResult = sendQuery($cxn, $query);
$id = mysqli_insert_id($cxn);
echo "<p>6. - Query SQL eseguita. L'id auto-increment è: $id.</p>\n";

# 7. - Visualizzazione record post-modifica
if ($AP == "") // nel caso di nuova apertura, determina il numero AP con l'id auto-increment della query eseguita
	$AP = $id;
$postmodQuery = viewSingleRecordQueryFactory($AP);

$postmodQueryResult = sendQuery($cxn, $postmodQuery);
singoloStampaTabella($postmodQueryResult, $caption);
echo "<p>7. - Visualizzato record post-modifica.</p>\n";

# 8. - Chiusura della connessione al database MySQL
mysqli_close($cxn);
echo "<p>8. - Connessione al database MySQL chiusa.</p>\n";

# 9. - Visualizzazione link per controllo aggiornamento database
echo "<p>9. - <a href=\"differiti-display.php?ideli=&eli=&chiusi=1&tipo=\">Link</a> per il controllo della corretta esecuzione della query.</p>\n";

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
