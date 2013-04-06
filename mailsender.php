<?php

// bool mail ( string $to , string $subject , string $message [, string $additional_headers [, string $additional_parameters ]] )

$to = "mtl@libero.it";
$subject = "Test di invio e-mail tramite PHP - uso di sSMTP";
$message = "Hello PHP World!";
$headers = 'From: PHP automatic sender' . "\r\n";

mail($to, $subject, $message, $headers);

?>
