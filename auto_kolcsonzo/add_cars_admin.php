
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 1) {
    header("Location: logout.php");
    exit();
}

$uzenet = "";

/* ========================= AUTÓ MÓDOSÍTÁS ========================= */
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
    $leiras    = $_POST['leiras'];
    $telefon   = $_POST['telefon'];
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
        leiras=?,
        telefon=?,
        selejt=?
        WHERE ItemsID=?
    ");
    $stmt->bind_param(
    "ssssssiiiiisssi",
    $rendszam, $tipus, $uzemanyag, $marka, $modell, $kivitel,
    $sz_szem, $suly, $ajtok, $ar, $loero, $nyomatek,
    $leiras, $telefon, $selejt, $carid
    );
    $stmt->execute();
    $uzenet = "Autó adatai frissítve!";
}

/* ========================= AUTÓK LEKÉRÉSE ========================= */
$cars = $conn->query("SELECT * FROM items ORDER BY ItemsID DESC");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin – Autók kezelése</title>

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

/* BODY */
body {
    font-family: Arial, sans-serif;
    background-color: var(--gray-bg);
    color: var(--text-dark);
    margin: 0;
    padding: 30px;
}

/* HEADINGS */
h1 {
    color: var(--orange);
    margin-bottom: 20px;
}

/* VISSZA GOMB */
.back-btn {
    display:inline-block;
    margin-bottom:20px;
    padding:10px 15px;
    background-color: var(--orange);
    color: #fff;
    text-decoration:none;
    border-radius:6px;
    font-weight:bold;
    transition:all .2s ease;
}
.back-btn:hover {
    background-color: var(--orange-light);
    transform: translateY(-1px);
}

/* SUCCESS */
.success {
    background:#e6f4ea;
    color:#2e7d32;
    padding:10px 15px;
    border-radius:6px;
    margin-bottom:20px;
    border:1px solid #b2d8b2;
}

/* CAR BOX */
.car-box {
    background: var(--panel-bg);
    border:1px solid var(--gray-border);
    border-radius:10px;
    padding:20px;
    margin-bottom:25px;
}

.car-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    cursor:pointer;
    border-bottom:1px solid var(--gray-border);
    padding-bottom:8px;
}

.car-meta {
    color: var(--text-muted);
    font-size:14px;
    margin-top:5px;
}

.arrow {
    font-size:18px;
    transition: transform 0.2s ease;
}

.arrow.open { transform:rotate(90deg); }

/* EDIT FORM */
.edit-form {
    display:none;
    margin-top:15px;
    padding-top:15px;
    border-top:1px solid var(--gray-border);
    animation: fadeIn .25s ease-in-out;
}

@keyframes fadeIn {
    from { opacity:0; transform:translateY(-5px); }
    to { opacity:1; transform:translateY(0); }
}

.form-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap:18px;
}

.form-group {
    display:flex;
    flex-direction:column;
}


label {
    font-size:13px;
    font-weight:600;
    margin-bottom:6px;
    color: var(--text-dark);
}

input, select, textarea {
    padding:10px 12px;
    border-radius:6px;
    border:1px solid var(--gray-border);
    background: var(--input-bg);
    font-size:14px;
    color: var(--text-dark);
    transition: all .2s ease;
}

input:hover, select:hover { border-color: var(--orange-light); }
input:focus, select:focus {
    outline:none;
    border-color: var(--orange);
    box-shadow:0 0 0 3px rgba(255,129,2,0.15);
    background:#fff;
}

