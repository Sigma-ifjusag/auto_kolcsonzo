<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 1) {
    header("Location: logout.php");
    exit();
}

$uzenet = "";

/* =========================
   AUTÓ MÓDOSÍTÁS ADMIN ÁLTAL
========================= */
if (isset($_POST['edit_car_admin'])) {

    $carid     = (int)$_POST['carid'];
    $rendszam  = $_POST['rendszam'];
    $tipus     = $_POST['tipus'];
    $uzemanyag = $_POST['uzemanyag'];
    $marka     = $_POST['marka'];
    $modell    = $_POST['modell'];
    $kivitel   = $_POST['kivitel'];
    $sz_szem   = (int)$_POST['sz_szem'];
    $suly      = (int)$_POST['suly'];
    $ajtok     = (int)$_POST['ajtokszama'];
    $ar        = (int)$_POST['ar'];
    $loero     = (int)$_POST['loero'];
    $nyomatek  = (int)$_POST['nyomatek'];
    $selejt    = $_POST['selejt'];

    $stmt = $conn->prepare("UPDATE items SET
        `R/U`=?,
        tipus=?,
        uzemanyag=?,
        marka=?,
        modell=?,
        kivitel=?,
        sz_szem=?,
        suly=?,
        ajtokszama=?,
        `ar/nap`=?,
        loero=?,
        nyomatek=?,
        selejt=?
        WHERE ItemsID=?");

    $stmt->bind_param(
        "ssssssiiiiissi",
        $rendszam, $tipus, $uzemanyag, $marka, $modell, $kivitel,
        $sz_szem, $suly, $ajtok, $ar, $loero, $nyomatek, $selejt, $carid
    );

    $stmt->execute();
    $uzenet = "Autó adatai frissítve!";
}

/* =========================
   ÖSSZES AUTÓ LEKÉRÉSE
========================= */
$cars = $conn->query("SELECT * FROM items ORDER BY ItemsID DESC");
?>

<script>
function autoLogout() {
    navigator.sendBeacon("logout.php");
}
window.addEventListener("unload", autoLogout);
</script>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Admin – Autók kezelése</title>
<style>
body { font-family: Arial; background:#f4f4f4; padding:40px; }
h1 { color:#ff8102; }
form { background:#fff; padding:15px; margin-bottom:20px; border-radius:8px; }
input, select { padding:6px; margin:4px 0; width:100%; }
button { padding:8px; background:#2b2b2b; color:white; border:none; cursor:pointer; }
button:hover { background:#ff8102; }
.car-box { background:#fff; padding:15px; margin-bottom:20px; border-radius:8px; }
.success { color:green; font-weight:bold; }
small { color:#777; }
</style>
</head>
<body>

<h1>Admin – Összes autó kezelése 🚗</h1>

<?php if ($uzenet): ?>
    <p class="success"><?= $uzenet ?></p>
<?php endif; ?>

<?php while ($car = $cars->fetch_assoc()): ?>
<div class="car-box">
    <strong>#<?= $car['ItemsID'] ?> — <?= htmlspecialchars($car['marka']) ?> <?= htmlspecialchars($car['modell']) ?></strong>
    <small>(Tulaj UserID: <?= $car['UserID'] ?>)</small>

    <form method="POST">
        <input type="hidden" name="carid" value="<?= $car['ItemsID'] ?>">

        <label>Rendszám</label>
        <input type="text" name="rendszam" value="<?= htmlspecialchars($car['R/U']) ?>" required>

        <label>Márka</label>
        <input type="text" name="marka" value="<?= htmlspecialchars($car['marka']) ?>" required>

        <label>Modell</label>
        <input type="text" name="modell" value="<?= htmlspecialchars($car['modell']) ?>" required>

        <label>Típus</label>
        <select name="tipus">
            <?php
            $tipusok = ['szemelygepauto','haszonauto','munkagep','motorkerekpar','egyeb'];
            foreach ($tipusok as $t) {
                $sel = $car['tipus'] == $t ? 'selected' : '';
                echo "<option value='$t' $sel>$t</option>";
            }
            ?>
        </select>

        <label>Üzemanyag</label>
        <select name="uzemanyag">
            <?php
            $uzemanyagok = ['Benzin','Dízel','Benzingaz','Hybrid','Elektromos'];
            foreach ($uzemanyagok as $u) {
                $sel = $car['uzemanyag'] == $u ? 'selected' : '';
                echo "<option value='$u' $sel>$u</option>";
            }
            ?>
        </select>

        <label>Kivitel</label>
        <select name="kivitel">
            <?php
            $kivitelek = ['Sedan','Hatchback','Kombi','SUV','Terepjáró','Pickup','Coupe','Cabrio','Van','Sport','Buggy','Motor','Egyéb'];
            foreach ($kivitelek as $k) {
                $sel = $car['kivitel'] == $k ? 'selected' : '';
                echo "<option value='$k' $sel>$k</option>";
            }
            ?>
        </select>

        <label>Személyek száma</label>
        <input type="number" name="sz_szem" value="<?= $car['sz_szem'] ?>" required>

        <label>Súly (kg)</label>
        <input type="number" name="suly" value="<?= $car['suly'] ?>" required>

        <label>Ajtók száma</label>
        <input type="number" name="ajtokszama" value="<?= $car['ajtokszama'] ?>" required>

        <label>Ár / nap (Ft)</label>
        <input type="number" name="ar" value="<?= $car['ar/nap'] ?>" required>

        <label>Lóerő</label>
        <input type="number" name="loero" value="<?= $car['loero'] ?>" required>

        <label>Nyomaték (Nm)</label>
        <input type="number" name="nyomatek" value="<?= $car['nyomatek'] ?>" required>

        <label>Selejt</label>
        <select name="selejt">
            <option value="nem" <?= $car['selejt'] == 'nem' ? 'selected' : '' ?>>Nem</option>
            <option value="igen" <?= $car['selejt'] == 'igen' ? 'selected' : '' ?>>Igen</option>
        </select>

        <button type="submit" name="edit_car_admin">Mentés</button>
    </form>
</div>
<?php endwhile; ?>

</body>
</html>