<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// ottenimento del record da correggere dal database
$recordID = $_GET['record'];
$query = "SELECT `inconveniente` FROM table_differitiaperture WHERE ((id) = $recordID)";
$cxn = connectToDatabase($host, $user, $password, $database);
$originalRecord = mysqli_fetch_row(sendQuery($cxn, $query));
$fixedRecord = $originalRecord;

// elaborazione record
echo "\n  <p>Record originale (controlla se vi sono problemi di codifica, ad esempio se vedi caratteri strani):</p>\n";
echo "\n  <ul>\n";
foreach ($originalRecord as $originalValue) {
	echo "    <li style=\"color: red\">$originalValue</li>\n";
}
echo "  </ul>\n";
echo "\n  <p>Anteprima del record dopo la conversione da UTF-8 a ISO-8859-1:</p>\n";
echo "\n  <ul>\n";
foreach ($fixedRecord as $originalValue) {
	$fixedValue = reverseEncoding($originalValue);
	echo "    <li style=\"color: green\">$fixedValue</li>\n";
}
echo "  </ul>\n";

// generazione query di update
$updateQuery = "UPDATE `differiti`.`table_differitiaperture`";
$updateQuery .= " SET `inconveniente` = '";
$updateQuery .= mysqli_real_escape_string($cxn, $fixedValue);
$updateQuery .= "' WHERE `table_differitiaperture`.`id` = '$recordID'";
echo "\n  <p>Query SQL che verrà inviata al database:</p>\n";
echo "\n  <ul>\n";
echo "    <li><i>$updateQuery;</i></li>\n";
echo "  </ul>\n";
$htmlFriendlyUpdateQuery = htmlentities($updateQuery);

// form per conferma conversione codifica
$formCode = <<<FORMCODE

  <form action="differiti-database-fix-encoding.php" method="post">
    <p><input type="hidden" name="query" value="$htmlFriendlyUpdateQuery" /></p>
    <p><input type="submit" value="Conferma correzione codifica" /></p>
  </form>

FORMCODE;
echo $formCode;

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc");
?>
