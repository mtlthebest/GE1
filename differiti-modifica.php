<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Differiti";
$h1Title = "Servizio Tecnico";
$h1HyperLink = "";
$h2Title = "Gestione dei provvedimenti correttivi differiti";
$h2HyperLink = "differiti-home.php#table";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

include("differiti-modifica-formBuilder.inc.php");

echo "<p>Record che verranno interessati dalla modifica:<p>\n";
echo "<ul>\n";
echo "<li>AP: {$_GET['AP']}</li>\n";
echo "<li>PR: {$_GET['PR']}</li>\n";
echo "<li>CH: {$_GET['CH']}</li>\n";
echo "</ul>\n";

buildForm(determineFormButtons($_GET['AP'], $_GET['PR'], $_GET['CH']));

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
