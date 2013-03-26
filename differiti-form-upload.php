<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// dichiarazione variabili
$queryTipologieDifferito = "SELECT * FROM table_differititipologie";
$queryPersonale = "SELECT * FROM view_personale";

// sequenza programma
$cxn = connectToDatabase($host, $user, $password, $database);
$resultTipologieDifferito = sendQuery($cxn, $queryTipologieDifferito);
$resultPersonale = sendQuery($cxn, $queryPersonale);
visualizzaForm($resultTipologieDifferito, $resultPersonale);

// funzioni utilizzate

function visualizzaForm($resultTipo, $resultPersonale) {
	
	echo "  <form accept-charset=\"UTF-8\" action=\"differiti-database-insert.php?ideli=$_GET[ideli]&eli=$_GET[eli]\" method=\"post\">\n";
	echo "    <fieldset>\n";
	echo "      <legend>Caricamento provvedimenti correttivi differiti (apertura differito fiancata ".$_GET['eli'].")</legend>\n";
	// box testo differito
	echo "\n    <p><input type='textarea' class='txtArea' value='inserire qui la descrizione del provvedimento correttivo differito' name='differito' /></p>";
	
	// selezione tipologia differito
	echo "    <p><select name=\"tipo\">";
	while($row = mysqli_fetch_assoc($resultTipo)) {
		$preselection = "";
		extract($row);
		if($id == '5')
			$preselection = ' selected="selected"';
		echo "\n      <option value='$id'".$preselection.">\n        $tipologia\n      </option>\n";
	}
	echo "</select></p>";
	
	// selezione data (utilizza JavaScript)
	echo "    <p><input type='text' value='yyyy-mm-dd' name='data' /></p>";
	
	// selezione firma apertura
	echo "    <p><select name=\"firma\">";
	while($row = mysqli_fetch_assoc($resultPersonale)) {
		$preselection = "";
		extract($row);
		if($gerarchia == '0')
			$preselection = ' selected="selected"';
		$grado = fixEncoding($grado);
		$cognome = fixEncoding($cognome);
		$nome = fixEncoding($nome);
		echo "\n      <option value='$id'".$preselection.">\n        $grado $cognome $nome\n      </option>\n";
	}
	echo "    </select></p>";

	// pulsante di caricamento
	echo "    <p><input type='submit' value='Apri differito (elicottero ".$_GET['eli'].")' /></p>";
	echo "</fieldset>";
	echo "</form>";
}

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
