<?php
session_start();
include 'config.php';

// Csak adminok érhetik el (jogosultsag = 1)
if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 1) {
    header("Location: kijelentkezes");
    exit();
}

// Összes bérlés lekérése a felhasználók és autók adataival együtt
$sql = "SELECT foglalas.*, 
               users.username, 
               users.name AS user_name, 
               users.email,
               items.marka, 
               items.modell, 
               items.`R/U` AS rendszam, 
               items.`ar/nap`
        FROM foglalas 
        JOIN users ON foglalas.UserID = users.UserID 
        JOIN items ON foglalas.ItemsID = items.ItemsID 
        ORDER BY foglalas.mikortol DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Összes bérlés</title>
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
        .user-info {
            background: #f8f9fa;
            padding: 12px 18px;
            border-radius: 8px;
            margin: 10px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            border: 1px solid #e9ecef;
        }
        .user-info-item {
            display: flex;
            flex-direction: column;
        }
        .user-info-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .user-info-value {
            font-size: 15px;
            font-weight: bold;
            color: var(--dark);
        }
        .ar-box {
            background: #fff8f0;
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-block;
            border: 1px solid var(--orange);
            margin-top: 10px;
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
        .stats {
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--gray-border);
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .stat-item {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }
        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: bold;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: var(--orange);
        }
    </style>
</head>
<body>
    <a href="admin-felulet" class="back-btn">Vissza az admin felületre</a>
    
    <h1>Összes bérlés a rendszerben</h1>
    
    <?php
    // Statisztikák számítása
    $total_berles = 0;
    $total_bevetel = 0;
    $unique_users = [];
    $unique_cars = [];
    
    if ($result && $result->num_rows > 0):
        $result->data_seek(0); // Visszaállítjuk a pointert a statisztikák után
        while ($stat = $result->fetch_assoc()):
            $total_berles++;
            $mikortol = new DateTime($stat['mikortol']);
            $meddig = new DateTime($stat['meddig']);
            $napok = $mikortol->diff($meddig)->days;
            $total_bevetel += $napok * $stat['ar/nap'];
            $unique_users[$stat['UserID']] = true;
            $unique_cars[$stat['ItemsID']] = true;
        endwhile;
        $result->data_seek(0); // Újra visszaállítjuk a listázáshoz
    ?>
    
    <div class="stats">
        <div class="stat-item">
            <span class="stat-label">Összes bérlés:</span>
            <span class="stat-value"><?= $total_berles ?></span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Összes bevétel:</span>
            <span class="stat-value"><?= number_format($total_bevetel, 0, ',', ' ') ?> Ft</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Bérlők száma:</span>
            <span class="stat-value"><?= count($unique_users) ?></span>
        </div>
    </div>
    
    <div class="foglalas-container">
        <?php while ($foglalas = $result->fetch_assoc()): 
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
                    <?= htmlspecialchars($foglalas['rendszam']) ?>
                </span>
            </div>
            
            <div class="user-info">
                <div class="user-info-item">
                    <span class="user-info-label">Bérlő neve</span>
                    <span class="user-info-value"><?= htmlspecialchars($foglalas['user_name']) ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Felhasználónév</span>
                    <span class="user-info-value"><?= htmlspecialchars($foglalas['username']) ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Email cím</span>
                    <span class="user-info-value"><?= htmlspecialchars($foglalas['email']) ?></span>
                </div>
                <div class="user-info-item">
                    <span class="user-info-label">Felhasználó ID</span>
                    <span class="user-info-value">#<?= $foglalas['UserID'] ?></span>
                </div>
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
    </div>
    
    <?php else: ?>
        <div class="no-data">
            <p style="font-size: 24px; margin-bottom: 10px;"></p>
            <h2>Még nincsenek bérlések a rendszerben</h2>
            <p>Az első bérlés megjelenik itt, amint valaki foglal egy autót.</p>
            <a href="admin-felulet" style="display: inline-block; margin-top: 20px; padding: 12px 24px; background-color: var(--orange); color: white; text-decoration: none; border-radius: 6px; font-weight: bold;">Vissza az admin felületre</a>
        </div>
    <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>