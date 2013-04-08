<?php

function insertQueryFactory($conn, $fiancata, $ideli, $tabella, $ap, $pr, $ch, $post_array) {

	// assembla una query di tipo INSERT

	if($tabella == "AP") {

		// ESEMPIO INSERT-AP valido:
		// INSERT INTO `differiti`.`table_differitiaperture`
		// 	(`id`, `elicottero`, `inconveniente`, `tipologia`, `dataInconveniente`,
		// 	`firmaApertura`) VALUES (NULL, '7', 'Test di inserimento', '3', '2013-04-04', '13');

		// conversione data gg/mm/aaaa in yyyy-mm-dd per MySQL
		$dataInconveniente = cleanDate($post_array['dataInconveniente']); 
		$inconveniente = cleanInput($conn, $post_array['inconveniente']); // pulizia e preparazione dati
		$insertAPQuery = "INSERT INTO `differiti`.`table_differitiaperture` (`id`, `elicottero`, `inconveniente`, " .
			"`tipologia`, `dataInconveniente`, `firmaApertura`) VALUES (NULL, ";
		$insertAPQuery .= "'$ideli', '$inconveniente', '{$post_array['tipologia']}', '$dataInconveniente', " .
			"'{$post_array['firmaApertura']}')";
		return $insertAPQuery;
	}

	else if($tabella == "PR") {
		// ESEMPIO INSERT-PR valido:
		// INSERT INTO `differiti`.`table_differitiannotazioni`
		// 	(`id`, `differito`, `note`) VALUES (NULL, '9', 'test di inserimento');
		$note = cleanInput($conn, $post_array['note']); // pulizia e preparazione dati
		$insertPRQuery = "INSERT INTO `differiti`.`table_differitiannotazioni` (`id`, `differito`, `note`) VALUES (NULL, ";
		$insertPRQuery .= "'$ap', '" . $note . "')";
		return $insertPRQuery;
	}

	else if($tabella == "CH") {

		// ESEMPIO INSERT-CH valido:
		// INSERT INTO `differiti`.`table_differitichiusure` (`id`, `differito`, `provvedimentoCorrettivoAdottato`,
		// 	`durataOreUomo`, `dataChiusura`, `firmaChiusura`) VALUES (NULL, '12', 'TEST CHIUSURA',
		// 	'11', '2013-04-04', '10');

		// conversione data gg/mm/aaaa in yyyy-mm-dd per MySQL
		$dataChiusura = cleanDate($post_array['dataChiusura']);
 		$durataOreUomo = cleanHour($post_array['durataOreUomo']);
		// pulizia e preparazione dati
		$provvedimentoCorrettivoAdottato = cleanInput($conn, $post_array['provvedimentoCorrettivoAdottato']); 
		$insertCHQuery = "INSERT INTO `differiti`.`table_differitichiusure` (`id`, `differito`, `provvedimentoCorrettivoAdottato`, " .
			"`durataOreUomo`, `dataChiusura`, `firmaChiusura`) VALUES (NULL, ";
		$insertCHQuery .= "'$ap', '$provvedimentoCorrettivoAdottato', $durataOreUomo, '$dataChiusura', '{$post_array['firmaChiusura']}')";
		return $insertCHQuery;
	}
	
	else {
		echo "<p>insertQueryFactory(): errore durante la creazione della query.</p>\n";
		// in caso di problemi restituisce una query "innocua" per non danneggiare il database
		return "SELECT * from differiti_view";
	}
}

