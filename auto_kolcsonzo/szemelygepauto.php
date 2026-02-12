<?php
session_start(); // Munkamenet indítása a bejelentkezés ellenőrzéséhez
include 'config.php';

// Profilkép alapértelmezett beállítása
$profilePic = "images/defavatar.webp";

// Ha be van jelentkezve, lekérjük a profilképét
if (isset($_SESSION['userid'])) {
    $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $_SESSION['userid']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (!empty($row['profile_pic'])) {
            $profilePic = $row['profile_pic'];
        }
    }
}

$where = [];
$where[] = "selejt = 'nem'";
$where[] = "tipus = 'szemelygepauto'";

if (!empty($_GET['marka'])) $where[] = "marka LIKE '%".$conn->real_escape_string($_GET['marka'])."%'";
if (!empty($_GET['modell'])) $where[] = "modell LIKE '%".$conn->real_escape_string($_GET['modell'])."%'";
if (!empty($_GET['uzemanyag'])) $where[] = "uzemanyag = '".$conn->real_escape_string($_GET['uzemanyag'])."'";
if (!empty($_GET['kivitel'])) $where[] = "kivitel = '".$conn->real_escape_string($_GET['kivitel'])."'";
if (!empty($_GET['ajtokszama'])) $where[] = "ajtokszama = ".intval($_GET['ajtokszama']);
if (!empty($_GET['ar_min'])) $where[] = "`ar/nap` >= ".intval($_GET['ar_min']);
if (!empty($_GET['ar_max'])) $where[] = "`ar/nap` <= ".intval($_GET['ar_max']);

// JAVÍTVA: foglalas tábla használata (nem kolcsonzes) és legfrissebb bérlés lekérése
$sql = "SELECT items.*, users.name AS tulaj_nev, 
               foglalas.meddig AS kiadva_datum 
        FROM items 
        LEFT JOIN users ON users.UserID = items.UserID
        LEFT JOIN (
            SELECT ItemsID, meddig 
            FROM foglalas 
            WHERE elvitte = 'nem' 
            ORDER BY meddig DESC 
            LIMIT 1
        ) AS foglalas ON items.ItemsID = foglalas.ItemsID";

if ($where) $sql .= " WHERE " . implode(" AND ", $where);

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Autók szűrése</title>
<style>
:root {
    --gray-bg: #f2f2f2;
    --gray-panel: #e6e6e6;
    --gray-border: #cfcfcf;
    --text-dark: #1e1e1e;
    --orange: #ff8102ff;
    --dark-gray: rgba(80, 80, 80, 1);
}
body {
    font-family: Arial,sans-serif;
    background-color: var(--gray-bg);
    color: var(--text-dark); margin: 0;
}
.container {
    display: flex;
    min-height: 100vh;
}
.sidebar {
    width: 270px;
    background-color: var(--gray-panel);
    padding: 20px; border-top: 2px solid var(--gray-border);
    border-right: 2px solid var(--gray-border); font-weight: bold;
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
    border-color: orange;
    box-shadow: 0 0 0 2px rgba(255,128,0,0.2);
}
button {
    margin-top: 15px;
    background-color: orange;
    border: none; color: #fff;
    font-weight: bold;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
}
.content {
    flex: 1;
    padding: 20px;
}
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
    min-height: 250px;
    align-items: stretch;
    position: relative;
}
.car-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}
.car-image {
    width: 200px;
    height: 200px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
    margin: 10px 15px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--gray-border);
    padding: 5px; position: relative;
}
.car-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
    border-radius: 4px;
}
.car-image button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    background-color: rgba(0,0,0,0.3);
    color: #000;
    border: none;
    padding: 5px 8px;
    border-radius: 50%;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
    transition: background-color 0.2s;
}
.car-image button:hover {
    background-color: rgba(0,0,0,0.6);
    color: #fff;
}
nav {
    width: 100%;
    height: 80px;
    background-color: #3f3f3f;
    border-bottom: 2px solid var(--gray-border);
    display: flex;
    align-items: center;
    padding: 0 20px;
    gap: 20px;
    box-sizing: border-box;
}
#logo {
    width: 70px;
    border-radius: 6px;
    border: 2px solid var(--gray-border);
    background-color: #fff;
}
.nav-links {
    display: flex;
    gap: 20px;
    flex: 1;
    justify-content: center;
    text-decoration: none;
}

