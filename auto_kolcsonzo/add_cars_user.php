<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 0) {
    header("Location: logout.php");
    exit();
}

$userid = $_SESSION['userid'];
$uzenet = "";

/* ========================= AUTÓ HOZZÁADÁS ========================= */
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
    $leiras     = $_POST['leiras'];
    $telefon    = $_POST['telefon'];
    $selejt     = $_POST['selejt'];

    // Kép feltöltése
    $kepPath = null;
    if (isset($_FILES['kep']) && $_FILES['kep']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['kep']['name']);
        $targetFile = $uploadDir . $fileName;

        // Kép feltöltése a szerverre
        if (move_uploaded_file($_FILES['kep']['tmp_name'], $targetFile)) {
            $kepPath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO items 
        (`R/U`, tipus, uzemanyag, marka, modell, kivitel, sz_szem, suly, ajtokszama, `ar/nap`, loero, nyomatek, leiras, telefon, selejt, kep, UserID) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssiiiiisssssi",
        $rendszam, $tipus, $uzemanyag, $marka, $modell, $kivitel,
        $sz_szem, $suly, $ajtok, $ar, $loero, $nyomatek, $leiras, $telefon, $selejt, $kepPath, $userid
    );
    $stmt->execute();
    $uzenet = "Autó sikeresen hozzáadva!";
}

