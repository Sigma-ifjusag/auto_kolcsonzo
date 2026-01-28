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

    $rendszam = $_POST['rendszam'];
    $tipus = $_POST['tipus'];
    $uzemanyag = $_POST['uzemanyag'];
    $marka = $_POST['marka'];
    $modell = $_POST['modell'];

    $stmt = $conn->prepare("INSERT INTO items (`R/U`, tipus, uzemanyag, marka, modell, UserID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $rendszam, $tipus, $uzemanyag, $marka, $modell, $userid);
    $stmt->execute();

    $uzenet = "Autó sikeresen hozzáadva!";
}

/* =========================
   AUTÓ MÓDOSÍTÁS
========================= */
if (isset($_POST['edit_car'])) {

    $carid = $_POST['carid'];
    $marka = $_POST['marka'];
    $modell = $_POST['modell'];

    $stmt = $conn->prepare("UPDATE items SET marka=?, modell=? WHERE ItemsID=? AND UserID=?");
    $stmt->bind_param("ssii", $marka, $modell, $carid, $userid);
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

<h1>Saját autóim 🚗</h1>

<?php if ($uzenet): ?>
    <p class="success"><?= $uzenet ?></p>
<?php endif; ?>

<!-- ================== ÚJ AUTÓ ================== -->
<form method="POST">
    <h2>Új autó hozzáadása</h2>
    <input type="text" name="rendszam" placeholder="Rendszám" required>
    <input type="text" name="marka" placeholder="Márka" required>
    <input type="text" name="modell" placeholder="Modell" required>

    <select name="tipus">
        <option value="szemelygepauto">Személygépkocsi</option>
        <option value="haszonauto">Haszonautó</option>
        <option value="munkagep">Munkagép</option>
        <option value="motorkerekpar">Motor</option>
        <option value="egyeb">Egyéb</option>
    </select>

    <select name="uzemanyag">
        <option value="Benzin">Benzin</option>
        <option value="Dízel">Dízel</option>
        <option value="Hybrid">Hybrid</option>
        <option value="Elektromos">Elektromos</option>
    </select>

    <button type="submit" name="add_car">Autó hozzáadása</button>
</form>

<!-- ================== SAJÁT AUTÓK ================== -->
<h2>Autóim listája</h2>

<?php while ($car = $cars->fetch_assoc()): ?>
    <div class="car-box">
        <strong><?= htmlspecialchars($car['marka']) ?> <?= htmlspecialchars($car['modell']) ?></strong>
        (<?= htmlspecialchars($car['R/U']) ?>)

        <form method="POST">
            <input type="hidden" name="carid" value="<?= $car['ItemsID'] ?>">
            <input type="text" name="marka" value="<?= htmlspecialchars($car['marka']) ?>" required>
            <input type="text" name="modell" value="<?= htmlspecialchars($car['modell']) ?>" required>
            <button type="submit" name="edit_car">Mentés</button>
        </form>
    </div>
<?php endwhile; ?>

</body>
</html>