.nav-links a {
    padding: 10px 16px;
    background-color: var(--orange);
    color: #fff;
    border-radius: 5px;
    font-weight: bold;
    transition: 0.3s;
    text-decoration: none;
}
#activated-btn{
    background-color:black;
}

.profile-menu {
    position: relative;
    display: inline-block;
}
.profile-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid var(--orange);
    object-fit: cover;
    transition: 0.2s;
}
.profile-icon:hover {
    transform: scale(1.05);
}
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 55px;
    background-color: white;
    min-width: 180px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    border-radius: 8px;
    overflow: hidden;
    z-index: 999;
}
.dropdown-content a {
    display: block;
    padding: 12px;
    color: black;
    text-decoration: none;
    transition: 0.2s;
    background-color: white;
}
.dropdown-content a:hover {
    background-color: var(--orange);
    color: white;
}
#loginBtn {
    padding: 10px 20px;
    font-size: 16px;
    background-color: var(--orange);
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
}
#loginBtn:hover {
    background-color: black;
    transform: scale(1.05);
}

.car-card.kiadott {
    filter: grayscale(100%);
    opacity: 0.6;
}
.car-card.kiadott::after {
    content: "KIADOTT";
    position: absolute;
    top: 18px;
    right: -45px;
    background: #444;
    color: #fff;
    padding: 6px 70px;
    transform: rotate(45deg);
    font-weight: bold;
    font-size: 14px;
    pointer-events: none;
}
.nav-links a:hover {
    background-color: black;
}
.car-main {
    flex: 1;
    padding: 15px 18px;
    display: flex;
    flex-direction: column;
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
.leiras-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 5px; margin-bottom: 6px;
}
.leiras {
    font-size: 13px;
    color: #444;
    overflow: hidden;
    max-height: 3em;
    transition: max-height 0.3s ease;
    flex: 1;
}
.leiras.expanded {
    max-height: 2000px; 
}
.show-more-btn {
    background: none;
    border: none;
    color: var(--orange);
    font-size: 12px;
    cursor: pointer;
    padding: 0;
    white-space: nowrap;
    margin-left: 0;
}
.specs {
    margin-top: 6px;
    font-size: 13px;
    color: #444;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 4px 10px;
}
.plate { 
    font-size: 13px;
    color: #666;
    margin-top: 4px;
}
.car-price {
    width: 170px;
    background: #fafafa;
    border-left: 1px solid #eee;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px; padding: 10px;
}
.owner {
    font-size: 13px;
    color: #555;
    margin-bottom: 4px;
    text-align: center;
}
.price {
    font-size: 22px;
    font-weight: bold;
    color: orange;
}
.perday {
    font-size: 13px;
    color: #777;
}
.rent-btn {
    margin-top: 8px;
    width: 100%;
    background-color: var(--orange);
    color: #fff;
    border: none;
    padding: 10px 0;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
    text-align: center;
    text-decoration: none;
}

.rent-btn:hover {
    background-color: black;
}