function updateQueryFactory($conn, $fiancata, $ideli, $tabella, $ap, $pr, $ch, $post_array) {

	// assembla una query di tipo UPDATE

	if($tabella == "AP") {

		// ESEMPIO UPDATE-AP valido:
		// UPDATE  `differiti`.`table_differitiaperture` SET  `inconveniente` =
		// 	'Rimosso pannello di controllo del sistema Star Safire S/N 20031106-01.'
		// 	WHERE `table_differitiaperture`.`id` =6;

		// conversione data gg/mm/aaaa in yyyy-mm-dd per MySQL
		$dataInconveniente = cleanDate($post_array['dataInconveniente']); 
		$inconveniente = cleanInput($conn, $post_array['inconveniente']); // pulizia e preparazione dati
		$updateAPQuery  = "UPDATE `differiti`.`table_differitiaperture` SET ";
		$updateAPQuery .= "`inconveniente` = '$inconveniente', ";
		$updateAPQuery .= "`tipologia` = '{$post_array['tipologia']}', ";
		$updateAPQuery .= "`dataInconveniente` = '$dataInconveniente', ";
		$updateAPQuery .= "`firmaApertura` = '{$post_array['firmaApertura']}' ";
		$updateAPQuery .= "WHERE `table_differitiaperture`.`id` = '$ap'";

		return $updateAPQuery;
	}

	else if($tabella == "PR") {
		
		$note = cleanInput($conn, $post_array['note']); // pulizia e preparazione dati
		$updatePRQuery = "UPDATE `differiti`.`table_differitiannotazioni` SET ";
		$updatePRQuery .= "`note` = '$note' ";
		$updatePRQuery .= "WHERE `table_differitiannotazioni`.`id` = '$pr'";
		return $updatePRQuery;
	}

	else if($tabella == "CH") {

		// INSERT INTO `differiti`.`table_differitichiusure` (`id`, `differito`, `provvedimentoCorrettivoAdottato`,
		// 	`durataOreUomo`, `dataChiusura`, `firmaChiusura`) VALUES (NULL, '12', 'TEST CHIUSURA',
		// 	'11', '2013-04-04', '10');

		$dataChiusura = cleanDate($post_array['dataChiusura']);
 		$durataOreUomo = cleanHour($post_array['durataOreUomo']);
		// pulizia e preparazione dati
		$provvedimentoCorrettivoAdottato = cleanInput($conn, $post_array['provvedimentoCorrettivoAdottato']); 
		$updateCHQuery  = "UPDATE `differiti`.`table_differitichiusure` SET ";
		$updateCHQuery .= "`provvedimentoCorrettivoAdottato` = '$provvedimentoCorrettivoAdottato', ";
		$updateCHQuery .= "`durataOreUomo` = $durataOreUomo, ";
		$updateCHQuery .= "`dataChiusura` = '$dataChiusura', ";
		$updateCHQuery .= "`firmaChiusura` = '{$post_array['firmaChiusura']}' ";
		$updateCHQuery .= "WHERE `table_differitichiusure`.`id` = '$ch'";

		return $updateCHQuery;
	}

	else {
		echo "<p>updateQueryFactory(): errore durante la creazione della query.</p>\n";		
		return "SELECT * from differiti_view"; // in caso di problemi restituisce una query "innocua" per non danneggiare il database
	}
}

function deleteQueryFactory($conn, $fiancata, $ideli, $tableToUse, $idAP, $idPR, $idCH, $post_array) {

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

	else {
		echo "<p>deleteQueryFactory(): errore durante la creazione della query.</p>\n";		
		return "SELECT * from differiti_view"; // in caso di problemi restituisce una query "innocua" per non danneggiare il database
	}

	$sqlQ = "DELETE FROM $table WHERE $wheres";

	if ($deletePRsql != "") // se esiste una nota, va eliminata prima
		$sqlQ = $deletePRsql." ".$sqlQ;
	if ($deleteCHsql != "") // se esiste una chiusura, va eliminata prima
		$sqlQ = $deleteCHsql." ".$sqlQ;

	return $sqlQ;
}

function viewSingleRecordQueryFactory($AP) {
	if ($AP == "")
		$AP_sql = "is null";
	else
		$AP_sql = "= '".$AP."'";
	$query = <<<QUERY
SELECT
`numerofiancata` as Elicottero, `inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`, `provvedimentocorrettivoadottato` as provvedimento, `durataoreuomo` as `ore/uomo`, `datachiusura` as `data CH`, `firmach` as `firma CH`
FROM
`view_differiti`
WHERE
( (`idapertura` $AP_sql) )
QUERY;
	return $query;
}

?>
