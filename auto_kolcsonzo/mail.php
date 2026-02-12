<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/auto_kolcsonzo/src/Exception.php';
require 'C:/xampp/htdocs/auto_kolcsonzo/src/PHPMailer.php';
require 'C:/xampp/htdocs/auto_kolcsonzo/src/SMTP.php';

// Itt add meg a cél e-mail címet (jöhet $_POST['email']-ből is)
$cimzett_email = 'pelda@email.com'; 

$mail = new PHPMailer(true);

try {
    // SZERVER BEÁLLÍTÁSOK
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rideonautokolcsonzo@gmail.com';
    $mail->Password   = 'qnpf ddhv jkuk yynx'; // Ügyelj rá, hogy ez bizalmas adat!
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // CÍMZETTEK
    $mail->setFrom('rideonautokolcsonzo@gmail.com', 'RideOn Autókölcsönző');
    $mail->addAddress($cimzett_email); 

    // TARTALOM (Modern HTML Dizájn)
    $mail->isHTML(true);
    $mail->Subject = '';
    
    

    $mail->AltBody = "";

    $mail->send();
    echo 'Sikerült! Az üzenet elküldve.';

} catch (Exception $e) {}
    echo "Hiba történt: {$mail->ErrorInfo}";