@media (max-width: 900px) {

    nav {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: auto;
        gap: 12px;
        padding: 10px;
    }
    #logo {
        margin-bottom: 10px;
    }
    .nav-links {
        flex-direction: column;
        gap: 10px;
        width: 100%;
        align-items: center;
        text-decoration: none;
    }
    .nav-links a {
        width: 80%;
        max-width: 300px;
        text-align: center;
        margin: 0 auto;
        padding: 10px 0;
        font-size: 14px;
        text-decoration: none;
    }
    .sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 2px solid var(--gray-border);
    }
    .car-card {
        flex-direction: column;
        align-items: stretch;
        min-height: auto;
    }
    .car-image {
        width: 100%;
        height: 220px;
        margin: 0 0 10px 0;
        border-radius: 0;
    }
    .car-main {
        width: 100%;
        padding: 10px 15px;
    }
    .leiras {
        max-height: 4.5em;
        }
    .specs {
        grid-template-columns: 1fr;
    }
    .car-price {
        width: 100%;
        border-left: none;
        border-top: 1px solid #eee;
        padding: 12px 0;
    }
    .car-card * { box-sizing: border-box; max-width: 100%; }
    .car-card.kiadott {
    filter: grayscale(100%);
    opacity: 0.6;
    pointer-events: none;
    }
}
</style>
</head>
<body>
    <nav>
        <a href="index.php">
            <img id="logo" src="images/logo3.png">
        </a>
        <div class="nav-links">
            <a id="activated-btn">Személygépautó</a>
            <a href="haszonauto.php">Haszonautó</a>
            <a href="munkagep.php">Munkagép</a>
            <a href="motorkerekpar.php">Motorkerékpár</a>
            <a href="egyeb.php">Egyéb</a>
        </div>

        <?php if (!isset($_SESSION['userid'])): ?>
            <button id="loginBtn" onclick="location.href='login.php'">
                Bejelentkezés
            </button>
        <?php else: ?>
            <div class="profile-menu">
                <img src="<?= htmlspecialchars($profilePic) ?>" class="profile-icon" onclick="toggleDropdown()">
                <div id="dropdown" class="dropdown-content">
                    <a href="profile_picture.php">Profilkép változtatása</a>
                    <?php if ($_SESSION['jogosultsag'] == 1): ?>
                        <a href="add_cars_admin.php">Összes kocsi</a>
                    <?php else: ?>
                        <a href="add_cars_user.php">Autóim</a>
                    <?php endif; ?>
                    <a href="logout.php">Kijelentkezés</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>

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
<?php $uzemanyagok = ['Benzin','Dízel','Benzingaz','Hybrid','Elektromos']; foreach ($uzemanyagok as $u) { $sel = ($_GET['uzemanyag'] ?? '') == $u ? 'selected' : ''; echo "<option value='$u' $sel>$u</option>"; } ?>
</select>
<label>Kivitel</label>
<select name="kivitel">
<option value="">-- mind --</option>
<?php $tipusok = ['Cabrio','Sedan','Hatchback','Kombi','Pickup','Coupe','Van','Buggy','Sport','SUV','Terepjáró','Egyéb']; foreach ($tipusok as $t) { $sel = ($_GET['kivitel'] ?? '') == $t ? 'selected' : ''; echo "<option value='$t' $sel>$t</option>"; } ?>
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
        $stmtImgs = $conn->prepare("SELECT kep FROM item_images WHERE ItemsID=?");
        $stmtImgs->bind_param("i", $row['ItemsID']);
        $stmtImgs->execute();
        $imagesResult = $stmtImgs->get_result();
        $images = [];
        while ($img = $imagesResult->fetch_assoc()) {
            $images[] = htmlspecialchars($img['kep']);
        }
        if (empty($images)) $images[] = 'noimage.jpg';

        $leiras = htmlspecialchars($row['leiras']);
        $imagesJson = json_encode($images);
        $kiadott = ($row['kiadott'] === 'igen');
        $cardClass = $kiadott ? 'car-card kiadott' : 'car-card';

        // JAVÍTVA: Dátum formázása magyar formátumban
        $kiadva_info = "";
        if ($kiadott) {
            if (!empty($row['kiadva_datum'])) {
                $datum = date('Y. m. d.', strtotime($row['kiadva_datum']));
                $kiadva_info = "<strong>KIADOTT</strong><br><small>(Visszaérkezik: {$datum})</small>";
            } else {
                $kiadva_info = "<strong>KIADOTT</strong>";
            }
        } else {
            $kiadva_info = "Telefon: " . htmlspecialchars($row['telefon']);
        }

        echo "
        <div class='{$cardClass}'>
            <div class='car-image'>
                <button onclick='prevImage({$row['ItemsID']})' style='left:5px;'>&lt;</button>
                <img id='car-img-{$row['ItemsID']}' src='{$images[0]}' alt='autó'>
                <button onclick='nextImage({$row['ItemsID']})' style='right:5px;'>&gt;</button>
            </div>
            <script>
                window['images_{$row['ItemsID']}'] = {$imagesJson};
                window['imgIndex_' + {$row['ItemsID']}] = 0;
            </script>
            <div class='car-main'>
                <h2>".htmlspecialchars($row['marka'])." ".htmlspecialchars($row['modell'])."</h2>
                <div class='tags'>
                    <span>".htmlspecialchars($row['uzemanyag'])."</span>
                    <span>".htmlspecialchars($row['kivitel'])."</span>
                    <span>".intval($row['ajtokszama'])." ajtó</span>
                    <span>".intval($row['sz_szem'])." fő</span>
                </div>
                <div class='leiras-wrapper'>
                    <div class='leiras' id='leiras-{$row['ItemsID']}'>".nl2br($leiras)."</div>
                    <button class='show-more-btn' onclick='toggleLeiras({$row['ItemsID']})'>Tovább</button>
                </div>
                <div class='specs'>
                    <div><strong>Lóerő:</strong> ".intval($row['loero'])." LE</div>
                    <div><strong>Nyomaték:</strong> ".intval($row['nyomatek'])." Nm</div>
                    <div><strong>Súly:</strong> ".intval($row['suly'])." kg</div>
                </div>
                <p class='plate'>Rendszám: ".htmlspecialchars($row['R/U'])."</p>
            </div>
            <div class='car-price'>
                <p class='owner'>Tulajdonos: ".htmlspecialchars($row['tulaj_nev'] ?? 'Ismeretlen')."</p>
                <p class='owner'>{$kiadva_info}</p>
                <div class='price'>".intval($row['ar/nap'])." Ft</div>
                <div class='perday'>/ nap</div>" .
                (!$kiadott ? "<a href='berles.php?id=".$row['ItemsID']."' class='rent-btn'>Bérlés</a>" : "") ."
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

