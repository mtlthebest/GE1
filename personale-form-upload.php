<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Personale";
$h1Title = "Segreteria Comando";
$h1HyperLink = "";
$h2Title = "Gestione del personale militare in forza al Gruppo";
$h2HyperLink = "personale-home.php#table";

include("common-open-page.inc");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// dichiarazione variabili
$query = "SELECT * FROM table_gradi ORDER BY gerarchia DESC";

// sequenza programma principale
$cxn = connectToDatabase($host, $user, $password, $database);
$result = sendQuery($cxn, $query);
visualizzaForm($result);

// funzioni utilizzate
function visualizzaForm($result) {
	echo "  <form action=\"personale-database-insert.php\" accept-charset=\"UTF-8\" method=\"post\">\n";
	echo "    <fieldset>\n";
	echo "      <legend>Caricamento nominativi per il personale militare</legend>\n";
	echo "\n      <p><select name=\"gradeID\">";
	while($row = mysqli_fetch_assoc($result)) {
		$preselection = "";
		extract($row);
		if($gerarchia == '0') $preselection = ' selected="selected"';
			$grado = fixEncoding($grado);
		echo "\n        <option value='$id'".$preselection.">\n          $grado\n        </option>\n";
	}
	echo "      </select></p>\n";
	echo "\n      <p><input type='text' value='COGNOME' name='surname' /></p>\n";
	echo "\n      <p><input type='text' value='Nome' name='name' /></p>\n";
	echo "\n      <p><input type=\"submit\" value=\"Inserisci nominativo\" /></p>\n";
	echo "    </fieldset>\n";
	echo "  </form>\n";
}

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc");
?>
