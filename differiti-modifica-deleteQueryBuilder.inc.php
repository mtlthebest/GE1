<?php

function deleteQueryBuilder($tableToUse, $idAP, $idPR, $idCH) {

	/* esempio di query di eliminazione valida:
	DELETE FROM `differiti`.`table_differitiannotazioni`
	WHERE `table_differitiannotazioni`.`id` = 1 AND `table_differitiannotazioni`.`differito` = 7
	*/

	$deletePRsql = "";
	$deleteCHsql = "";

	if ($tableToUse == 'PR') {
		$table = "`differiti`.`table_differitiannotazioni`";
		$wheres = "`table_differitiannotazioni`.`id` = $idPR AND `table_differitiannotazioni`.`differito` = $idAP";
	}

	else if ($tableToUse == 'CH') {
		$table = "`differiti`.`table_differitichiusure`";
		$wheres = "`table_differitichiusure`.`id` = $idCH AND `table_differitichiusure`.`differito` = $idAP";
	}

	else if ($tableToUse == 'AP') { // in questo caso bisogna eliminare prima eventuali note o chiusure
		
		if ($idPR != "") { // nel caso esista una nota / PR, la elimina prima
			$tablePR = "`differiti`.`table_differitiannotazioni`";
			$wheresPR = "`table_differitiannotazioni`.`id` = $idPR AND `table_differitiannotazioni`.`differito` = $idAP";
			$deletePRsql = "DELETE FROM $tablePR WHERE $wheresPR;";
		}

		if ($idCH != "") { // nel caso esista una CH, la elimina prima
			$tableCH = "`differiti`.`table_differitichiusure`";
			$wheresCH = "`table_differitichiusure`.`id` = $idCH AND `table_differitichiusure`.`differito` = $idAP";
			$deleteCHsql = "DELETE FROM $tableCH WHERE $wheresCH;";
		}

		$table = "`differiti`.`table_differitiaperture`";
		$wheres = "`table_differitiaperture`.`id` = $idAP";
	}

	else
		return "Errore! Controlla i parametri passati alla funzione deleteQueryBuilder()!";

	$sqlQ = "DELETE FROM $table WHERE $wheres;";

	if ($deletePRsql != "") // se esiste una nota, va eliminata prima
		$sqlQ = $deletePRsql." ".$sqlQ;
	if ($deleteCHsql != "") // se esiste una chiusura, va eliminata prima
		$sqlQ = $deleteCHsql." ".$sqlQ;

	return $sqlQ;
}

?>
