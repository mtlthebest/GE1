<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

stampaIntroduzionePaginaWeb();

// adminMode = $_POST['...']; // ancora da sviluppare sistema per determinare se l'utente è admin o in sola lettura
$adminMode = TRUE;

// connessione al database e ottenimento result-set
$query = "SELECT * FROM view_differiti_menu";
$cxn = connectToDifferitiDatabase();
$set = sendQuery($cxn, $query);
$caption = "<a id=\"table\"></a>Punto di situazione - Provvedimenti correttivi differiti";
if($adminMode)
	$caption .= " / modalità amministratore";

// include funzione rowFactory(...)
include("differiti-factory-rows.inc.php");

// sequenza programma principale
initTable($caption);
scriviHeaderPrincipale();
scriviASW($set);
scriviEarlyWarning($set);
scriviUtility($set);
scriviUtility16($set);
scriviASH($set);
scriviTutte($set);
endTable();

function scriviHeaderPrincipale() {
	$tableHeader = <<<TABLEHEADER
    <tr>
      <th colspan="2" rowspan="2">AgustaWestland EH-101</th>

      <th rowspan="2" style="color: red">Apri differito</th>

      <th colspan="5">Differiti ancora aperti</th>

      <th rowspan="2">Tutti i differiti</th>
    </tr>

    <tr>
      <td class="larghezzaFissa">Inconvenienti</td>

      <td class="larghezzaFissa">Ispezioni</td>

      <td class="larghezzaFissa">Sostituzioni</td>

      <td class="larghezzaFissa">Altri</td>

      <th>Totale ancora aperti</th>
    </tr>

TABLEHEADER;
	echo $tableHeader;
}

function scriviASW($R) {
	scriviCella("ASW-ASuW", 2);
	rowFactory("2-01", "1", $R);
	initRow();
	rowFactory("2-08", "2", $R);
}

function scriviEarlyWarning($R) {
	scriviCella("HEW", 2);
	rowFactory("2-09", "3", $R);
	initRow();
	rowFactory("2-12", "4", $R);
}

function scriviUtility($R) {
	scriviCella("Utility", 3);
	rowFactory("2-13", "5", $R);
	initRow();
	rowFactory("2-14", "6", $R);
	initRow();
	rowFactory("2-15", "7", $R);
}

function scriviUtility16($R) {
	scriviCella("Utility/ASH*", 1);
	rowFactory("2-16", "8", $R);
}

function scriviASH($R) {
	scriviCella("ASH", 4);
	rowFactory("2-18", "9", $R);
	initRow();
	rowFactory("2-19", "10", $R);
	initRow();
	rowFactory("2-20", "11", $R);
	initRow();
	rowFactory("2-21", "12", $R);
}

function scriviTutte($R) {
	scriviCellaTutte();
	rowFactory("tutte le fiancate", "", $R);
}

function scriviCella($letter, $rowspan) {
	echo "\n    <tr>\n";
	echo "      <th rowspan=\"$rowspan\">$letter</th>\n";
}

function scriviCellaTutte() {
	echo "\n    <tr>\n";
	echo "      <th colspan=\"2\">Tutte le fiancate</th>\n";
}

function initTable($capt) {
	echo "  <table border=\"1\" summary=\"Tabella di riepilogo differiti\">\n";
	echo "    <caption>\n";
        echo "      $capt";
	echo "\n    </caption>\n";
}

function endTable() {
	echo "  </table>\n";
}

function initRow() {
	echo "\n    <tr>";
}

function stampaIntroduzionePaginaWeb() {
	$intro = <<<INTRO
  <div class="boxed">
  <p style="text-align: right; color: #940000; font-size: 75%"><i>Estratto dalla Norma Tecnica n° 09</i></p>
  <p>Nella seconda parte del libretto tecnico di volo devono essere
  registrati:</p>

  <ul>
    <li>gli inconvenienti non prontamente risolvibili e la cui
    eliminazione non limiti l'idoneità al volo;</li>

    <li>le voci di ispezioni eventualmente differite (nei limiti
    delle tolleranze e con le modalità previste);</li>

    <li>i componenti soggetti a LI la cui sostituzione è stata
    differita (nei limiti delle tolleranze previste) e la scadenza
    della tolleranza riportata in ore TOT/DUR dell'aeromobile, o la
    data di rimozione.</li>
  </ul>

  <p>Per ogni voce inserita nei differiti devono essere
  riportate:</p>

  <ul>
    <li>la data in cui si è verificato l'inconveniente;</li>

    <li>la firma di chi ha autorizzato il differimento.</li>
  </ul>

  <p>Lo specialista che provvede ad eliminare un inconveniente
  riportato nella parte seconda del libretto tecnico di volo deve
  annotare:</p>

  <ul>
    <li>il provvedimento correttivo adottato;</li>

    <li>la durata del lavoro in ore/uomo;</li>

    <li>la data di eliminazione;</li>

    <li>la propria firma.</li>
  </ul>
  </div>


INTRO;
	echo $intro;
}

// Chiusura della connessione al database MySQL
mysqli_close($cxn);

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
