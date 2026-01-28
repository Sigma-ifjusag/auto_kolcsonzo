<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 0) {
    header("Location: logout.php");
    exit();
}

$userid = $_SESSION['userid'];
$uzenet = "";

/* =========================
   AUTÓ HOZZÁADÁS
========================= */
if (isset($_POST['add_car'])) {

    $rendszam   = $_POST['rendszam'];
    $tipus      = $_POST['tipus'];
    $uzemanyag  = $_POST['uzemanyag'];
    $marka      = $_POST['marka'];
    $modell     = $_POST['modell'];
    $kivitel    = $_POST['kivitel'];
    $sz_szem    = (int)$_POST['sz_szem'];
    $suly       = (int)$_POST['suly'];
    $ajtok      = (int)$_POST['ajtokszama'];
    $ar         = (int)$_POST['ar'];
    $loero      = (int)$_POST['loero'];
    $nyomatek   = (int)$_POST['nyomatek'];
    $selejt     = $_POST['selejt'];

    $stmt = $conn->prepare("INSERT INTO items 
        (`R/U`, tipus, uzemanyag, marka, modell, kivitel, sz_szem, suly, ajtokszama, `ar/nap`, loero, nyomatek, selejt, UserID) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssiiiiissi",
        $rendszam, $tipus, $uzemanyag, $marka, $modell, $kivitel,
        $sz_szem, $suly, $ajtok, $ar, $loero, $nyomatek, $selejt, $userid
    );

    $stmt->execute();
    $uzenet = "Autó sikeresen hozzáadva!";
}

/* =========================
   AUTÓ MÓDOSÍTÁS
========================= */
if (isset($_POST['edit_car'])) {

    $carid    = (int)$_POST['carid'];
    $marka    = $_POST['marka'];
    $modell   = $_POST['modell'];
    $ar       = (int)$_POST['ar'];
    $loero    = (int)$_POST['loero'];
    $nyomatek = (int)$_POST['nyomatek'];
    $selejt   = $_POST['selejt'];

    $stmt = $conn->prepare("UPDATE items 
        SET marka=?, modell=?, `ar/nap`=?, loero=?, nyomatek=?, selejt=? 
        WHERE ItemsID=? AND UserID=?");

    $stmt->bind_param("ssiiisii", $marka, $modell, $ar, $loero, $nyomatek, $selejt, $carid, $userid);
    $stmt->execute();

    $uzenet = "Autó sikeresen módosítva!";
}

/* =========================
   SAJÁT AUTÓK LEKÉRÉSE
========================= */
$stmt = $conn->prepare("SELECT * FROM items WHERE UserID=?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$cars = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Saját autóim</title>
<style>
body { font-family: Arial; background:#f4f4f4; padding:40px; }
h1 { color:#ff8102; }
form { background:#fff; padding:15px; margin-bottom:20px; border-radius:8px; }
input, select { padding:8px; margin:5px 0; width:100%; }
button { padding:10px; background:#2b2b2b; color:white; border:none; cursor:pointer; }
button:hover { background:#ff8102; }
.car-box { background:#fff; padding:15px; margin-bottom:15px; border-radius:8px; }
.success { color:green; font-weight:bold; }
</style>
</head>
<body>

<h1>autó hozzáadása</h1>

<?php if ($uzenet): ?>
    <p class="success"><?= $uzenet ?></p>
<?php endif; ?>

<!-- ================== ÚJ AUTÓ ================== -->
<form method="POST">
    <h2>Új autó hozzáadása</h2>

    <input type="text" name="rendszam" placeholder="Rendszám" maxlength="7" required>
    <input type="text" name="marka" placeholder="Márka" required>
    <input type="text" name="modell" placeholder="Modell" required>

    <select name="tipus" required>
        <option value="szemelygepauto">Személygépkocsi</option>
        <option value="haszonauto">Haszonautó</option>
        <option value="munkagep">Munkagép</option>
        <option value="motorkerekpar">Motor</option>
        <option value="egyeb">Egyéb</option>
    </select>

    <select name="uzemanyag" required>
        <option value="Benzin">Benzin</option>
        <option value="Dízel">Dízel</option>
        <option value="Benzingaz">Benzin + Gáz</option>
        <option value="Hybrid">Hibrid</option>
        <option value="Elektromos">Elektromos</option>
    </select>

    <select name="kivitel" required>
        <option value="Sedan">Sedan</option>
        <option value="Hatchback">Hatchback</option>
        <option value="Kombi">Kombi</option>
        <option value="SUV">SUV</option>
        <option value="Terepjáró">Terepjáró</option>
        <option value="Pickup">Pickup</option>
        <option value="Coupe">Coupe</option>
        <option value="Cabrio">Cabrio</option>
        <option value="Van">Van</option>
        <option value="Sport">Sport</option>
        <option value="Buggy">Buggy</option>
        <option value="Motor">Motor</option>
        <option value="Egyéb">Egyéb</option>
    </select>

    <input type="number" name="sz_szem" placeholder="Szállítható személyek száma" min="1" required>
    <input type="number" name="suly" placeholder="Súly (kg)" min="1" required>
    <input type="number" name="ajtokszama" placeholder="Ajtók száma" min="1" required>
    <input type="number" name="ar" placeholder="Ár / nap (Ft)" min="0" required>
    <input type="number" name="loero" placeholder="Lóerő" min="1" required>
    <input type="number" name="nyomatek" placeholder="Nyomaték (Nm)" min="1" required>

    <select name="selejt" required>
        <option value="nem">Nem selejt</option>
        <option value="igen">Selejt</option>
    </select>

    <button type="submit" name="add_car">Autó hozzáadása</button>
</form>

<!-- ================== AUTÓK LISTÁJA ================== -->
<h2>jármúim</h2>

<?php while ($car = $cars->fetch_assoc()): ?>
    <div class="car-box">
        <strong>
            <?= htmlspecialchars($car['marka']) ?> <?= htmlspecialchars($car['modell']) ?>
        </strong>
        (<?= htmlspecialchars($car['R/U']) ?> | <?= $car['ar/nap'] ?> Ft/nap)

        <form method="POST">
            <input type="hidden" name="carid" value="<?= $car['ItemsID'] ?>">

            <input type="text" name="marka" value="<?= htmlspecialchars($car['marka']) ?>" required>
            <input type="text" name="modell" value="<?= htmlspecialchars($car['modell']) ?>" required>
            <input type="number" name="ar" value="<?= $car['ar/nap'] ?>" required>
            <input type="number" name="loero" value="<?= $car['loero'] ?>" required>
            <input type="number" name="nyomatek" value="<?= $car['nyomatek'] ?>" required>

            <select name="selejt">
                <option value="nem" <?= $car['selejt'] == 'nem' ? 'selected' : '' ?>>Nem selejt</option>
                <option value="igen" <?= $car['selejt'] == 'igen' ? 'selected' : '' ?>>Selejt</option>
            </select>

            <button type="submit" name="edit_car">Mentés</button>
        </form>
    </div>
<?php endwhile; ?>

</body>
</html>
