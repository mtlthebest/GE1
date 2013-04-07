<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Personale";
$h1Title = "Segreteria Comando";
$h1HyperLink = "";
$h2Title = "Gestione del personale militare in forza al Gruppo";
$h2HyperLink = "personale-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// dichiarazione variabili (ricevute dal form html)
$idGrado = $_POST['gradeID'];
$cognome = trim(strip_tags($_POST['surname']));
$nome = trim(strip_tags($_POST['name']));

// validazione dati e preparazione dati
$idGrado = validateGrade($idGrado);
$cognome = validate(strtoupper($cognome)); // controlli: check solo caratteri, anti-injection, trim spazi, tutto maiuscolo
$nome = validate(ucwords($nome)); // controlli: check solo caratteri, anti-injection, trim spazi, iniziale maiuscola

function validateGrade($grade) {
	if($grade == '22') {
		echo "<p>Errore di validazione, grado non valido!</p>";
		return "";
	}
	else
		return $grade;
}

function validate($var) {
	if(empty($var)) {
		echo "<p>Errore di validazione, inserita variabile vuota!</p>";
		return "";
	}
	else if(!preg_match('/^[a-z]+((-| )?[a-z]+)?$/i', $var)) { // utilizzata espressione regolare per consentire nomi e cognomi validi
		echo "<p>Errore di validazione, inserita variabile con caratteri non consentiti!</p>";
		return "";
		}
	else
		return $var;
}

// preparazione query SQL
$insertQuery = <<<INSERTQUERY
INSERT INTO  `differiti`.`table_personale` (
`id` ,
`grado` ,
`cognome` ,
`nome`
)
VALUES (
NULL, 
INSERTQUERY;
$insertQuery .= "'".$idGrado."', ";
$insertQuery .= "'".$cognome."', ";
$insertQuery .= "'".$nome."'\n);";

// connessione al database e esecuzione query (se check validazione OK)
if(!empty($idGrado) && !empty($cognome) && !empty($nome)) {
	$cxn = connectToDifferitiDatabase();
	$result = sendQuery($cxn, $insertQuery);
}

// mostra pagina di riepilogo personale e nominativo inserito evidenziato
if(!empty($idGrado) && !empty($cognome) && !empty($nome))
	stampaPaginaWeb($insertQuery);

function stampaPaginaWeb($insertQ) {
	echo "<p style=\"text-align: center\">Query inviata al database:</p>";
	echo "<p style=\"text-align: center\"><i>$insertQ</i></p>";
	echo "<p style=\"text-align: center\">Controlla <a href=\"personale-home.php#table\">qui</a> che il nominativo sia stato correttamente inserito.</p>";
	return;
}

// Chiusura della connessione al database MySQL
mysqli_close($cxn);

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
