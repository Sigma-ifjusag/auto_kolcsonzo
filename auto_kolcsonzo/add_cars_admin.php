<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 1) {
    header("Location: logout.php");
    exit();
}

$uzenet = "";

/* =========================
   AUTÓ MÓDOSÍTÁS
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

    $stmt = $conn->prepare("
        UPDATE items SET
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
        WHERE ItemsID=?
    ");

    $stmt->bind_param(
        "ssssssiiiiissi",
        $rendszam, $tipus, $uzemanyag, $marka, $modell, $kivitel,
        $sz_szem, $suly, $ajtok, $ar, $loero, $nyomatek, $selejt, $carid
    );

    $stmt->execute();
    $uzenet = "✅ Autó adatai frissítve!";
}

/* =========================
   AUTÓK LEKÉRÉSE
========================= */
$cars = $conn->query("SELECT * FROM items ORDER BY ItemsID DESC");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Admin – Autók kezelése</title>

<style>
body { font-family: Arial; background:#f4f4f4; padding:40px; }
h1 { color:#ff8102; }
.car-box { background:#fff; padding:15px; margin-bottom:20px; border-radius:8px; }
input, select { padding:6px; margin:4px 0; width:100%; }
button { padding:8px; background:#2b2b2b; color:white; border:none; cursor:pointer; }
button:hover { background:#ff8102; }
.success { color:green; font-weight:bold; }

.back-btn {
    display:inline-block;
    margin-bottom:20px;
    padding:10px 15px;
    background:#2b2b2b;
    color:white;
    text-decoration:none;
    border-radius:6px;
}

.back-btn:hover { background:#ff8102; }

.car-form {
    display:none;
    margin-top:15px;
    animation: fadeIn .3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity:0; transform:translateY(-5px); }
    to { opacity:1; transform:translateY(0); }
}
</style>

<script>
function toggleCar(id) {
    const el = document.getElementById('car-' + id);
    el.style.display = (el.style.display === 'none') ? 'block' : 'none';
}
</script>
</head>

<body>

<a href="index.php" class="back-btn">⬅ Vissza a főoldalra</a>

<h1>Admin – Autók kezelése 🚗</h1>

<?php if ($uzenet): ?>
    <p class="success"><?= $uzenet ?></p>
<?php endif; ?>

<?php while ($car = $cars->fetch_assoc()): ?>
<div class="car-box">
    <strong>#<?= $car['ItemsID'] ?> — <?= htmlspecialchars($car['marka']) ?> <?= htmlspecialchars($car['modell']) ?></strong>
    <small>(Tulaj ID: <?= $car['UserID'] ?>)</small>
    <br><br>

    <button type="button" onclick="toggleCar(<?= $car['ItemsID'] ?>)">
        ⚙ Beállítások megjelenítése
    </button>

    <div id="car-<?= $car['ItemsID'] ?>" class="car-form">
        <form method="POST">
            <input type="hidden" name="carid" value="<?= $car['ItemsID'] ?>">

            <label>Rendszám</label>
            <input type="text" name="rendszam" value="<?= htmlspecialchars($car['R/U']) ?>">

            <label>Márka</label>
            <input type="text" name="marka" value="<?= htmlspecialchars($car['marka']) ?>">

            <label>Modell</label>
            <input type="text" name="modell" value="<?= htmlspecialchars($car['modell']) ?>">

            <label>Típus</label>
            <select name="tipus">
                <?php foreach (['szemelygepauto','haszonauto','munkagep','motorkerekpar','egyeb'] as $t): ?>
                    <option value="<?= $t ?>" <?= $car['tipus']==$t?'selected':'' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>

            <label>Üzemanyag</label>
            <select name="uzemanyag">
                <?php foreach (['Benzin','Dízel','Benzingaz','Hybrid','Elektromos'] as $u): ?>
                    <option value="<?= $u ?>" <?= $car['uzemanyag']==$u?'selected':'' ?>><?= $u ?></option>
                <?php endforeach; ?>
            </select>

            <label>Kivitel</label>
            <select name="kivitel">
                <?php foreach (['Sedan','Hatchback','Kombi','SUV','Terepjáró','Pickup','Coupe','Cabrio','Van','Sport','Motor','Egyéb'] as $k): ?>
                    <option value="<?= $k ?>" <?= $car['kivitel']==$k?'selected':'' ?>><?= $k ?></option>
                <?php endforeach; ?>
            </select>

            <label>Személyek száma</label>
            <input type="number" name="sz_szem" value="<?= $car['sz_szem'] ?>">

            <label>Súly (kg)</label>
            <input type="number" name="suly" value="<?= $car['suly'] ?>">

            <label>Ajtók száma</label>
            <input type="number" name="ajtokszama" value="<?= $car['ajtokszama'] ?>">

            <label>Ár / nap (Ft)</label>
            <input type="number" name="ar" value="<?= $car['ar/nap'] ?>">

            <label>Lóerő</label>
            <input type="number" name="loero" value="<?= $car['loero'] ?>">

            <label>Nyomaték</label>
            <input type="number" name="nyomatek" value="<?= $car['nyomatek'] ?>">

            <label>Selejt</label>
            <select name="selejt">
                <option value="nem" <?= $car['selejt']=='nem'?'selected':'' ?>>Nem</option>
                <option value="igen" <?= $car['selejt']=='igen'?'selected':'' ?>>Igen</option>
            </select>

            <button type="submit" name="edit_car_admin">💾 Mentés</button>
        </form>
    </div>
</div>
<?php endwhile; ?>

</body>
</html>