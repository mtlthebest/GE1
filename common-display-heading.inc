<?php
echo "  <p class=\"cr\">".htmlentities("©")." 2013 MtL</p>\n";
$heading = <<<HEADING

  <div style="text-align: center">
    <a href="index.php"><img src="GRUPELICOT%20UNO.jpg" alt=
    "Primo Gruppo Elicotteri" /></a>


HEADING;
echo $heading;
if($h1HyperLink != "") // implementa il collegamento ipertestuale al titolo se richiesto
	$h1Title = '<a href="'.$h1HyperLink.'">'.$h1Title.'</a>';
if($h2HyperLink != "") // implementa il collegamento ipertestuale al sottotitolo se richiesto
	$h2Title = '<a href="'.$h2HyperLink.'">'.$h2Title.'</a>';
echo "    <h1>$h1Title</h1>\n"; // dichiarata nel file .php che richiama questo .inc
echo "\n    <h2>$h2Title</h2>\n"; // dichiarata nel file .php che richiama questo .inc
echo "  </div>\n";
?>
