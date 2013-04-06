<?php

function connectToDatabase($H, $U, $P, $D) {
	$conn = mysqli_connect($H, $U, $P, $D) or die("\n<p>connectToDatabase(): Errore di connessione.</p>\n");
	return $conn;	
}

function sendQuery($C, $Q) {
	mysqli_query($C, "SET NAMES utf8") // imposta la connessione per utilizzare la codifica UTF-8
		or die("\n<p>sendQuery(): Errore durante l'esecuzione della query (SET NAMES).</p>\n");
	$res = mysqli_query($C, $Q) or die("\n<p>sendQuery(): Errore durante l'esecuzione della query.</p>\n");
	return $res;
}

/* function fixEncoding($in_str) { // temporaneamente disabilitato per debug codifica UTF-8
	//$cur_encoding = mb_detect_encoding($in_str);
	//if ($cur_encoding == "UTF-8" && mb_check_encoding($in_str, "UTF-8"))
		return $in_str;
	//else
	//	return utf8_encode($in_str);
} */

function reverseEncoding($in_str) {
	return utf8_decode($in_str);
}

function cleanInput($connection, $inputText) {
	$string = ucfirst(trim(strip_tags($inputText))); // rimuove codice malevolo, rimuove spazi superflui prima e dopo, prima lettera maiuscola
	if (substr($string, -1) != ".") // controlla se l'ultimo carattere è un punto
		$string .= "."; // chiude il testo con un punto se mancante
	if (substr($string, -2) == ".." && substr($string, -3) != "...") // controlla se vi sono due punti alla fine del testo
		$string = substr($string, 0, -1); // rimuove l'eventuale doppio punto
	return mysqli_real_escape_string($connection, $string);
}

function oggi() {
	return date('d/m/Y');
}

function cleanDate($stringaData) {
	// esempio di stringa attesa: '20/04/2012'
	$stringaData = str_replace('/', '-', $stringaData);
	return date('Y-m-d', strtotime($stringaData)); // output: formato data compatibile MySQL (2012-04-20)
}

function cleanHour($stringaOra) {
	// esempio di stringa attesa: ' 21,0'
	$stringaOra = trim(strip_tags($stringaOra));
	$stringaOra = str_replace(',', '.', $stringaOra);
	if(is_numeric($stringaOra)) // se il numero è valido, lo ritorna
		return "'$stringaOra'"; // output: formato ora compatibile MySQL (21.0)
	else
		return "NULL"; // qui non è un "NULL" PHP, ma SQL
}

?>
