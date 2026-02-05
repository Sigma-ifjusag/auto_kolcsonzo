<?php
include 'config.php';

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

$sql = "SELECT items.*, users.name AS tulaj_nev 
        FROM items 
        LEFT JOIN users ON users.UserID = items.UserID";

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
    --orange: #ff8102;
}

* { box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    background-color: var(--gray-bg);
    color: var(--text-dark);
    margin: 0;
    overflow-x: hidden;
}

/* ================= NAVBAR ================= */
nav {
    width: 100%;
    background-color: #3f3f3f;
    display: flex;
    align-items: center;
    padding: 10px 15px;
    gap: 15px;
    flex-wrap: wrap;
}

#logo {
    width: 55px;
    border-radius: 6px;
    border: 2px solid var(--gray-border);
    background: #fff;
}
#logo:hover {
    opacity: 0.9;
}
.nav-links {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    justify-content: center;
    flex: 1;
}

.nav-links a {
    padding: 8px 14px;
    background-color: var(--orange);
    color: #fff;
    border-radius: 5px;
    font-weight: bold;
    text-decoration: none;
}
.vehicle-types {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: var(--gray-panel);
    border-bottom: 2px solid var(--gray-border);
}

/* MOBIL */
@media (max-width: 768px) {
    .vehicle-types {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .vehicle-types a {
        flex: 0 0 auto;
        white-space: nowrap;
    }

    /* opcionális: scroll csík elrejtése */
    .vehicle-types::-webkit-scrollbar {
        display: none;
    }
}
.vehicle-types a {
    padding: 10px 16px;
    background-color: var(--orange);
    color: #fff;
    border-radius: 6px;
    font-weight: bold;
    text-decoration: none;
}
#back-btn {
    padding: 8px 14px;
    background-color: var(--orange);
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

/* ================= LAYOUT ================= */
.container {
    display: flex;
    min-height: calc(100vh - 80px);
}

/* ================= SIDEBAR ================= */
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
    padding: 7px;
    margin-top: 5px;
    border: 1px solid var(--gray-border);
    border-radius: 4px;
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

/* ================= CONTENT ================= */
.content {
    flex: 1;
    padding: 20px;
}

.car-list {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* ================= CARD ================= */
.car-card {
    display: flex;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #ddd;
    overflow: hidden;
}

.car-image {
    width: 220px;
    height: 200px;
    margin: 15px;
    position: relative;
    background: #eee;
    border-radius: 8px;
    overflow: hidden;
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.car-image button {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    color: #fff;
    border: none;
    padding: 6px 10px;
    border-radius: 50%;
    cursor: pointer;
}

.car-main {
    flex: 1;
    padding: 15px;
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.tags span {
    background: #f1f1f1;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.leiras {
    font-size: 13px;
    max-height: 3em;
    overflow: hidden;
}

.leiras.expanded { max-height: 2000px; }

.show-more-btn {
    background: none;
    border: none;
    color: var(--orange);
    font-size: 12px;
    cursor: pointer;
}

.specs {
    margin-top: 8px;
    font-size: 13px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
}

.car-price {
    width: 160px;
    background: #fafafa;
    border-left: 1px solid #eee;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
#activated-btn{
    background-color: black;
}
#filter-form-container {
    overflow: hidden;
    transition: max-height 0.3s ease-out;
    max-height: 1000px; /* Elég nagy érték a tartalomnak */
}

#filter-form-container.collapsed {
    max-height: 0;
}

.filter-toggle-btn {
    width: 100%;
    margin-bottom: 10px;
    background-color: #3f3f3f; /* Sötétszürke, hogy elüssön az Orange-től */
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}

.filter-toggle-btn::after {
    content: '▲';
    font-size: 0.8em;
}

.filter-toggle-btn.collapsed::after {
    content: '▼';
}
/* ================= RESPONSIVE ================= */
@media (max-width: 900px) {
    .container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 2px solid var(--gray-border);
    }

    .car-card {
        flex-direction: column;
    }

    .car-image {
        width: 100%;
        height: 280px;
        margin: 0;
    }

    .car-price {
        width: 100%;
        flex-direction: row;
        justify-content: space-around;
        border-left: none;
        border-top: 1px solid #eee;
        padding: 10px;
    }
}

@media (max-width: 480px) {
    .specs {
        grid-template-columns: 1fr;
    }

    #back-btn {
        width: 100%;
    }
}
</style>
</head>
<body>

    <nav>
    <a href="http://localhost/auto_kolcsonzo/index.php">
    <img id="logo" src="images/logo.png" alt="Főoldal">
    </a>
</nav>
<div class="vehicle-types">
    <a id="activated-btn">Személygépautó</a>
    <a href="http://localhost/auto_kolcsonzo/haszonauto.php">Haszonautó</a>
    <a href="http://localhost/auto_kolcsonzo/munkagep.php">Munkagép</a>
    <a href="http://localhost/auto_kolcsonzo/motorkerekpar.php">Motorkerékpár</a>
    <a href="http://localhost/auto_kolcsonzo/egyeb.php">Egyéb</a>
</div>
<div class="container">
<div class="sidebar">
    <button type="button" class="filter-toggle-btn" onclick="toggleFilter()" id="filterBtn">
        Szűrés beállításai
    </button>

    <div id="filter-form-container">
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

            <button type="submit" style="width:100%">Keresés alkalmazása</button>
        </form>
    </div>
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

        echo "
        <div class='car-card'>
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
                <p class='owner'>Telefon: ".htmlspecialchars($row['telefon'])."</p>
                <div class='price'>".intval($row['ar/nap'])." Ft</div>
                <div class='perday'>/ nap</div>
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
function toggleFilter() {
    const container = document.getElementById('filter-form-container');
    const btn = document.getElementById('filterBtn');
    
    container.classList.toggle('collapsed');
    btn.classList.toggle('collapsed');
}

// Opcionális: Mobilon alapértelmezetten legyen csukva a szűrő
if (window.innerWidth < 768) {
    toggleFilter();
}
// Csak akkor mutatjuk a Tovább gombot, ha a leírás hosszabb, mint a max-height
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