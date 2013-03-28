<?php

// $AP = $_GET['AP'];
// $PR = $_GET['PR'];
// $CH = $_GET['CH'];

createForm('13', '21' ,''); // caso AP PR -> caso 3

function createForm($AP, $PR, $CH) { // riceve come parametri gli ID dalle tabelle differiti MySQL

	// impostazione variabili di default
	// pulsanti relativi alle aperture dei differiti (AP)
	// $aggiungiApertura = TRUE; // negativo, non applicabile in quest'area del sito
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
	echo "<p>Errore: è necessario un parametro relativo a un differito aperto.</p>\n";

	// caso 1: differito (AP)
	if($AP != "" && $PR == "" && $CH == "") {
		$aggiungiNota = TRUE;
		$aggiungiChiusura = TRUE;
	}

	// caso 2: differito (AP) (CH)
	if($AP != "" && $PR == "" && $CH != "") {
		$aggiungiNota = TRUE;
		$modificaChiusura = TRUE;
		$annullaChiusura = TRUE;
	}

	// caso 3: differito (AP) (PR)
	if($AP != "" && $PR != "" && $CH == "") {
		$modificaNota = TRUE;
		$annullaNota = TRUE;
		$aggiungiChiusura = TRUE;
	}

	// caso 4: differito (AP) (PR) (CH)
	if($AP != "" && $PR != "" && $CH != "") {
		$modificaNota = TRUE;
		$annullaNota = TRUE;
		$modificaChiusura = TRUE;
		$annullaChiusura = TRUE;
	}

	// creazione matrice visualizzazione pulsanti
	$buttonsToDisplay = array( ('APa' => $aggiungiApertura, 'APm' => $modificaApertura, 'APc' => $annullaApertura),
				   ('PRa' => $aggiungiNota, 'PRm' => $modificaNota, 'PRc' => $annullaNota),
				   ('PRa' => $aggiungiChiusura, 'PRm' => $modificaChiusura, 'PRc' => $annullaChiusura)
				 );
	print_r($buttonsToDisplay);
	return $buttonsToDisplay;

} // end function

?>
