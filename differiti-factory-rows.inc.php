<?php

# FUNZIONE rowFactory

function rowFactory($eli, $ideli, $result) {
    
    // Esplodo la riga, ottenendo le una variabile per ogni campo della riga
    while ($row = mysqli_fetch_assoc($result)) {
        extract($row);
        if ($elicottero == $eli) { // $elicottero è uno dei campi del result-set
            break; // mantiene le ultime variabili trovate, relative all'elicottero interessato
        }
    }
    
    // Compilazione casella #0: Numero di fiancata -> GRASSETTO, FORMATTAZIONE CONDIZIONALE
    if ($eli == "Tutte le fiancate")
        echo "";
    else if ($totaleancoraaperti == NULL)
        compileCell("th", "$eli"); // mostra cella con il solo numero di fiancata
    else {
        compileYellowCell("td", $eli);
    }
    
    // Compilazione casella #1: Inconvenienti -> LINK
    if ($inconvenienti == NULL)
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "1"; // idem
        $cell   = prepareFetchHyperlink($eli, $chiusi, $tipo, $inconvenienti);
        compileCell("td", $cell);
    }
    
    // Compilazione casella #2: Ispezioni -> LINK
    if ($ispezioni == NULL)
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "2"; // idem
        $cell   = prepareFetchHyperlink($eli, $chiusi, $tipo, $ispezioni);
        compileCell("td", $cell);
    }
    
    // Compilazione casella #3: Sostituzioni -> LINK
    if ($sostituzioni == NULL)
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "3"; // idem
        $cell   = prepareFetchHyperlink($eli, $chiusi, $tipo, $sostituzioni);
        compileCell("td", $cell);
    }
    // Compilazione casella #4: Altri -> LINK
    if ($altri == NULL)
        compileCell("td", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = "4"; // idem
        $cell   = prepareFetchHyperlink($eli, $chiusi, $tipo, $altri);
        compileCell("td", $cell);
    }
    
    // Compilazione casella #5: Totale ancora aperti -> LINK, FORMATTAZIONE CONDIZIONALE, TABLE HEADER (grassetto)
    if ($totaleancoraaperti == NULL)
        compileCell("th", ""); // mostra cella vuota
    else {
        $chiusi = 0; // variabile passata nell'URL, specifica per ogni colonna della tabella
        $tipo   = ""; // idem
        $cell   = prepareFetchHyperlink($eli, $chiusi, $tipo, $totaleancoraaperti);
        if ($eli == "Tutte le fiancate")
            compileCell("td", $cell);
        else
            compileYellowCell("th", $cell);
    }
    
    // Compilazione casella #6: casella lente ricerca tutti i differiti (anche chiusi) -> ICONA, LINK
    $chiusi    = 1; // variabile passata nell'URL, specifica per ogni colonna della tabella
    $tipo      = ""; // idem
    $imageFile = "search_lens_mini.png";
    $imageAlt  = "consulta";
    $cell      = prepareFetchHyperlink($eli, $chiusi, $tipo, compileImage($imageFile, $imageAlt));
    compileCell("td", $cell);
    
    // Compilazione casella #7: casella freccia caricamento differito -> ICONA, LINK, NON DISPONIBILE PER TUTTE LE FIANCATE
    if ($eli == "Tutte le fiancate")
        echo "";
    else if ($eli == "")
        compileCell("td", ""); // mostra cella vuota nel caso la riga sia relativa a tutte le fiancate
    else {
        $imageFile = "upload_icon_mini.png";
        $imageAlt  = "carica";
        $cell      = prepareUploadHyperlink($eli, $ideli, compileImage($imageFile, $imageAlt));
        compileCell("td", $cell);
    }
    echo "    </tr>\n"; // chiude la riga della tabella
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

function prepareFetchHyperlink($eli, $closed, $type, $value) {
    if ($eli == "Tutte le fiancate")
        $eli = "";
    $and = htmlentities("&");
    return '<a href="differiti-display.php?eli=' . $eli . $and . 'chiusi=' . $closed . $and . 'tipo=' . $type . '">' . $value . '</a>';
}

function compileImage($file, $alt) {
    return '<img src="' . $file . '" alt="' . $alt . '" />';
}

function prepareUploadHyperlink($chopper, $chopperID, $icon) {
    $and = htmlentities("&");
    return '<a href="differiti-form-upload.php?eli=' . $chopper . $and . 'ideli=' . $chopperID . '">' . $icon . '</a>';
}

?>