/* ========================= AUTÓ MÓDOSÍTÁS ========================= */
if (isset($_POST['edit_car'])) {
    $carid    = (int)$_POST['carid'];
    $marka    = $_POST['marka'];
    $modell   = $_POST['modell'];
    $ar       = (int)$_POST['ar'];
    $loero    = (int)$_POST['loero'];
    $nyomatek = (int)$_POST['nyomatek'];
    $leiras   = $_POST['leiras'];
    $telefon  = $_POST['telefon'];
    $selejt   = $_POST['selejt'];

    $stmt = $conn->prepare("UPDATE items 
        SET marka=?, modell=?, `ar/nap`=?, loero=?, nyomatek=?, leiras=?, telefon=?, selejt=? 
        WHERE ItemsID=? AND UserID=?");
    $stmt->bind_param("ssiiisssii", $marka, $modell, $ar, $loero, $nyomatek, $leiras, $telefon, $selejt, $carid, $userid);
    $stmt->execute();
    $uzenet = "Autó sikeresen módosítva!";
}

/* ========================= SAJÁT AUTÓK ========================= */
$stmt = $conn->prepare("SELECT * FROM items WHERE UserID=?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$cars = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saját autóim</title>
<style>
:root {
    --gray-bg: #f9f9f9;
    --panel-bg: #ffffff;
    --input-bg: #f2f2f2;
    --gray-border: #ccc;
    --text-dark: #333;
    --text-muted: #666;
    --orange: #ff8102;
    --orange-light: #ff9d3d;
}
body { font-family: Arial, sans-serif; background-color: var(--gray-bg); color: var(--text-dark); margin: 0; padding: 30px; }
h1,h2 { color: var(--orange); margin-bottom:15px; }
.back-btn { display:inline-block; margin-bottom:20px; padding:10px 15px; background-color: var(--orange); color:#fff; text-decoration:none; border-radius:6px; font-weight:bold; transition:all .2s ease; }
.back-btn:hover { background-color: var(--orange-light); transform: translateY(-1px); }
.success { background:#e6f4ea; color:#2e7d32; padding:10px 15px; border-radius:6px; margin-bottom:20px; border:1px solid #b2d8b2; }
form, .car-box { background: var(--panel-bg); border:1px solid var(--gray-border); border-radius:10px; padding:20px; margin-bottom:25px; }
.form-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px; }
.form-group { display:flex; flex-direction:column; }
label { font-size:13px; font-weight:600; margin-bottom:6px; color: var(--text-dark); }
input, select, textarea { padding:10px 12px; border-radius:6px; border:1px solid var(--gray-border); background: var(--input-bg); font-size:14px; color: var(--text-dark); transition: all .2s ease; }
textarea { resize: vertical; min-height: 80px; }
input:hover, select:hover, textarea:hover { border-color: var(--orange-light); }
input:focus, select:focus, textarea:focus { outline:none; border-color: var(--orange); box-shadow:0 0 0 3px rgba(255,129,2,0.15); background:#fff; }
button { margin-top:18px; background: linear-gradient(135deg, var(--orange), var(--orange-light)); border:none; color:white; font-weight:bold; padding:12px; border-radius:6px; cursor:pointer; font-size:14px; transition:all .2s ease; }
button:hover { transform:translateY(-1px); box-shadow:0 4px 10px rgba(0,0,0,0.1); }
.car-header { display:flex; justify-content:space-between; align-items:center; cursor:pointer; border-bottom:1px solid var(--gray-border); padding-bottom:8px; }
.car-meta { color: var(--text-muted); font-size:14px; margin-top:5px; }
.arrow { font-size:18px; transition: transform 0.2s ease; }
.arrow.open { transform:rotate(90deg); }
.edit-form { display:none; margin-top:15px; padding-top:15px; border-top:1px solid var(--gray-border); animation: fadeIn .25s ease-in-out; }
@keyframes fadeIn { from { opacity:0; transform:translateY(-5px); } to { opacity:1; transform:translateY(0); } }
</style>

<script>
function toggleEdit(id) {
    const form = document.getElementById('edit-' + id);
    const arrow = document.getElementById('arrow-' + id);
    const isOpen = form.style.display === 'block';
    form.style.display = isOpen ? 'none' : 'block';
    arrow.classList.toggle('open');
}
</script>
</head>
<body>

<a href="index.php" class="back-btn">Vissza a főoldalra</a>

<h1>Saját autóim</h1>

<?php if ($uzenet): ?>
<div class="success"><?= $uzenet ?></div>
<?php endif; ?>

<!-- ÚJ AUTÓ FORM -->
<form method="POST" enctype="multipart/form-data">
<h2>Új autó hozzáadása</h2>
<div class="form-grid">
<div class="form-group"><label>Rendszám</label><input type="text" name="rendszam" maxlength="7" required></div>
<div class="form-group"><label>Márka</label><input type="text" name="marka" required></div>
<div class="form-group"><label>Modell</label><input type="text" name="modell" required></div>
<div class="form-group"><label>Típus</label>
    <select name="tipus" required>
        <option value="szemelygepauto">Személygépkocsi</option>
        <option value="haszonauto">Haszonautó</option>
        <option value="munkagep">Munkagép</option>
        <option value="motorkerekpar">Motor</option>
        <option value="egyeb">Egyéb</option>
    </select>
</div>
<div class="form-group"><label>Üzemanyag</label>
    <select name="uzemanyag" required>
        <option>Benzin</option><option>Dízel</option><option>Benzingaz</option>
        <option>Hybrid</option><option>Elektromos</option>
    </select>
</div>
<div class="form-group"><label>Kivitel</label>
    <select name="kivitel" required>
        <option>Sedan</option><option>Hatchback</option><option>Kombi</option>
        <option>SUV</option><option>Terepjáró</option><option>Pickup</option>
        <option>Coupe</option><option>Cabrio</option><option>Van</option>
        <option>Sport</option><option>Buggy</option><option>Motor</option><option>Egyéb</option>
    </select>
</div>
<div class="form-group"><label>Személyek száma</label><input type="number" name="sz_szem" min="1" required></div>
<div class="form-group"><label>Súly (kg)</label><input type="number" name="suly" min="1" required></div>
<div class="form-group"><label>Ajtók száma</label><input type="number" name="ajtokszama" min="1" required></div>
<div class="form-group"><label>Ár / nap</label><input type="number" name="ar" min="0" required></div>
<div class="form-group"><label>Lóerő</label><input type="number" name="loero" min="1" required></div>
<div class="form-group"><label>Nyomaték</label><input type="number" name="nyomatek" min="1" required></div>
<div class="form-group"><label>Leírás</label><textarea name="leiras"></textarea></div>
<div class="form-group"><label>Telefonszám</label><input type="text" name="telefon"></div>
<div class="form-group"><label>Állapot</label>
    <select name="selejt">
        <option value="nem">Nem selejt</option>
        <option value="igen">Selejt</option>
    </select>
</div>
<div class="form-group"><label>Autó képe</label><input type="file" name="kep" accept="image/*"></div>
</div>
<button type="submit" name="add_car">Autó hozzáadása</button>
</form>

<h2>Járműveim</h2>

<?php while($car=$cars->fetch_assoc()): ?>
<div class="car-box">
    <div class="car-header" onclick="toggleEdit(<?= $car['ItemsID'] ?>)">
        <div>
            <strong><?= htmlspecialchars($car['marka']) ?> <?= htmlspecialchars($car['modell']) ?></strong>
            <div class="car-meta"><?= htmlspecialchars($car['R/U']) ?> | <?= $car['ar/nap'] ?> Ft/nap | <?= $car['loero'] ?> LE</div>
        </div>
        <div id="arrow-<?= $car['ItemsID'] ?>" class="arrow">▶</div>
    </div>

    <?php if ($car['kep']): ?>
        <div style="margin-top:10px;">
            <img src="<?= htmlspecialchars($car['kep']) ?>" alt="Autó képe" style="max-width:200px; border-radius:8px;">
        </div>
    <?php endif; ?>

    <div id="edit-<?= $car['ItemsID'] ?>" class="edit-form">
        <form method="POST">
            <input type="hidden" name="carid" value="<?= $car['ItemsID'] ?>">
            <div class="form-grid">
                <div class="form-group"><label>Márka</label><input type="text" name="marka" value="<?= htmlspecialchars($car['marka']) ?>" required></div>
                <div class="form-group"><label>Modell</label><input type="text" name="modell" value="<?= htmlspecialchars($car['modell']) ?>" required></div>
                <div class="form-group"><label>Ár / nap</label><input type="number" name="ar" value="<?= $car['ar/nap'] ?>" required></div>
                <div class="form-group"><label>Lóerő</label><input type="number" name="loero" value="<?= $car['loero'] ?>" required></div>
                <div class="form-group"><label>Nyomaték</label><input type="number" name="nyomatek" value="<?= $car['nyomatek'] ?>" required></div>
                <div class="form-group"><label>Leírás</label><textarea name="leiras"><?= htmlspecialchars($car['leiras'] ?? '') ?></textarea></div>
                <div class="form-group"><label>Telefonszám</label><input type="text" name="telefon" value="<?= htmlspecialchars($car['telefon'] ?? '') ?>"></div>
                <div class="form-group"><label>Állapot</label>
                    <select name="selejt">
                        <option value="nem" <?= $car['selejt']=='nem'?'selected':'' ?>>Nem selejt</option>
                        <option value="igen" <?= $car['selejt']=='igen'?'selected':'' ?>>Selejt</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="edit_car">Mentés</button>
        </form>
    </div>
</div>
<?php endwhile; ?>

</body>
</html>