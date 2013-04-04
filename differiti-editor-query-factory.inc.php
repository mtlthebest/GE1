<?php

function insertQueryFactory($conn, $fiancata, $ideli, $tabella, $ap, $pr, $ch, $post_array) { // assembla una query di tipo INSERT

	echo "<p>insertQueryFactory: chiamata alla funzione.</p>\n";

	if($tabella == "AP") {
		// ESEMPIO INSERT-AP valido:
		// INSERT INTO `differiti`.`table_differitiaperture` (`id`, `elicottero`, `inconveniente`, `tipologia`, `dataInconveniente`,
		// 	`firmaApertura`) VALUES (NULL, '7', 'Test di inserimento', '3', '2013-04-04', '13');
		$dataInconveniente = cleanDate($post_array['dataInconveniente']); // conversione data gg/mm/aaaa in yyyy-mm-dd per MySQL
		$inconveniente = cleanInput($conn, $post_array['inconveniente']); // pulizia e preparazione dati
		$insertAPQuery = "INSERT INTO `differiti`.`table_differitiaperture` (`id`, `elicottero`, `inconveniente`, " .
			"`tipologia`, `dataInconveniente`, `firmaApertura`) VALUES (NULL, ";
		$insertAPQuery .= "'$ideli', '$inconveniente', '{$post_array['tipologia']}', '$dataInconveniente', " .
			"'{$post_array['firmaApertura']}')";
		return $insertAPQuery;
	}

	else if($tabella == "PR") {
		// ESEMPIO INSERT-PR valido:
		// INSERT INTO `differiti`.`table_differitiannotazioni` (`id`, `differito`, `note`) VALUES (NULL, '9', 'test di inserimento');
		$note = cleanInput($conn, $post_array['note']); // pulizia e preparazione dati
		$insertPRQuery = "INSERT INTO `differiti`.`table_differitiannotazioni` (`id`, `differito`, `note`) VALUES (NULL, ";
		$insertPRQuery .= "'$ap', '" . $note . "')";
		return $insertPRQuery;
	}

	else if($tabella == "CH") {
		// ESEMPIO INSERT-CH valido:
	}
	
	else {
		echo "<p>insertQueryFactory(): errore durante la creazione della query.</p>\n";		
		return "SELECT * from differiti_view"; // in caso di problemi restituisce una query "innocua" per non danneggiare il database
	}
}

function updateQueryFactory($conn, $fiancata, $ideli, $tabella, $ap, $pr, $ch, $post_array) { // assembla una query di tipo UPDATE
	return "SELECT * from differiti_view";
}

function deleteQueryFactory($conn, $fiancata, $ideli, $tabella, $ap, $pr, $ch, $post_array) { // assembla una query di tipo DELETE
	return "SELECT * from differiti_view";
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
