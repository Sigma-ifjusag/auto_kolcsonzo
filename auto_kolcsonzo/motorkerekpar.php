<?php
include 'config.php';

$where = [];
$where[] = "selejt = 'nem'";
$where[] = "tipus = 'motorkerekpar'";

if (!empty($_GET['marka'])) {
    $where[] = "marka LIKE '%".$conn->real_escape_string($_GET['marka'])."%'";
}

if (!empty($_GET['modell'])) {
    $where[] = "modell LIKE '%".$conn->real_escape_string($_GET['modell'])."%'";
}

if (!empty($_GET['uzemanyag'])) {
    $where[] = "uzemanyag = '".$conn->real_escape_string($_GET['uzemanyag'])."'";
}

if (!empty($_GET['kivitel'])) {
    $where[] = "kivitel = '".$conn->real_escape_string($_GET['kivitel'])."'";
}

if (!empty($_GET['ajtokszama'])) {
    $where[] = "ajtokszama = ".intval($_GET['ajtokszama']);
}

if (!empty($_GET['ar_min'])) {
    $where[] = "`ar/nap` >= ".intval($_GET['ar_min']);
}

if (!empty($_GET['ar_max'])) {
    $where[] = "`ar/nap` <= ".intval($_GET['ar_max']);
}

$sql = "SELECT * FROM items";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Autók szűrése</title>

<style>
:root {
    --gray-bg: #f2f2f2;
    --gray-panel: #e6e6e6;
    --gray-border: #cfcfcf;
    --text-dark: #1e1e1e;
    --orange: #ff8102ff;
}

body {
    font-family: Arial, sans-serif;
    background-color: var(--gray-bg);
    color: var(--text-dark);
    margin: 0;
}

.container {
    display: flex;
    min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
    width: 270px;
    background-color: var(--gray-panel);
    padding: 20px;
    border-right: 2px solid var(--gray-border);
    font-weight: bold;
}

.sidebar label {
    margin-top: 12px;
    font-size: 14px;
    display: block;
}

input, select {
    width: 100%;
    padding: 6px;
    margin-top: 5px;
    border: 1px solid var(--gray-border);
    border-radius: 4px;
}

input:focus, select:focus {
    outline: none;
    border-color: var(--orange);
    box-shadow: 0 0 0 2px rgba(255, 128, 0, 0.2);
}

button {
    margin-top: 15px;
    background-color: var(--orange);
    border: none;
    color: #fff;
    font-weight: bold;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
}

/* CONTENT */
.content {
    flex: 1;
    padding: 20px;
}

/* CAR LIST */
.car-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.car-card {
    display: flex;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #ddd;
    overflow: hidden;
    transition: box-shadow 0.2s, transform 0.15s;
}

.car-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.car-image {
    width: 220px;
    height: 150px;
    background: #eee;
    flex-shrink: 0;
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.car-main {
    flex: 1;
    padding: 15px 18px;
}

.car-main h2 {
    margin: 0 0 8px 0;
    font-size: 20px;
}

.tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.tags span {
    background: #f1f1f1;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.plate {
    font-size: 13px;
    color: #666;
}

.car-price {
    width: 170px;
    background: #fafafa;
    border-left: 1px solid #eee;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 4px;
}

.price {
    font-size: 22px;
    font-weight: bold;
    color: var(--orange);
}

.perday {
    font-size: 13px;
    color: #777;
}

.car-price button {
    margin-top: 8px;
    padding: 8px 12px;
    font-size: 13px;
}
</style>
</head>

<body>

<div class="container">

<div class="sidebar">
<form method="GET">
    <label>Márka</label>
    <input type="text" name="marka" value="<?= htmlspecialchars($_GET['marka'] ?? '') ?>">

    <label>Modell</label>
    <input type="text" name="modell" value="<?= htmlspecialchars($_GET['modell'] ?? '') ?>">

    <label>Üzemanyag</label>
    <select name="uzemanyag">
        <option value="">-- mind --</option>
        <?php
        $uzemanyagok = ['Benzin','Dízel','Benzingaz','Hybrid','Elektromos'];
        foreach ($uzemanyagok as $u) {
            $sel = ($_GET['uzemanyag'] ?? '') == $u ? 'selected' : '';
            echo "<option value='$u' $sel>$u</option>";
        }
        ?>
    </select>

    <label>Kivitel</label>
    <select name="kivitel">
        <option value="">-- mind --</option>
        <?php
        $tipusok = ['Cabrio','Sedan','Hatchback','Kombi','Pickup','Coupe','Van','Buggy','Sport','SUV','Terepjáró','Egyéb'];
        foreach ($tipusok as $t) {
            $sel = ($_GET['kivitel'] ?? '') == $t ? 'selected' : '';
            echo "<option value='$t' $sel>$t</option>";
        }
        ?>
    </select>

    <label>Ajtók száma</label>
    <input type="number" name="ajtokszama" value="<?= htmlspecialchars($_GET['ajtokszama'] ?? '') ?>">

    <label>Ár / nap (min)</label>
    <input type="number" name="ar_min" value="<?= htmlspecialchars($_GET['ar_min'] ?? '') ?>">

    <label>Ár / nap (max)</label>
    <input type="number" name="ar_max" value="<?= htmlspecialchars($_GET['ar_max'] ?? '') ?>">

    <button type="submit">Szűrés</button>
</form>
</div>

<div class="content">
<div class="car-list">
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kep = $row['kep'] ? htmlspecialchars($row['kep']) : 'noimage.jpg';

        echo "
        <div class='car-card'>
            <div class='car-image'>
                <img src='{$kep}' alt='autó'>
            </div>

            <div class='car-main'>
                <h2>".htmlspecialchars($row['marka'])." ".htmlspecialchars($row['modell'])."</h2>
                
                <div class='tags'>
                    <span>".htmlspecialchars($row['uzemanyag'])."</span>
                    <span>".htmlspecialchars($row['kivitel'])."</span>
                    <span>".intval($row['ajtokszama'])." ajtó</span>
                </div>

                <p class='plate'>Rendszám: ".htmlspecialchars($row['R/U'])."</p>
            </div>

            <div class='car-price'>
                <div class='price'>".intval($row['ar/nap'])." Ft</div>
                <div class='perday'>/ nap</div>
                <button>Részletek</button>
            </div>
        </div>
        ";
    }
} else {
    echo "<p>Nincs találat a szűrésre.</p>";
}
$conn->close();
?>
</div>
</div>

</div>
</body>
</html>