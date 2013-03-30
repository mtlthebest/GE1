<?php

function singoloStampaTabella($res, $capt) {
    echo "  <table border=\"1\" summary=\"Questa tabella riepiloga i risultati della query SQL\">\n";
    singoloSetTableCaption($capt);
    singoloStampaIntestazioneTabella($res);
    $colonneDate  = individuaDate($res); // un array indica quali colonne contengono date per la successiva formattazione
    $colonneTesto = individuaTesto($res); // un array indica quali colonne contengono molto testo per la formattazione
    singoloStampaDatiTabella($res, $colonneDate, $colonneTesto);
    echo "  </table>\n";
    echo "\n<p></p>\n";
    return;
}

function singoloSetTableCaption($capt) { // semplificato
    echo "    <caption>\n";
    echo "      " . $capt;
    echo "\n    </caption>\n";
    return;
}

function singoloStampaIntestazioneTabella($R)
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

function singoloStampaDatiTabella($R, $dateColumns, $textColumns) {
    mysqli_data_seek($R, 0); // reset posizione pointer per la variabile $R, nel caso fosse già stata impiegata

    for ($i = 0; $i < mysqli_num_rows($R); $i++) {

        echo "\n    <tr>";
        $row = $R->fetch_assoc();  
        $j   = 0; // inizializzazione contatore colonne
        foreach ($row as $value) {

            if (in_array($j, $dateColumns) && $value != "") // controlla se tra le colonne relative alle date
                $value = date("d/m/Y", strtotime($value)); // nel caso, formatta la data
            
            if (in_array($j, $textColumns) && $value != "") { // controlla se tra le colonne con testo lungo
                echo "\n      <td style=\"text-align: left\">" . $value . "</td>\n"; // nel caso, formatta con rientro
                $j++;
                continue; // passa alla prossima colonna
            }
            echo "\n      <td>" . $value . "</td>\n";
            $j++;
        }
        echo "    </tr>\n";
    }
    return;
}

function individuaDate($res) {
    $dateArray = array();
    $fields    = mysqli_num_fields($res);
    for ($i = 0; $i < $fields; $i++) {
        $finfo = $res->fetch_field_direct($i);
        if (($finfo->type) == 10) // tipo associato alle date: 10
            array_push($dateArray, $i);
    }
    return $dateArray;
}

function individuaTesto($res) {
    $textArray = array();
    $fields    = mysqli_num_fields($res);
    for ($i = 0; $i < $fields; $i++) {
        $finfo = $res->fetch_field_direct($i);
        if (($finfo->type) == 252) // tipo associato al testo lungo: 252
            array_push($textArray, $i);
    }
    return $textArray;
}

?>
