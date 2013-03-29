<?php
function queryAndCaptionFactory($chopper, $closed, $type, $request)
{
    
    // AGGIUNTA APICI PER CORRETTO FUNZIONAMENTO QUERY SQL
    if ($chopper != "")
        $chopper = "'$chopper'";
   
    // adminMode = $_POST['...']; // ancora da sviluppare sistema per determinare se l'utente è admin o in sola lettura
    $adminMode = TRUE;
    
    // INIZIALIZZAZIONE VARIABILI PER COSTRUZIONE QUERY SQL E CAPTION
    $caption = "";
    $fields  = "";
    $wheres  = "";
    $T       = "";
    if ($type == 1)
        $T = "inconvenienti o avarie";
    else if ($type == 2)
        $T = "ispezioni in proroga";
    else if ($type == 3)
        $T = "sostituzioni in proroga";
    else if ($type == 4)
        $T = "altre segnalazioni";
    else
        $T = "--- errore, controllare il codice ---";
    
    if (($chopper != "") && (!$closed) && ($type != "")) { // CASO 6 (più specifico)
        $caption      = "Differiti ($T) ancora aperti per la fiancata $chopper";
        $fields       = "`inconveniente`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`";
        $where_eli    = "(numerofiancata) = $chopper";
        $where_chiusi = "(firmach) is null";
        $where_tipo   = "(idtipo) = $type";
        $wheres       = "($where_eli) AND ($where_chiusi) AND ($where_tipo)";
    }
    
    else if (($chopper != "") && (!$closed) && ($type == NULL)) { // CASO 5
        $caption      = "Differiti ancora aperti per la fiancata $chopper";
        $fields       = "`inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`";
        $where_eli    = "(numerofiancata) = $chopper";
        $where_chiusi = "(firmach) is null";
        $wheres       = "($where_eli) AND ($where_chiusi)";
    }
    
    else if (($chopper != "") && ($closed) && ($type == NULL)) { // CASO 4
        $caption   = "Tutti i differiti nel database (anche chiusi) per la fiancata $chopper";
        $fields    = "`inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`, `provvedimentocorrettivoadottato` as provvedimento, `durataoreuomo` as `ore/uomo`, `datachiusura` as `data CH`, `firmach` as `firma CH`";
        $where_eli = "(numerofiancata) = $chopper";
        $wheres    = "($where_eli)";
    }
    
    else if (($chopper == NULL) && (!$closed) && ($type != "")) { // CASO 3
        $caption      = "Differiti per tutte le fiancate (tipo: $T, ancora aperti) caricati nel database";
        $fields       = "`numerofiancata` as Elicottero, `inconveniente`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`";
        $where_chiusi = "(firmach) is null";
        $where_tipo   = "(idtipo) = $type";
        $wheres       = "($where_chiusi) AND ($where_tipo)";
    }
    
    else if (($chopper == NULL) && (!$closed) && ($type == NULL)) { // CASO 2
        $caption      = "Elenco di tutti i differiti ancora aperti";
        $fields       = "`numerofiancata` as Elicottero, `inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`";
        $where_chiusi = "(firmach) is null";
        $wheres       = "($where_chiusi)";
    }
    
    else { // CASO 1 (meno specifico)
        $caption = "Elenco di tutti i differiti (anche chiusi) memorizzati nel database";
        $fields  = "`numerofiancata` as Elicottero, `inconveniente`, `tipologia` as `tipo`, `datainconveniente` as `data AP`, `firmaap` as `firma AP`, `note`, `provvedimentocorrettivoadottato` as provvedimento, `durataoreuomo` as `ore/uomo`, `datachiusura` as `data CH`, `firmach` as `firma CH`";
    }
    
    // selezione degli id AP, PR e CH nel caso di amministratore (dati necessari per individuare i record da modificare)
    if($adminMode)
        $fields .= ", `idapertura` as `AP`, `idnote` as `PR`, `idchiusura` as `CH`";

    $query = "SELECT $fields\nFROM view_differiti";
    if ($wheres != "") {
        $query .= "\nWHERE ($wheres)"; // nel caso di restrizioni aggiunge la clausola WHERE
    }
    
    if ($request == "caption")
        return $caption;
    
    else
        return $query;
    
} // end function
?>
