<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

// costruzione query e caption specifiche
include("differiti-factory-queriesAndCaptions.inc.php");
$query   = queryAndCaptionFactory($_GET["eli"], $_GET["chiusi"], $_GET["tipo"], "query");
$caption = queryAndCaptionFactory($_GET["eli"], $_GET["chiusi"], $_GET["tipo"], "caption");
// adminMode = $_POST['...']; // ancora da sviluppare sistema per determinare se l'utente è admin o in sola lettura
$adminMode = TRUE;

// sequenza programma principale
$cxn    = connectToDatabase($host, $user, $password, $database);
$result = sendQuery($cxn, $query);
stampaTabella($result, $caption, $adminMode);

// funzioni utilizzate
function stampaTabella($R, $capt, $isAdmin)
{
    echo "  <table border=\"1\" summary=\"Questa tabella riepiloga i risultati della query SQL\">\n";
    setTableCaption($capt, $isAdmin);
    stampaIntestazioneTabella($R, $isAdmin);
    $colonneDate  = individuaDate($R); // un array indica quali colonne contengono date per la successiva formattazione
    $colonneTesto = individuaTesto($R); // un array indica quali colonne contengono molto testo per la formattazione
    $colonneAP_PR_CH = individuaAP_PR_CH($R); // un array indica quali colonne contengono gli id di AP, PR e CH
    stampaDatiTabella($R, $isAdmin, $colonneDate, $colonneTesto, $colonneAP_PR_CH);
    echo "  </table>\n";
    echo "\n  <p style=\"text-align: center; font-size: 75%; color: gray\">conteggio righe: ".mysqli_num_rows($R)."</p>";
    return;
}

function setTableCaption($capt, $admin)
{
    echo "    <caption>\n";
    echo "      " . $capt;
    // in caso di amministratore, si segnala nella didascalia della tabella
    if($admin)
        echo " / modalità amministratore";
    echo "\n    </caption>\n";
    return;
}

function stampaIntestazioneTabella($R, $admin)
{
    echo "\n    <tr>";
    $finfo = mysqli_fetch_fields($R);
    foreach ($finfo as $field) {
        if ( ($field->name) == 'AP' || ($field->name) == 'PR' || ($field->name) == 'CH' )
            continue; // nel caso di amministratore, evita di stampare gli header per le colonne AP, PR e CH
        echo "\n      <th>" . ucfirst($field->name) . "</th>\n";
    }
    // in caso di amministratore, si aggiunge la colonna con i link per la modifica
    if($admin)
        echo "\n      <th style=\"color: red\">Modifica</th>\n";
    echo "    </tr>\n";
    return;
}

function stampaDatiTabella($R, $admin, $dateColumns, $textColumns, $idColumns)
{
    mysqli_data_seek($R, 0); // reset posizione pointer per la variabile $R, nel caso fosse già stata impiegata

    for ($i = 0; $i < mysqli_num_rows($R); $i++) {

        $row = $R->fetch_assoc();  
        $editLink = "differiti-modifica.php?AP=";
        $editLink .= $row["AP"]."&PR=";
        $editLink .= $row["PR"]."&CH=";
        $editLink .= $row["CH"];

        echo "\n    <tr>";

        $j   = 0; // inizializzazione contatore colonne
        foreach ($row as $value) {

            if (in_array($j, $dateColumns) && $value != "") // controlla se tra le colonne relative alle date
                $value = date("d/m/Y", strtotime($value)); // nel caso, formatta la data
            
            if (in_array($j, $idColumns)) { // controlla se tra le colonne relative alle date
                $j++;
                continue; // passa alla prossima colonna, evitando di stampare AP, PR o CH
            }

            if (in_array($j, $textColumns) && $value != "") { // controlla se tra le colonne con testo lungo
                echo "\n      <td style=\"text-align: left\">" . $value . "</td>\n"; // nel caso, formatta con rientro
                $j++;
                continue; // passa alla prossima colonna
            }
            echo "\n      <td>" . $value . "</td>\n";
            $j++;
        }
        // in caso di amministratore, si aggiunge la colonna con i link per la modifica
        if($admin)
            echo "\n      <td><a href=\"$editLink\"><img src=\"edit_icon_righthanded.png\" alt=\"icona_edita\" /></a></td>\n";
        echo "    </tr>\n";
    }
    return;
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

function individuaAP_PR_CH($res)
{
    $AP_PR_CHArray = array();
    $fields    = mysqli_num_fields($res);
    for ($i = 0; $i < $fields; $i++) {
        $finfo = $res->fetch_field_direct($i);
        if (($finfo->name) == 'AP' || ($finfo->name) == 'PR' || ($finfo->name) == 'CH') // individua colonne AP, PR e CH
            array_push($AP_PR_CHArray, $i);
    }
    return $AP_PR_CHArray;
}

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
