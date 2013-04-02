<?php

function determineFormButtons($AP, $PR, $CH) { // riceve come parametri gli ID dalle tabelle differiti MySQL

	// impostazione variabili di default

	$caso = "";
	// pulsanti relativi alle aperture dei differiti (AP)
	$aggiungiApertura = FALSE; // solo per completezza matrice, non applicabile in quest'area del sito
	$modificaApertura = TRUE;
	$annullaApertura = TRUE;

	// pulsanti relativi alle note/prosecuzioni dei differiti (PR)
	$aggiungiNota = FALSE;
	$modificaNota = FALSE;
	$annullaNota = FALSE;

	// pulsanti relativi alle chiusure dei differiti (CH)
	$aggiungiChiusura = FALSE;
	$modificaChiusura = FALSE;
	$annullaChiusura = FALSE;

	// Suddivisione in casi
	// caso 0: differito non aperto (inesistente) -> pagina di modifica non disponibile
	// caso 1: differito (AP)
	// caso 2: differito (AP) (CH)
	// caso 3: differito (AP) (PR)
	// caso 4: differito (AP) (PR) (CH)

	// caso 0: differito non aperto (inesistente) -> pagina di modifica non disponibile
	if($AP == "") {
		$caso = "0";
		$modificaApertura = FALSE;
		$annullaApertura = FALSE;
		echo "<p>Errore: è necessario un parametro relativo a un differito aperto.</p>\n";
	}

	// caso 1: differito (AP)
	if($AP != "" && $PR == "" && $CH == "") {
		$caso = "1";
		$aggiungiNota = TRUE;
		$aggiungiChiusura = TRUE;
	}

	// caso 2: differito (AP) (CH)
	if($AP != "" && $PR == "" && $CH != "") {
		$caso = "2";
		$aggiungiNota = TRUE;
		$modificaChiusura = TRUE;
		$annullaChiusura = TRUE;
	}

	// caso 3: differito (AP) (PR)
	if($AP != "" && $PR != "" && $CH == "") {
		$caso = "3";
		$modificaNota = TRUE;
		$annullaNota = TRUE;
		$aggiungiChiusura = TRUE;
	}

	// caso 4: differito (AP) (PR) (CH)
	if($AP != "" && $PR != "" && $CH != "") {
		$caso = "4";
		$modificaNota = TRUE;
		$annullaNota = TRUE;
		$modificaChiusura = TRUE;
		$annullaChiusura = TRUE;
	}

	// creazione matrice visualizzazione pulsanti
	$buttonsToDisplay = array( 'aperture' => array('crea' => $aggiungiApertura, 'modifica' => $modificaApertura, 'annulla' => $annullaApertura),
				   'note'     => array('crea' => $aggiungiNota, 'modifica' => $modificaNota, 'annulla' => $annullaNota),
				   'chiusure' => array('crea' => $aggiungiChiusura, 'modifica' => $modificaChiusura, 'annulla' => $annullaChiusura)
				 );

	return $buttonsToDisplay; // viene restituita una matrice che determina i pulsanti disponibili a seconda dello stato del differito

} // end function

function buildForm($buttons, $fiancata, $idEli, $AP, $PR, $CH) {

	// inizio form
	echo "<form class=\"modifyDifferiti\" action=\"differiti-editor.php?eli=$fiancata&ideli=$idEli&AP=$AP&PR=$PR&CH=$CH\" method=\"post\">\n";

	// radio button per la scelta dell'azione da eseguire, suddivisione in 8 casi:

	// 1. - UPDATE-AP (modifica AP)
	// 2. - DELETE-AP (elimina AP ed eventuali PR e CH)

	// 3. - INSERT-PR (aggiunge una nota / PR)
	// 4. - UPDATE-PR (edita una nota / PR esistente)
	// 5. - DELETE-PR (elimina una nota / PR esistente)

	// 6. - INSERT-CH (aggiunge una CH)
	// 7. - UPDATE-CH (edita una CH esistente)
	// 8. - DELETE-CH (elimina una CH esistente)

	// fieldset AP
	echo "<fieldset>\n";
	echo "<legend>Apertura differito (AP)</legend>\n";
	if($buttons['aperture']['modifica']) // 1. - UPDATE-AP (modifica AP)
		echo "<p><input type=\"radio\" name=\"action\" value=\"UPDATE-AP\"/>Modifica apertura esistente</p>\n";
	if($buttons['aperture']['annulla']) // 2. - DELETE-AP (elimina AP ed eventuali PR e CH)
		echo "<p><input type=\"radio\" name=\"action\" value=\"DELETE-AP\"/>Annulla apertura esistente (elimina anche eventuali PR e CH)</p>\n";
	echo "</fieldset>\n";	

	echo "<fieldset>\n";
	echo "<legend>Prosecuzione differito / annotazioni (PR)</legend>\n";
	if($buttons['note']['crea']) // 3. - INSERT-PR (aggiunge una nota / PR)
		echo "<p><input type=\"radio\" name=\"action\" value=\"INSERT-PR\"/>Scrivi nuova annotazione</p>\n";
	if($buttons['note']['modifica']) // 4. - UPDATE-PR (edita una nota / PR esistente)
		echo "<p><input type=\"radio\" name=\"action\" value=\"UPDATE-PR\"/>Modifica annotazione esistente</p>\n";
	if($buttons['note']['annulla'])	// 5. - DELETE-PR (elimina una nota / PR esistente)
		echo "<p><input type=\"radio\" name=\"action\" value=\"DELETE-PR\"/>Elimina annotazione esistente</p>\n";
	echo "</fieldset>\n";	

	echo "<fieldset>\n";
	echo "<legend>Chiusura differito (CH)</legend>\n";
	if($buttons['chiusure']['crea']) // 6. - INSERT-CH (aggiunge una CH)
		echo "<p><input type=\"radio\" name=\"action\" value=\"INSERT-CH\"/>Chiudi differito</p>\n";
	if($buttons['chiusure']['modifica']) // 7. - UPDATE-CH (edita una CH esistente)
		echo "<p><input type=\"radio\" name=\"action\" value=\"UPDATE-CH\"/>Modifica chiusura esistente</p>\n";
	if($buttons['chiusure']['annulla']) // 8. - DELETE-CH (elimina una CH esistente)
		echo "<p><input type=\"radio\" name=\"action\" value=\"DELETE-CH\"/>Annulla chiusura esistente</p>\n";
	echo "</fieldset>\n";	

	// pulsante submit
	echo "<p><img src=\"edit_icon_lefthanded.png\" alt=\"\" /><input type=\"submit\" value=\"Procedi\" /></p>\n";
	
	// fine form
	echo "</fieldset>\n";
	echo "</form>";

}

?>
