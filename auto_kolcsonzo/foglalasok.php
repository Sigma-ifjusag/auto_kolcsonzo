<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid'])) {
    header("Location: bejelentkezes");
    exit();
}

$userid = $_SESSION['userid'];

// Felhasználó adatainak lekérése
$stmt_user = $conn->prepare("SELECT name FROM users WHERE UserID = ?");
$stmt_user->bind_param("i", $userid);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user = $user_result->fetch_assoc();

// Foglalások lekérése az autók adataival együtt
$sql = "SELECT foglalas.*, 
               items.marka, 
               items.modell, 
               items.`R/U`, 
               items.`ar/nap`
        FROM foglalas 
        JOIN items ON foglalas.ItemsID = items.ItemsID 
        WHERE foglalas.UserID = ?
        ORDER BY foglalas.mikortol DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$foglalasok = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalásaim</title>
    <style>
        :root {
            --orange: #ff8102;
            --dark: #2b2b2b;
            --gray-bg: #f9f9f9;
            --gray-border: #ddd;
            --text-dark: #333;
            --text-muted: #666;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: var(--gray-bg);
            color: var(--text-dark);
            margin: 0;
            padding: 30px;
        }
        h1 {
            color: var(--orange);
            margin-bottom: 20px;
            border-bottom: 2px solid var(--orange);
            padding-bottom: 10px;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: var(--orange);
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all .2s ease;
        }
        .back-btn:hover {
            background-color: black;
            transform: translateY(-1px);
        }
        .foglalas-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .foglalas-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 6px solid var(--orange);
            transition: transform 0.2s;
        }
        .foglalas-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .auto-adatok {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .auto-cim {
            font-size: 20px;
            font-weight: bold;
            color: var(--dark);
        }
        .rendszam {
            background: #f0f0f0;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            color: var(--text-muted);
        }
        .datumok {
            display: flex;
            gap: 30px;
            margin: 15px 0;
            flex-wrap: wrap;
        }
        .datum-box {
            display: flex;
            flex-direction: column;
        }
        .datum-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .datum-erteke {
            font-size: 18px;
            font-weight: bold;
            color: var(--dark);
        }
        .ar-box {
            background: #fff8f0;
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-block;
            border: 1px solid var(--orange);
        }
        .napi-ar {
            font-size: 14px;
            color: var(--text-muted);
        }
        .vegosszeg {
            font-size: 20px;
            font-weight: bold;
            color: var(--orange);
            margin-left: 10px;
        }
        .statusz {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--gray-border);
            font-size: 14px;
        }
        .statusz-igen {
            color: #e74c3c;
            font-weight: bold;
        }
        .statusz-nem {
            color: #27ae60;
            font-weight: bold;
        }
        .no-data {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            color: var(--text-muted);
            font-size: 16px;
        }
        .info-note {
            background: #e7f3fe;
            border: 1px solid #b8daff;
            color: #004085;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <a href="kezdolap" class="back-btn">Vissza a főoldalra</a>
    
    <h1>Foglalásaim</h1>
    
    <?php if (isset($user['name'])): ?>
        <div class="info-note">
            <?= htmlspecialchars($user['name']) ?>, itt láthatod az összes eddigi foglalásodat.
        </div>
    <?php endif; ?>
    
    <div class="foglalas-container">
        <?php if ($foglalasok && $foglalasok->num_rows > 0): ?>
            <?php while ($foglalas = $foglalasok->fetch_assoc()): 
                // Napok számának kiszámítása
                $mikortol = new DateTime($foglalas['mikortol']);
                $meddig = new DateTime($foglalas['meddig']);
                $napok = $mikortol->diff($meddig)->days;
                $vegosszeg = $napok * $foglalas['ar/nap'];
                
                // Dátumok formázása
                $mikortol_formatted = date('Y. m. d.', strtotime($foglalas['mikortol']));
                $meddig_formatted = date('Y. m. d.', strtotime($foglalas['meddig']));
                
                // Aktuális dátum
                $ma = new DateTime();
                $foglalas_vege = new DateTime($foglalas['meddig']);
            ?>
            <div class="foglalas-card">
                <div class="auto-adatok">
                    <span class="auto-cim">
                        <?= htmlspecialchars($foglalas['marka'] . ' ' . $foglalas['modell']) ?>
                    </span>
                    <span class="rendszam">
                        <?= htmlspecialchars($foglalas['R/U']) ?>
                    </span>
                </div>
                
                <div class="datumok">
                    <div class="datum-box">
                        <span class="datum-label">Bérlés kezdete</span>
                        <span class="datum-erteke"><?= $mikortol_formatted ?></span>
                    </div>
                    <div class="datum-box">
                        <span class="datum-label">Visszahozatal</span>
                        <span class="datum-erteke"><?= $meddig_formatted ?></span>
                    </div>
                    <div class="datum-box">
                        <span class="datum-label">Időtartam</span>
                        <span class="datum-erteke"><?= $napok ?> nap</span>
                    </div>
                </div>
                
                <div class="ar-box">
                    <span class="napi-ar">Napi díj: <?= number_format($foglalas['ar/nap'], 0, ',', ' ') ?> Ft</span>
                    <span class="vegosszeg">Végösszeg: <?= number_format($vegosszeg, 0, ',', ' ') ?> Ft</span>
                </div>
                
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-data">
                <p style="font-size: 24px; margin-bottom: 10px;"></p>
                <h2>Még nincsenek foglalásaid</h2>
                <p>Böngéssz az autók között és foglald le a számodra legmegfelelőbb járművet!</p>
                <a href="kezdolap" style="display: inline-block; margin-top: 20px; padding: 12px 24px; background-color: var(--orange); color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Autók keresése →</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>