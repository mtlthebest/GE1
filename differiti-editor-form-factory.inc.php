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
	
	else if (($action == "INSERT" || $action == "UPDATE") && $table == "AP") { // caso INSERT-AP e UPDATE-AP
		
		// inizializzazione form di inserimento/aggiornamento
		$insertForm = "";

		// caricamento dati precedenti nel caso ($action == "UPDATE")
		if($action == "UPDATE") {
			// query per selezionare il differito specifico
			$loadPreviousQuery = "SELECT * FROM `view_differiti` WHERE (`idapertura` = '$AP')";
			$previousResult = sendQuery($conn, $loadPreviousQuery);
			// con questo ciclo si creano variabili che contengono i valori precedenti
			while($previousRow = mysqli_fetch_array($previousResult)) {
				$boxTestoDifferito = $previousRow['inconveniente'];
				$idTipologiaDifferito = $previousRow['idtipo'];
				$data = date("d/m/Y", strtotime($previousRow['datainconveniente']));
				$idPersonale = $previousRow['idfirmaap'];
			}
		}

		// dichiarazione variabili per query SQL
		$queryTipologieDifferito = "SELECT * FROM table_differititipologie ORDER BY ordine ASC";
		$queryPersonale = "SELECT * FROM view_personale";

		// esecuzione query per menu a tendina
		$resultTipo = sendQuery($conn, $queryTipologieDifferito);
		$resultPersonale = sendQuery($conn, $queryPersonale);

		// box testo differito
		if($action != "UPDATE")
			$boxTestoDifferito = "Inserire qui la descrizione del provvedimento correttivo differito...";
		$insertForm .= "\n    <p><textarea class='txtArea' name='inconveniente'>" .
			"$boxTestoDifferito</textarea></p>";
	
		// selezione tipologia differito
		if($action != "UPDATE")
			$idTipologiaDifferito = '5';
		$insertForm .= "    <p><select name=\"tipologia\">";
		while($row = mysqli_fetch_assoc($resultTipo)) {
			$preselection = "";
			extract($row);
			if($id == $idTipologiaDifferito) // preseleziona un campo
				$preselection = ' selected="selected"';
			$insertForm .= "\n      <option value='$id'".$preselection . 
				">\n        $tipologia\n      </option>\n";
		}
		$insertForm .= "</select></p>";
	
		// selezione data (utilizza JavaScript)
		if($action != "UPDATE")
			$data = oggi(); // di default fa trovare la data di oggi nel campo, salvo UPDATE
		$insertForm .= "    <p><input type='text' value='$data' id='data' name='dataInconveniente' /></p>";

		// selezione firma apertura
		if($action != "UPDATE")
			$idPersonale = '7';
		$insertForm .= "    <p><select name=\"firmaApertura\">";
		while($row = mysqli_fetch_assoc($resultPersonale)) {
			$preselection = "";
			extract($row);
			if($id == $idPersonale)
				$preselection = ' selected="selected"';
			$insertForm .= "\n      <option value='$id'" . 
				$preselection.">\n        $grado $cognome $nome\n      </option>\n";
		}
		$insertForm .= "    </select></p>";

		return $insertForm;

	} // fine caso INSERT-AP e UPDATE-AP

	else if (($action == "INSERT" || $action == "UPDATE") && $table == "PR") { // caso INSERT-PR e UPDATE-PR

		// inizializzazione form di inserimento/aggiornamento
		$insertForm = "";

		// caricamento dati precedenti nel caso ($action == "UPDATE")
		if($action == "UPDATE") {
			// query per selezionare il differito specifico
			$loadPreviousQuery = "SELECT * FROM `view_differiti` WHERE (`idapertura` = '$AP')";
			$previousResult = sendQuery($conn, $loadPreviousQuery);
			// con questo ciclo si creano variabili che contengono i valori precedenti
			while($previousRow = mysqli_fetch_array($previousResult)) {
				$boxTestoNote = $previousRow['note'];
			}
		}

		// box testo annotazioni differito
		if($action != "UPDATE")
			$boxTestoNote = "Inserire nota o prosecuzione (PR) per il provvedimento correttivo differito...";

		$insertForm .= "\n    <p><textarea class='txtArea' name='note'>" .
			"$boxTestoNote</textarea></p>";

		return $insertForm;

	} // fine caso INSERT-PR e UPDATE-PR

	else if (($action == "INSERT" || $action == "UPDATE") && $table == "CH") { // caso INSERT-CH e UPDATE-CH

		// inizializzazione form di inserimento/aggiornamento
		$insertForm = "";

		// caricamento dati precedenti nel caso ($action == "UPDATE")
		if($action == "UPDATE") {
			// query per selezionare il differito specifico
			$loadPreviousQuery = "SELECT * FROM `view_differiti` WHERE (`idapertura` = '$AP')";
			$previousResult = sendQuery($conn, $loadPreviousQuery);
			// con questo ciclo si creano variabili che contengono i valori precedenti
			while($previousRow = mysqli_fetch_array($previousResult)) {
				$boxProvvedimento = $previousRow['provvedimentocorrettivoadottato'];
				$oreUomo = $previousRow['durataoreuomo'];
				$data = date("d/m/Y", strtotime($previousRow['datachiusura']));
				$idPersonale = $previousRow['idfirmach'];
			}
		}

		// dichiarazione variabili per query SQL
		$queryPersonale = "SELECT * FROM view_personale";

		// esecuzione query per menu a tendina
		$resultPersonale = sendQuery($conn, $queryPersonale);

		// box testo chiusura differito
		if($action != "UPDATE")
			$boxProvvedimento = "Inserire provvedimento correttivo adottato per chiusura differito...";
		$insertForm .= "\n    <p><textarea class='txtArea' name='provvedimentoCorrettivoAdottato'>" .
			"$boxProvvedimento</textarea></p>";
	
		// box testo durata in ore-uomo
		if($action != "UPDATE")
			$oreUomo = "ore-uomo (es.: 2,25)";
		$insertForm .= "\n    <p><input type='text' value='$oreUomo' name='durataOreUomo'>";

		if($action != "UPDATE")
			$data = oggi(); // di default fa trovare la data di oggi nel campo, salvo UPDATE
		$insertForm .= "    <p><input type='text' value='$data' id='data' name='dataChiusura' /></p>";

		// selezione firma chiusura
		if($action != "UPDATE")
			$idPersonale = '7';
		$insertForm .= "    <p><select name=\"firmaChiusura\">";
		while($row = mysqli_fetch_assoc($resultPersonale)) {
			$preselection = "";
			extract($row);
			if($id == $idPersonale)
				$preselection = ' selected="selected"';
			$insertForm .= "\n      <option value='$id'" . 
				$preselection.">\n        $grado $cognome $nome\n      </option>\n";
		}
		$insertForm .= "    </select></p>";

		return $insertForm;

	} // fine caso INSERT-CH e UPDATE-CH

	else { // caso non previsto
		return "<p>Errore durante la creazione del form, caso non previsto.</p>\n";
	} // fine caso non previsto
}

?>
