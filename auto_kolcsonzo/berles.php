<?php
session_start();
include 'config.php';
require_once 'mail.php'; // vagy a pontos elérési út

if (!isset($_SESSION['userid'])) {
    header("Location: bejelentkezes");
    exit();
}
if (!isset($_GET['id'])) {
    die("Nincs kiválasztott autó. <a href='kezdolap'>Vissza a főoldalra</a>");
}

$itemsID = intval($_GET['id']);
$userID = $_SESSION['userid'];

// 3. Autó adatainak lekérése (csak ha nem selejt)
$stmt = $conn->prepare("SELECT * FROM items WHERE ItemsID = ? AND selejt = 'nem'");
$stmt->bind_param("i", $itemsID);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    die("Az autó nem található vagy selejtezve lett.");
}

// Felhasználó email címének és nevének lekérése - JAVÍTVA a te adatbázisodhoz!
$stmt_user = $conn->prepare("SELECT email, name FROM users WHERE UserID = ?");
$stmt_user->bind_param("i", $userID);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user = $user_result->fetch_assoc();

// 4. Bérlés feldolgozása
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['berles_inditasa'])) {
    $mikortol = $_POST['mikortol'];
    $meddig = $_POST['meddig'];

    // Újra ellenőrizzük, hogy időközben nem foglalták-e le
    if ($car['kiadott'] == 'igen') {
        $hiba = "Sajnos az autó már foglalt.";
    } elseif (strtotime($meddig) <= strtotime($mikortol)) {
        $hiba = "A leadás dátumának későbbinek kell lennie az elvitelénél!";
    } else {
        // Tranzakció kezelés
        $conn->begin_transaction();
        
        try {
            // Mentés a foglalas táblába
            $stmt_ins = $conn->prepare("INSERT INTO foglalas (UserID, ItemsID, mikortol, meddig, elvitte) VALUES (?, ?, ?, ?, 'nem')");
            $stmt_ins->bind_param("iiss", $userID, $itemsID, $mikortol, $meddig);
            $stmt_ins->execute();
            
            // Frissítjük az autó állapotát kiadottra
            $stmt_upd = $conn->prepare("UPDATE items SET kiadott = 'igen' WHERE ItemsID = ?");
            $stmt_upd->bind_param("i", $itemsID);
            $stmt_upd->execute();
            
            // Napok számának kiszámítása
            $date1 = new DateTime($mikortol);
            $date2 = new DateTime($meddig);
            $napok = $date1->diff($date2)->days;
            $vegosszeg = $napok * $car['ar/nap'];
            
            // EMAIL KÜLDÉS - JAVÍTVA a helyes név mezővel!
            $email_sikeres = sendRentalConfirmation(
                $user['email'],
                $user['name'], // Itt a 'name' mezőt használjuk, ami a teljes nevet tartalmazza
                $car,
                $mikortol,
                $meddig,
                $napok,
                $vegosszeg
            );
            
            if (!$email_sikeres) {
                error_log("Email küldési hiba a bérléshez: " . $user['email']);
            }
            
            $conn->commit();
            
            $siker = "Sikeres bérlés! Az autó lefoglalva: $mikortol - $meddig";
            if ($email_sikeres) {
                $siker .= "<br> Foglalási visszaigazolást küldtünk az e-mail címedre!";
            } else {
                $siker .= "<br> A visszaigazoló email küldése nem sikerült, de a foglalás érvényes!";
            }
            $car['kiadott'] = 'igen'; // Hogy az űrlap eltűnjön az oldalon
            
        } catch (Exception $e) {
            $conn->rollback();
            $hiba = "Hiba történt a mentés során: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bérlés: <?= htmlspecialchars($car['marka'] . " " . $car['modell']) ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; justify-content: center; padding: 40px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 500px; width: 100%; }
        .car-info { border-bottom: 3px solid #ff8102; margin-bottom: 20px; padding-bottom: 15px; }
        .price-box { background: #fff8f0; border: 1px solid #ff8102; padding: 15px; border-radius: 8px; margin-top: 15px; }
        .btn { background: #ff8102; color: white; border: none; padding: 12px; cursor: pointer; width: 100%; font-size: 18px; border-radius: 6px; transition: 0.3s; }
        .btn:hover { background: #e67300; }
        .msg { padding: 15px; margin-bottom: 20px; border-radius: 6px; font-weight: bold; }
        .siker { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .hiba { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        input[type="date"] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .email-icon { margin-right: 5px; }
        #f-v-btn{ font-weight: bold; background: #ff8102; color: white; border: none; padding: 5px; text-decoration: none; cursor: pointer; width: 100%;border-radius: 6px; transition: 0.3s; }
    </style>
</head>
<body>

<div class="container">
    <div class="car-info">
        <h2><?= htmlspecialchars($car['marka'] . " " . $car['modell']) ?></h2>
        <p>Típus: <?= ucfirst($car['tipus']) ?> | Üzemanyag: <?= $car['uzemanyag'] ?></p>
        <p>Napi ár: <strong><?= number_format($car['ar/nap'], 0, ',', ' ') ?> Ft</strong></p>
    </div>

    <?php if (isset($siker)): ?>
        <div class='msg siker'><?= $siker ?></div>
        <p><a id="f-v-btn" href="kezdolap">Vissza a böngészéshez</a> | <a id="f-v-btn" href="foglalasok">Saját foglalásaim</a></p>
    <?php elseif (isset($hiba)): ?>
        <div class='msg hiba'><?= $hiba ?></div>
    <?php endif; ?>

    <?php if ($car['kiadott'] == 'nem' && !isset($siker)): ?>
        <form method="POST">
            <label for="mikortol">Bérlés kezdete:</label>
            <input type="date" name="mikortol" id="mikortol" required min="<?= date('Y-m-d') ?>" onchange="szamolAr()">
            <br><br>
            <label for="meddig">Visszahozatal:</label>
            <input type="date" name="meddig" id="meddig" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" onchange="szamolAr()">

            <div class="price-box">
                Időtartam: <span id="napok">0</span> nap<br>
                <strong>Végösszeg: <span id="total-ar">0</span> Ft</strong>
            </div>
            <br>
            <button type="submit" name="berles_inditasa" class="btn">Foglalás véglegesítése</button>
        </form>
    <?php elseif ($car['kiadott'] == 'igen' && !isset($siker)): ?>
        <div class="msg hiba">Sajnáljuk, ezt az autót már elvitték.</div>
        <a id="f-v-btn" href="kezdolap">Keress másik autót</a>
    <?php endif; ?>
</div>

<script>
function szamolAr() {
    const mikorVal = document.getElementById('mikortol').value;
    const meddigVal = document.getElementById('meddig').value;
    const napiAr = <?= $car['ar/nap'] ?>;
    
    if (mikorVal && meddigVal) {
        const mikor = new Date(mikorVal);
        const meddig = new Date(meddigVal);
        
        if (meddig > mikor) {
            const kulonbsegIdo = meddig.getTime() - mikor.getTime();
            const napok = Math.ceil(kulonbsegIdo / (1000 * 3600 * 24));
            
            document.getElementById('napok').innerText = napok;
            document.getElementById('total-ar').innerText = (napok * napiAr).toLocaleString();
        } else {
            document.getElementById('napok').innerText = "0";
            document.getElementById('total-ar').innerText = "0";
        }
    }
}
</script>
</body>
</html>