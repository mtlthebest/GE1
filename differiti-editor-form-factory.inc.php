<?php

function editorFormFactory($conn, $eli, $ideli, $action, $table, $AP, $PR, $CH) {
	
	if ($action == "DELETE") { // caso DELETE: più semplice, non richiede campi di inserimento per il form
		$alsoPRandCH = "";
		if ($table == "AP")
			$alsoPRandCH = "Questo comporterà anche l'eliminazione di eventuali \"PR\" e \"CH\" correlati. ";
		return 	"<p>Stai per eliminare il record \"$table\" selezionato, relativo alla fiancata $eli. ".
			"$alsoPRandCH"."Confermi?</p>\n";
	} // fine caso DELETE
	
	else if ($action == "INSERT") { // caso INSERT
		return "<p>Codice per la creazione del form nel caso INSERT...</p>\n";
	} // fine caso INSERT

	else if ($action == "UPDATE") { // caso UPDATE, più complesso
		return "<p>Codice per la creazione del form nel caso UPDATE...</p>\n";
	} // fine caso UPDATE

	else { // caso non previsto
		return "<p>Errore durante la creazione del form, caso non previsto.</p>\n";
	} // fine caso non previsto
}

?>
