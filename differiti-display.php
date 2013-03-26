<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Elenco provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// costruzione query e caption specifiche
include("differiti-factory-queriesAndCaptions.inc.php");
$query   = queryAndCaptionFactory($_GET["eli"], $_GET["chiusi"], $_GET["tipo"], "query");
$caption = queryAndCaptionFactory($_GET["eli"], $_GET["chiusi"], $_GET["tipo"], "caption");

// sequenza programma principale
$cxn    = connectToDatabase($host, $user, $password, $database);
$result = sendQuery($cxn, $query);
stampaTabella($result, $caption);

// funzioni utilizzate
function stampaTabella($R, $capt)
{
    echo "  <table border=\"1\" summary=\"Questa tabella riepiloga i risultati della query SQL\">\n";
    setTableCaption($capt);
    stampaIntestazioneTabella($R);
    $colonneDate  = individuaDate($R); // un array indica quali colonne contengono date; utilizzato per la successiva formattazione
    $colonneTesto = individuaTesto($R); // un array indica quali colonne contengono molto testo; utilizzato per la successiva formattazione
    stampaDatiTabella($R, $colonneDate, $colonneTesto);
    echo "  </table>\n";
    echo "\n  <p style=\"text-align: center; font-size: 75%; color: gray\">conteggio righe: ".mysqli_num_rows($R)."</p>";
    return;
}

function setTableCaption($capt)
{
    echo "    <caption>\n";
    echo "      " . $capt;
    echo "\n    </caption>\n";
    return;
}

function stampaIntestazioneTabella($R)
{
    echo "\n    <tr>";
    $finfo = mysqli_fetch_fields($R);
    foreach ($finfo as $field) {
        echo "\n      <th>" . ucfirst(fixEncoding($field->name)) . "</th>\n";
    }
    echo "    </tr>\n";
    return;
}

function stampaDatiTabella($R, $dateColumns, $textColumns)
{
    for ($i = 0; $i < mysqli_num_rows($R); $i++) {
        echo "\n    <tr>";
        $row = mysqli_fetch_row($R);
        $j   = 0; // inizializzazione contatore colonne
        foreach ($row as $value) {
            if (in_array($j, $dateColumns) && $value != "") // controlla se l'attuale colonna "j" è tra le colonne relative alle date
                $value = date("d/m/Y", strtotime($value)); // nel caso, formatta la data per la visualizzazione in tabella
            
            if (in_array($j, $textColumns) && $value != "") { // controlla se l'attuale colonna "j" è tra le colonne relative al testo lungo
                echo "\n      <td style=\"text-align: left\">" . fixEncoding($value) . "</td>\n"; // nel caso, formatta con rientro a sinistra
                $j++;
                continue;
            }
            echo "\n      <td>" . fixEncoding($value) . "</td>\n";
            $j++;
        }
        echo "    </tr>\n";
    }
    return;
}

function queryFactory($chopper, $closed, $type)
{
    return "SELECT * FROM view_differiti_nuova";
}

function individuaDate($res)
{
    $dateArray = array();
    $fields    = mysqli_num_fields($res);
    for ($i = 0; $i < $fields; $i++) {
        $finfo = $res->fetch_field_direct($i);
        if (($finfo->type) == 10) // tipo associato alle date: 10
            array_push($dateArray, $i);
    }
    return $dateArray;
}

function individuaTesto($res)
{
    $textArray = array();
    $fields    = mysqli_num_fields($res);
    for ($i = 0; $i < $fields; $i++) {
        $finfo = $res->fetch_field_direct($i);
        if (($finfo->type) == 252) // tipo associato al testo lungo: 252
            array_push($textArray, $i);
    }
    return $textArray;
}

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
