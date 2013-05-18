<?php

# FUNZIONE rowFactory

function rowFactory($eli, $ideli, $result) {
    
    // Nel caso $result sia stata usata altre volte, resetta la posizione del pointer
    mysqli_data_seek($result, 0);

    // Esplodo la riga, ottenendo le una variabile per ogni campo della riga
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
	if ($elicottero == $eli) { // $elicottero è uno dei campi del result-set
            break; // mantiene le ultime variabili trovate, relative all'elicottero interessato
        }
    }
    
    // Compilazione casella #1: Numero di fiancata -> GRASSETTO, FORMATTAZIONE CONDIZIONALE
    if ($eli == "tutte le fiancate")
        compileCell("td", "");
    else if ($totaleaperti == '0')
        compileCell("th", "<img src=\"EH-101_icon_mini.png\" alt=\"\" /> "."$eli"); // mostra cella con il solo numero di fiancata
    else {
        compileYellowCell("td", "<img src=\"EH-101_icon_mini.png\" alt=\"\" /> "."$eli");
    }
    
    // Compilazione casella #2: casella freccia caricamento differito -> ICONA, LINK, NON DISPONIBILE PER TUTTE LE FIANCATE
    if ($eli == "tutte le fiancate") {
    }
    else if ($eli == "")
        compileCell("td", ""); // mostra cella vuota nel caso la riga sia relativa a tutte le fiancate
    else {
        $imageFile = "upload_icon_mini.png";
        $imageAlt  = "carica";
        $cell      = prepareUploadHyperlink($eli, $ideli, compileImage($imageFile, $imageAlt));
        compileCell("td", $cell);
    }

    // Compilazione casella #3: Inconvenienti -> LINK
    if ($inconvenienti == '0')
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "1"; // idem
        $cell   = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, $inconvenienti);
        compileCell("td", $cell);
    }
    
    // Compilazione casella #4: Ispezioni -> LINK
    if ($ispezioni == '0')
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "2"; // idem
        $cell   = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, $ispezioni);
        compileCell("td", $cell);
    }
    
    // Compilazione casella #5: Sostituzioni -> LINK
    if ($sostituzioni == '0')
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "3"; // idem
        $cell   = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, $sostituzioni);
        compileCell("td", $cell);
    }

    // Compilazione casella #5-bis: Necessità logistiche -> LINK
    if ($logistiche == '0')
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "6"; // idem
        $cell   = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, $logistiche);
        compileCell("td", $cell);
    }

    // Compilazione casella #6: Altri -> LINK
    if ($altri == '0')
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "4"; // idem
        $cell   = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, $altri);
        compileCell("td", $cell);
    }
    
    // Compilazione casella #7: Totale ancora aperti -> LINK, FORMATTAZIONE CONDIZIONALE, TABLE HEADER (grassetto)
    if ($totaleaperti == '0')
        compileCell("th", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = ""; // idem
        $cell   = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, $totaleaperti);
        if ($eli == "tutte le fiancate")
            compileCell("td", $cell);
        else
            compileYellowCell("th", $cell);
    }
    
    // Compilazione casella #8: casella lente ricerca tutti i differiti (anche chiusi) -> ICONA, LINK
    $chiusi    = 1; // variabile passata nell'URL, specifica per ogni colonna della tabella
    $tipo      = ""; // idem
    $imageFile = "search_lens_mini.png";
    $imageAlt  = "consulta";
    $cell      = prepareFetchHyperlink($ideli, $eli, $chiusi, $tipo, compileImage($imageFile, $imageAlt));
    compileCell("td", $cell);
    
    // chiude la riga della tabella
    echo "    </tr>\n"; 

} // compileRow(...) function end

# FUNZIONI MINORI UTILIZZATE

function compileCell($type, $x) {
    echo "\n      <$type>$x</$type>\n";
    return;
}

function compileYellowCell($type, $x) {
    echo "\n      <$type class=\"yellowCell\">$x</$type>\n";
    return;
}

function prepareFetchHyperlink($ideli, $eli, $closed, $type, $value) {
    if ($eli == "tutte le fiancate") {
        $eli = "";
        $ideli = "";
    }
    $and = htmlentities("&");
    return '<a href="differiti-display.php?ideli=' . $ideli . $and . 'eli=' . $eli . $and . 'chiusi=' . $closed . $and . 'tipo=' . $type . '#caption">' .
	$value . '</a>';
}

function compileImage($file, $alt) {
    return '<img src="' . $file . '" alt="' . $alt . '" />';
}

function prepareUploadHyperlink($chopper, $chopperID, $icon) {
    $and = htmlentities("&"); // per compatibilità con form-editor.php sono passati anche i parametri vuoti
    return '<a href="differiti-editor.php?AP=' . $and . 'PR=' . $and . 'CH='.$and . 'action=INSERT' . $and . 'table=AP' .
        $and . 'eli=' . $chopper . $and . 'ideli=' . $chopperID . '#legend">' . $icon . '</a>';
}

?>
