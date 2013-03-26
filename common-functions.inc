<?php

function connectToDatabase($H, $U, $P, $D) {
	return mysqli_connect($H, $U, $P, $D);	
}

function sendQuery($C, $Q) {
	mysqli_query($C, "SET NAMES utf8"); // imposta la connessione per utilizzare la codifica UTF-8
	return mysqli_query($C, $Q);
}

function fixEncoding($in_str) { // temporaneamente disabilitato per debug codifica UTF-8
	//$cur_encoding = mb_detect_encoding($in_str);
	//if ($cur_encoding == "UTF-8" && mb_check_encoding($in_str, "UTF-8"))
		return $in_str;
	//else
	//	return utf8_encode($in_str);
}

function reverseEncoding($in_str) {
	return utf8_decode($in_str);
}

?>
