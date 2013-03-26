<?php

### VARIABILI PER LA PERSONALIZZAZIONE DELLA PAGINA WEB ###

$title = "Portale";
$h1Title = "GRUPELICOT UNO";
$h1HyperLink = "";
$h2Title = "Portale web del Primo Gruppo Elicotteri";
$h2HyperLink = "";

include("common-open-page.inc.php");

### CODICE PRINCIPALE DELLA PAGINA QUI SOTTO ###

$menuGE1 = <<<MENU

  <ol>
    <li>
      <h3>Segreteria Comando</h3>
    
      <ul>
        <li>
          <p><a href="personale-home.php">Elenco del personale
          militare</a></p>
        </li>
      </ul>
    </li>

    <li>
      <h3>Servizio Tecnico</h3>

      <ul>
        <li>
          <p><a href="differiti-home.php">Gestione provvedimenti
          correttivi differiti</a></p>
        </li>
      </ul>
    </li>
  </ol>

MENU;
echo $menuGE1;

### CODICE PRINCIPALE DELLA PAGINA QUI SOPRA ###

include("common-close-page.inc.php");
?>