<script>
function toggleDropdown() {
    const menu = document.getElementById("dropdown");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

window.onclick = function(event) {
    if (!event.target.matches('.profile-icon')) {
        const dropdown = document.getElementById("dropdown");
        if (dropdown && dropdown.style.display === "block") {
            dropdown.style.display = "none";
        }
    }
}

function toggleLeiras(id) {
    const elem = document.getElementById('leiras-' + id);
    const btn = elem.nextElementSibling;
    if (elem.classList.contains('expanded')) {
        elem.classList.remove('expanded');
        btn.textContent = 'Tovább';
    } else {
        elem.classList.add('expanded');
        btn.textContent = 'Összecsukás';
    }
}

function prevImage(id) {
    if (!window['images_' + id]) return;
    window['imgIndex_' + id]--;
    if (window['imgIndex_' + id] < 0) window['imgIndex_' + id] = window['images_' + id].length - 1;
    document.getElementById('car-img-' + id).src = window['images_' + id][window['imgIndex_' + id]];
}

function nextImage(id) {
    if (!window['images_' + id]) return;
    window['imgIndex_' + id]++;
    if (window['imgIndex_' + id] >= window['images_' + id].length) window['imgIndex_' + id] = 0;
    document.getElementById('car-img-' + id).src = window['images_' + id][window['imgIndex_' + id]];
}

document.querySelectorAll('.leiras-wrapper').forEach(wrapper => {
    const leiras = wrapper.querySelector('.leiras');
    const btn = wrapper.querySelector('.show-more-btn');
    if (leiras.scrollHeight <= leiras.clientHeight) {
        btn.style.display = 'none';
    }
});
</script>
</body>
</html>