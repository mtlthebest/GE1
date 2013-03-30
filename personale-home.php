<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Personale";
$h1Title = "Segreteria Comando";
$h1HyperLink = "";
$h2Title = "Elenco del personale militare";
$h2HyperLink = "";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// dichiarazione variabili
$query = "SELECT grado, cognome, nome FROM view_personale";
$caption = "<a name=\"table\" id=\"table\"></a>Elenco del personale militare in forza al Gruppo";

// sequenza programma principale
$cxn = connectToDatabase($host, $user, $password, $database);
$result = sendQuery($cxn, $query);
stampaTabella($result, $caption);
echo '<p style="text-align: center">Per aggiungere un nuovo nominativo alla lista, utilizza questo <a href="personale-form-upload.php">form</a>.</p>';

// funzioni utilizzate
function stampaTabella($R, $capt)
{
	echo "  <table border=\"1\" summary=\"Questa tabella riepiloga i risultati della query SQL\">\n";
	setTableCaption($capt);
	stampaIntestazioneTabella($R);
	stampaDatiTabella($R);
	echo "  </table>\n";
	return;
}

function setTableCaption($capt)
{
	echo "    <caption>\n";
        echo $capt;
	echo "\n    </caption>\n";
	return;
}

function stampaIntestazioneTabella($R)
{
	echo "\n    <tr>";
	$finfo = mysqli_fetch_fields($R);
	foreach($finfo as $field)
	{
	echo "\n      <th>".ucfirst($field->name)."</th>\n";
	}
	echo "    </tr>\n";
	return;
}

function stampaDatiTabella($R)
{
	for ($i = 0; $i < mysqli_num_rows($R); $i++)
	{
		echo "\n    <tr>";
        	$row = mysqli_fetch_row($R);
        	foreach($row as $value)
        	{
           		echo "\n      <td>".$value."</td>\n";
        	}
        	echo "    </tr>\n";
	}
	return;
}

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
