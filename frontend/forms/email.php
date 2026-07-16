<?php

$to = 'destinataire@exemple.com';
$subject = '=?UTF-8?B?' . base64_encode('Email en HTML') . '?='; 
$from = 'expediteur@votre-site.com';


$message = "
<html>
<head>
    <title>Email de test</title>
</head>
<body>
    <h1>Bonjour !</h1>
    <p>Ceci est un email <strong>HTML</strong> envoyé depuis PHP.</p>
    <ul>
        <li>Item 1</li>
        <li>Item 2</li>
        <li>Item 3</li>
    </ul>
    <p>Cordialement,<br>L'équipe</p>
</body>
</html>
";


$headers = "From: $from\r\n";
$headers .= "Reply-To: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: 8bit\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";


if (mail($to, $subject, $message, $headers)) {
    echo "✅ Email HTML envoyé avec succès !";
} else {
    echo "❌ Erreur lors de l'envoi.";
}
?>