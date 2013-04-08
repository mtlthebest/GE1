<?php
// chiude la connessione al database MySQL nel caso fosse ancora aperta e lo notifica
if(isset($cxn)) {
	if(is_resource($cxn)) {
		mysqli_close($cxn);
		echo "<p>Attenzione: connessione al database MySQL chiusa in modo automatico, controllare il codice .php!</p>\n";
	}
	unset($cxn);
}
echo "</body>\n";
echo "</html>";
?>
