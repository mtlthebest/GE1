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

function buildForm($buttons) {

	// inizio form
	echo "<form action=\"differiti-modifica.php\" method=\"post\">\n";

	echo "<fieldset>\n";
	echo "<legend>Scelta della modifica da apportare al provvedimento correttivo differito</legend>\n";

	// radio button per la scelta dell'azione da eseguire
	if($buttons['aperture']['modifica'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Modifica apertura esistente</p>\n";
	if($buttons['aperture']['annulla'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Annulla apertura esistente</p>\n";
	if($buttons['note']['crea'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Scrivi nuova annotazione</p>\n";
	if($buttons['note']['modifica'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Modifica annotazione esistente</p>\n";
	if($buttons['note']['annulla'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Elimina annotazione esistente</p>\n";
	if($buttons['chiusure']['crea'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Chiudi differito</p>\n";
	if($buttons['chiusure']['modifica'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Modifica chiusura esistente</p>\n";
	if($buttons['chiusure']['annulla'])
		echo "<p><input type=\"radio\" name=\"action\" value=\"APmod\"/>Annulla chiusura esistente</p>\n";

	// pulsante submit
	echo "<p><input type=\"submit\" value=\"Procedi\" /></p>\n";
	
	// fine form
	echo "</fieldset>\n";
	echo "</form>";

}

?>
