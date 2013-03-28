<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Portale";
$h1Title = "GRUPELICOT UNO";
$h1HyperLink = "";
$h2Title = "Portale web del Primo Gruppo Elicotteri";
$h2HyperLink = "";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

include("differiti-modifica-formBuilder.inc.php");

buildForm(determineFormButtons('13', '' ,'6'));

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
