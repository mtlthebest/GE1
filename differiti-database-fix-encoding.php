<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// connessione al database
$cxn = connectToDatabase($host, $user, $password, $database);

// variabili ricevute dal form
$query = $_POST['query'];

// esecuzione query
$result = sendQuery($cxn, $query);

// mostra pagina di riepilogo e differito inserito
echo "\n  <p style=\"text-align: center\">Query inviata al database:</p>\n";
echo "\n  <p style=\"text-align: center\"><i>".$query.";</i></p>\n";
echo "\n  <p style=\"text-align: center\">Controlla <a href=\"differiti-display.php?eli=&chiusi=1&tipo=\">qui</a> che il record sia stato correttamente modificato.</p>\n";

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
