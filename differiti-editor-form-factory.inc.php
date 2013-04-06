<?php

function editorFormFactory($conn, $eli, $ideli, $action, $table, $AP, $PR, $CH) {
	
	if ($action == "DELETE") { // caso DELETE: più semplice, non richiede campi di inserimento per il form
		$alsoPRandCH = "";
		if ($table == "AP")
			$alsoPRandCH = "Questo comporterà anche l'eliminazione di eventuali \"PR\" e \"CH\" correlati. ";
		return 	"<p>Stai per eliminare il record \"$table\" selezionato, relativo alla fiancata $eli. ".
			"$alsoPRandCH"."Confermi?</p>\n";

	// da inserire un campo hidden che tramite $_POST indica cosa eliminare alla pressione del tasto di conferma

	} // fine caso DELETE-AP, DELETE-PR, DELETE-CH
	
	else if ($action == "INSERT" && $table == "AP") { // caso INSERT-AP --- OK, funzionante

		// dichiarazione variabili per query SQL
		$queryTipologieDifferito = "SELECT * FROM table_differititipologie ORDER BY ordine ASC";
		$queryPersonale = "SELECT * FROM view_personale";

		// esecuzione query per menu a tendina
		$resultTipo = sendQuery($conn, $queryTipologieDifferito);
		$resultPersonale = sendQuery($conn, $queryPersonale);

		// box testo differito
		$insertForm = "";	
		$insertForm .= "\n    <p><textarea class='txtArea' name='inconveniente'>" .
			"Inserire qui la descrizione del provvedimento correttivo differito...</textarea></p>";
	
		// selezione tipologia differito
		$insertForm .= "    <p><select name=\"tipologia\">";
		while($row = mysqli_fetch_assoc($resultTipo)) {
			$preselection = "";
			extract($row);
			if($id == '5') // preseleziona un campo
				$preselection = ' selected="selected"';
			$insertForm .= "\n      <option value='$id'".$preselection.">\n        $tipologia\n      </option>\n";
		}
		$insertForm .= "</select></p>";
	
		// selezione data (utilizza JavaScript)
		$dataOdierna = oggi(); // di default fa trovare la data di oggi nel campo
		$insertForm .= "    <p><input type='text' value='$dataOdierna' id='data' name='dataInconveniente' /></p>";

		// selezione firma apertura
		$insertForm .= "    <p><select name=\"firmaApertura\">";
		while($row = mysqli_fetch_assoc($resultPersonale)) {
			$preselection = "";
			extract($row);
			if($gerarchia == '0')
				$preselection = ' selected="selected"';
			$insertForm .= "\n      <option value='$id'".$preselection.">\n        $grado $cognome $nome\n      </option>\n";
		}
		$insertForm .= "    </select></p>";

		return $insertForm;

	} // fine caso INSERT-AP

	else if ($action == "INSERT" && $table == "PR") { // caso INSERT-PR --- OK, funzionante

		$insertForm = "";	

		// box testo annotazioni differito
		$insertForm .= "\n    <p><textarea class='txtArea' name='note'>" .
			"Inserire qui una nota o una prosecuzione (PR) del provvedimento correttivo differito...</textarea></p>";

		return $insertForm;

	} // fine caso INSERT-PR

	else if ($action == "INSERT" && $table == "CH") { // caso INSERT-CH

		$insertForm = ""; // inizializzazione form

		// dichiarazione variabili per query SQL
		$queryPersonale = "SELECT * FROM view_personale";

		// esecuzione query per menu a tendina
		$resultPersonale = sendQuery($conn, $queryPersonale);

		// box testo chiusura differito
		$insertForm .= "\n    <p><textarea class='txtArea' name='provvedimentoCorrettivoAdottato'>" .
			"Inserire qui il provvedimento correttivo adottato per la chiusura (CH) del differito...</textarea></p>";
	
		// box testo durata in ore-uomo
		$insertForm .= "\n    <p><input type='text' value='ore-uomo (ad es.: 2,25)' name='durataOreUomo'>";

		// selezione data (utilizza JavaScript)
		$dataOdierna = oggi(); // di default fa trovare la data di oggi nel campo
		$insertForm .= "    <p><input type='text' value='$dataOdierna' id='data' name='dataChiusura' /></p>";

		// selezione firma chiusura
		$insertForm .= "    <p><select name=\"firmaChiusura\">";
		while($row = mysqli_fetch_assoc($resultPersonale)) {
			$preselection = "";
			extract($row);
			if($gerarchia == '0')
				$preselection = ' selected="selected"';
			$insertForm .= "\n      <option value='$id'".$preselection.">\n        $grado $cognome $nome\n      </option>\n";
		}
		$insertForm .= "    </select></p>";

		return $insertForm;

	} // fine caso INSERT-CH

	else if ($action == "UPDATE") { // caso UPDATE, più complesso
		return "<p>Codice per la creazione del form nel caso UPDATE...</p>\n";
	} // fine caso UPDATE

	else { // caso non previsto
		return "<p>Errore durante la creazione del form, caso non previsto.</p>\n";
	} // fine caso non previsto
}

?>