/* BUTTONS */
button {
    margin-top:18px;
    background: linear-gradient(135deg, var(--orange), var(--orange-light));
    border:none;
    color:white;
    font-weight:bold;
    padding:12px;
    border-radius:6px;
    cursor:pointer;
    font-size:14px;
    transition:all .2s ease;
}
button:hover {
    transform:translateY(-1px);
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
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

<h1>Admin – Autók kezelése</h1>

<?php if ($uzenet): ?>
<div class="success"><?= $uzenet ?></div>
<?php endif; ?>

<?php while($car=$cars->fetch_assoc()): ?>
<div class="car-box">
    <div class="car-header" onclick="toggleEdit(<?= $car['ItemsID'] ?>)">
        <div>
            <strong>#<?= $car['ItemsID'] ?> — <?= htmlspecialchars($car['marka']) ?> <?= htmlspecialchars($car['modell']) ?></strong>
            <div class="car-meta">Tulaj ID: <?= $car['UserID'] ?> | <?= $car['R/U'] ?> | <?= $car['ar/nap'] ?> Ft/nap</div>
        </div>
        <div id="arrow-<?= $car['ItemsID'] ?>" class="arrow">▶</div>
    </div>

    <div id="edit-<?= $car['ItemsID'] ?>" class="edit-form">
        <form method="POST">
            <input type="hidden" name="carid" value="<?= $car['ItemsID'] ?>">
            <div class="form-grid">
                <div class="form-group"><label>Rendszám</label><input type="text" name="rendszam" value="<?= htmlspecialchars($car['R/U']) ?>"></div>
                <div class="form-group"><label>Márka</label><input type="text" name="marka" value="<?= htmlspecialchars($car['marka']) ?>"></div>
                <div class="form-group"><label>Modell</label><input type="text" name="modell" value="<?= htmlspecialchars($car['modell']) ?>"></div>
                <div class="form-group"><label>Típus</label>
                    <select name="tipus">
                        <?php foreach (['szemelygepauto','haszonauto','munkagep','motorkerekpar','egyeb'] as $t): ?>
                        <option value="<?= $t ?>" <?= $car['tipus']==$t?'selected':'' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Üzemanyag</label>
                    <select name="uzemanyag">
                        <?php foreach (['Benzin','Dízel','Benzingaz','Hybrid','Elektromos'] as $u): ?>
                        <option value="<?= $u ?>" <?= $car['uzemanyag']==$u?'selected':'' ?>><?= $u ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Kivitel</label>
                    <select name="kivitel">
                        <?php foreach (['Sedan','Hatchback','Kombi','SUV','Terepjáró','Pickup','Coupe','Cabrio','Van','Sport','Motor','Egyéb'] as $k): ?>
                        <option value="<?= $k ?>" <?= $car['kivitel']==$k?'selected':'' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Személyek száma</label><input type="number" name="sz_szem" value="<?= $car['sz_szem'] ?>"></div>
                <div class="form-group"><label>Súly (kg)</label><input type="number" name="suly" value="<?= $car['suly'] ?>"></div>
                <div class="form-group"><label>Ajtók száma</label><input type="number" name="ajtokszama" value="<?= $car['ajtokszama'] ?>"></div>
                <div class="form-group"><label>Ár / nap</label><input type="number" name="ar" value="<?= $car['ar/nap'] ?>"></div>
                <div class="form-group"><label>Lóerő</label><input type="number" name="loero" value="<?= $car['loero'] ?>"></div>
                <div class="form-group"><label>Nyomaték</label><input type="number" name="nyomatek" value="<?= $car['nyomatek'] ?>"></div>
                <div class="form-group">
                    <label>Leírás</label>
                    <textarea name="leiras"><?= htmlspecialchars($car['leiras'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Telefonszám</label>
                    <input type="text" name="telefon" value="<?= htmlspecialchars($car['telefon'] ?? '') ?>">
                </div>
                <div class="form-group"><label>Selejt</label>
                    <select name="selejt">
                        <option value="nem" <?= $car['selejt']=='nem'?'selected':'' ?>>Nem</option>
                        <option value="igen" <?= $car['selejt']=='igen'?'selected':'' ?>>Igen</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="edit_car_admin">Mentés</button>
        </form>
    </div>
</div>
<?php endwhile; ?>

</body>
</html>
