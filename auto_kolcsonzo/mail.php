<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/auto_kolcsonzo/src/Exception.php';
require 'C:/xampp/htdocs/auto_kolcsonzo/src/PHPMailer.php';
require 'C:/xampp/htdocs/auto_kolcsonzo/src/SMTP.php';

/**
 * Bérlés visszaigazoló email küldése
 */
function sendRentalConfirmation($cimzett_email, $nev, $car, $mikortol, $meddig, $napok, $vegosszeg) {
    $mail = new PHPMailer(true);
    
    try {
        // SZERVER BEÁLLÍTÁSOK
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rideonautokolcsonzo@gmail.com';
        $mail->Password   = 'qnpf ddhv jkuk yynx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        $mail->SMTPDebug  = 0; // Kikapcsoljuk a debug-ot élesben

        // CÍMZETTEK
        $mail->setFrom('rideonautokolcsonzo@gmail.com', 'RideOn Autókölcsönző');
        $mail->addAddress($cimzett_email, $nev);
        $mail->addReplyTo('rideonautokolcsonzo@gmail.com', 'Ügyfélszolgálat');

        // TARTALOM
        $mail->isHTML(true);
        $mail->Subject = 'Foglalás visszaigazolás - ' . $car['marka'] . ' ' . $car['modell'];
        
        // Formázott dátumok
        $mikortol_formatted = date('Y. m. d.', strtotime($mikortol));
        $meddig_formatted = date('Y. m. d.', strtotime($meddig));
        
        // HTML email sablon
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Foglalás visszaigazolás</title>
        </head>
        <body style="margin:0; padding:0; font-family: \'Segoe UI\', Arial, sans-serif; background-color:#f4f4f4;">
            <div style="max-width:600px; margin:0 auto; background-color:#ffffff; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                <!-- Fejléc -->
                <div style="background: linear-gradient(135deg, #ff8102 0%, #ff9f4b 100%); padding:30px; border-radius:8px 8px 0 0; text-align:center;">
                    <h1 style="color:#ffffff; margin:0; font-size:28px;">✓ Foglalás visszaigazolva!</h1>
                    <p style="color:#ffffff; margin:10px 0 0 0; font-size:16px;">Köszönjük, hogy a RideOn-t választotta!</p>
                </div>
                
                <!-- Tartalom -->
                <div style="padding:30px;">
                    <p style="font-size:18px; color:#333; margin-bottom:20px;">Kedves <strong>' . htmlspecialchars($nev) . '</strong>!</p>
                    
                    <p style="color:#555; line-height:1.6;">Örömmel értesítjük, hogy sikeresen lefoglalta az alábbi autót:</p>
                    
                    <div style="background-color:#fff8f0; border-left:4px solid #ff8102; padding:20px; margin:25px 0; border-radius:4px;">
                        <h2 style="color:#ff8102; margin:0 0 15px 0; font-size:22px;">🚗 ' . htmlspecialchars($car['marka'] . ' ' . $car['modell']) . '</h2>
                        
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="padding:8px 0; color:#666;">Típus:</td>
                                <td style="padding:8px 0; font-weight:bold; color:#333;">' . ucfirst($car['tipus']) . '</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0; color:#666;">Üzemanyag:</td>
                                <td style="padding:8px 0; font-weight:bold; color:#333;">' . $car['uzemanyag'] . '</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0; color:#666;">Bérlés kezdete:</td>
                                <td style="padding:8px 0; font-weight:bold; color:#333;">' . $mikortol_formatted . '</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0; color:#666;">Visszahozatal:</td>
                                <td style="padding:8px 0; font-weight:bold; color:#333;">' . $meddig_formatted . '</td>
                            </tr>
                            <tr>
                                <td style="padding:8px 0; color:#666;">Időtartam:</td>
                                <td style="padding:8px 0; font-weight:bold; color:#333;">' . $napok . ' nap</td>
                            </tr>
                        </table>
                        
                        <div style="background-color:#fff; padding:15px; border-radius:4px; margin-top:15px; text-align:right;">
                            <p style="margin:0; color:#666;">Napi díj: <strong>' . number_format($car['ar/nap'], 0, ',', ' ') . ' Ft</strong></p>
                            <p style="margin:10px 0 0 0; font-size:24px; color:#ff8102; font-weight:bold;">VÉGÖSSZEG: ' . number_format($vegosszeg, 0, ',', ' ') . ' Ft</p>
                        </div>
                    </div>
                    
                    <div style="background-color:#e7f3fe; border:1px solid #b8daff; padding:15px; border-radius:4px; margin:25px 0;">
                        <p style="margin:0; color:#004085; font-weight:bold;">📌 Fontos tudnivalók:</p>
                        <ul style="margin:10px 0 0 0; color:#004085;">
                            <li style="margin-bottom:5px;">Az autó átvételekor személyazonosító okmány és vezetői engedély szükséges</li>
                            <li style="margin-bottom:5px;">A bérlés kezdetekor kaució fizetendő</li>
                            <li style="margin-bottom:5px;">Késedelmes visszahozatal esetén pótdíj felszámításra kerül</li>
                            <li style="margin-bottom:5px;">Foglalás módosítását vagy lemondását jelezze ügyfélszolgálatunkon</li>
                        </ul>
                    </div>
                    
                    <div style="text-align:center; margin-top:30px;">
                        <a href="http://localhost/auto_kolcsonzo/sajat_foglalasok.php" 
                           style="display:inline-block; background-color:#ff8102; color:#ffffff; padding:12px 30px; 
                                  text-decoration:none; border-radius:4px; font-weight:bold; margin:0 5px;">
                            📋 Saját foglalásaim
                        </a>
                    </div>
                </div>
                
                <!-- Lábléc -->
                <div style="background-color:#f8f9fa; padding:20px; border-radius:0 0 8px 8px; text-align:center; border-top:1px solid #e9ecef;">
                    <p style="color:#6c757d; margin:0 0 10px 0;">© 2024 RideOn Autókölcsönző - Minden jog fenntartva</p>
                    <p style="color:#6c757d; margin:0; font-size:14px;">
                        ️🚩 1011 Budapest, Batthyány tér 5-6. | 📞 +36 1 234 5678 | ✉️ rideonautokolcsonzo@gmail.com
                    </p>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->AltBody = "Kedves $nev!\n\n" .
                         "Sikeresen lefoglalta: {$car['marka']} {$car['modell']}\n" .
                         "Bérlés időpontja: $mikortol_formatted - $meddig_formatted\n" .
                         "Időtartam: $napok nap\n" .
                         "Végösszeg: " . number_format($vegosszeg, 0, ',', ' ') . " Ft\n\n" .
                         "Köszönjük, hogy minket választott!\n" .
                         "RideOn Autókölcsönző";

        $mail->send();
        return true;
        
    } catch (Exception $e) {
        error_log("Email küldési hiba: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Egyszerű teszt email küldése
 */
function sendTestEmail($cimzett_email) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rideonautokolcsonzo@gmail.com';
        $mail->Password   = 'qnpf ddhv jkuk yynx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('rideonautokolcsonzo@gmail.com', 'RideOn Autókölcsönző');
        $mail->addAddress($cimzett_email);
        $mail->isHTML(true);
        $mail->Subject = '📧 Teszt email - RideOn SMTP működik!';
        $mail->Body    = '<h1>Sikeres teszt!</h1><p>Az SMTP beállítások megfelelően működnek.</p>';
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Teszt email hiba: " . $mail->ErrorInfo);
        return false;
    }
}
?>