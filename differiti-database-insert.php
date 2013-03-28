<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// variabili ricevute dal form
$inconveniente = trim(strip_tags($_POST['differito']));
$tipologia = $_POST['tipo'];
$data = $_POST['data'];
$nominativo = $_POST['firma'];
$idElicottero = $_GET['ideli'];

// connessione al database
$cxn = connectToDatabase($host, $user, $password, $database);

// preparazione query SQL
$insertQuery = <<<INSERTQUERY
INSERT INTO `differiti`.`table_differitiaperture` (`id`,
`elicottero`,
`inconveniente`,
`tipologia`,
`dataInconveniente`,
`firmaApertura`)
VALUES (NULL, 
INSERTQUERY;
$insertQuery .= "'".mysqli_real_escape_string($cxn, $idElicottero)."', ";  // id elicottero
$insertQuery .= "'".mysqli_real_escape_string($cxn, $inconveniente)."', "; // descrizione differito
$insertQuery .= "'".mysqli_real_escape_string($cxn, $tipologia)."', ";     // tipologia
$insertQuery .= "'".mysqli_real_escape_string($cxn, $data)."', ";          // data
$insertQuery .= "'".mysqli_real_escape_string($cxn, $nominativo)."');";    // firma apertura

// esecuzione query
$result = sendQuery($cxn, $insertQuery);

// mostra pagina di riepilogo e differito inserito
echo "<p style=\"text-align: center\">Query inviata al database:</p>";
echo "<p style=\"text-align: center\"><i>$insertQuery</i></p>";
echo "<p style=\"text-align: center\">Controlla <a href=\"differiti-home.php#table\">qui</a> che il differito sia stato correttamente inserito.</p>";

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
