<?php
include 'config.php';

$where = [];
$where[] = "selejt = 'nem'";
$where[] = "tipus = 'szemelygepauto'";

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
body {
    font-family: Arial, sans-serif;
}
.container {
    display: flex;
}
.sidebar {
    width: 260px;
    padding: 15px;
    border-right: 1px solid #ccc;
    background: #f9f9f9;
}
.content {
    flex: 1;
    padding: 15px;
}
label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}
input, select, button {
    width: 100%;
    padding: 6px;
    margin-top: 5px;
}
button {
    margin-top: 15px;
    cursor: pointer;
}
table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
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
.sidebar {
    width: 270px;
    background-color: var(--gray-panel);
    padding: 20px;
    border-right: 2px solid var(--gray-border);
}
.sidebar label {
    margin-top: 12px;
    font-size: 14px;
    color: #333;
}
input, select {
    background-color: #fafafa;
    border: 1px solid var(--gray-border);
    border-radius: 4px;
    transition: border 0.2s, box-shadow 0.2s;
}
input:focus,
select:focus {
    outline: none;
    border-color: var(--orange);
    box-shadow: 0 0 0 2px rgba(255, 128, 0, 0.2);
}
button {
    background-color: var(--orange);
    border: none;
    color: #fff;
    font-weight: bold;
    padding: 10px;
    border-radius: 4px;
    transition: background 0.2s;
}
button:hover {
    background-color: #ff8400ff;
    cursor: pointer;
}
.content {
    flex: 1;
    padding: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
}
th {
    background-color: #3a3a3a;
    color: #fff;
    text-transform: uppercase;
    font-size: 13px;
}
th, td {
    padding: 10px;
    border: 1px solid var(--gray-border);
}
tr:nth-child(even) {
    background-color: #f5f5f5;
}
tr:hover {
    background-color: rgba(255, 128, 0, 0.08);
}
img.car-img {
    max-width: 100px;
    border-radius: 5px;
}
</style>
</head>

<body>

<div class="container">

<div class="sidebar">
<form method="GET">
    <label>Márka</label>
    <input type="text" name="marka" value="<?= $_GET['marka'] ?? '' ?>">

    <label>Modell</label>
    <input type="text" name="modell" value="<?= $_GET['modell'] ?? '' ?>">

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
    <input type="number" name="ajtokszama" value="<?= $_GET['ajtokszama'] ?? '' ?>">

    <label>Ár / nap (min)</label>
    <input type="number" name="ar_min" value="<?= $_GET['ar_min'] ?? '' ?>">

    <label>Ár / nap (max)</label>
    <input type="number" name="ar_max" value="<?= $_GET['ar_max'] ?? '' ?>">

    <button type="submit">Szűrés</button>
</form>
</div>

<div class="content">
<table>
<tr>
    <th>Kép</th>
    <th>Márka</th>
    <th>Modell</th>
    <th>Üzemanyag</th>
    <th>Karosszéria</th>
    <th>Rendszám</th>
    <th>Ajtók</th>
    <th>Ár / nap</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kepHtml = $row['kep'] ? "<img src='".htmlspecialchars($row['kep'])."' class='car-img'>" : "-";
        echo "<tr>
            <td>$kepHtml</td>
            <td>{$row['marka']}</td>
            <td>{$row['modell']}</td>
            <td>{$row['uzemanyag']}</td>
            <td>{$row['kivitel']}</td>
            <td>{$row['R/U']}</td>
            <td>{$row['ajtokszama']}</td>
            <td>{$row['ar/nap']} Ft</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='9'>Nincs találat</td></tr>";
}
$conn->close();
?>
</table>
</div>
</div>
</body>
</html>
