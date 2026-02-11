<?php
include 'config.php';

// Kiemelt autók lekérdezése
$sql = "SELECT items.*, users.name AS tulaj_nev 
        FROM items 
        LEFT JOIN users ON users.UserID = items.UserID
        WHERE selejt='nem' AND kiemelt='igen'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Használtautó - Főoldal</title>
<style>
:root {
    --gray-bg: #f9f9f9;
    --gray-border: #3f3f3f;
    --orange: #ff8102;
    --dark-gray: rgba(80, 80, 80, 1);
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: var(--gray-bg);
    color: #000;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

a { text-decoration: none; }

/* FEJLÉC */
nav {
    width: 97.9%;
    height: 80px;
    background-color: var(--dark-gray);
    border-bottom: 2px solid var(--gray-border);
    display: flex;
    align-items: center;
    padding: 0 20px;
    gap: 20px;
}

#logo {
    width: 70px;
    border-radius: 6px;
    border: 2px solid var(--gray-border);
    background-color: #3f3f3f;
}

.nav-links {
    display: flex;
    gap: 20px;
    flex: 1;
    justify-content: center;
}

.nav-links a {
    padding: 10px 16px;
    background-color: var(--orange);
    color: #fff;
    border-radius: 5px;
    font-weight: bold;
    transition: 0.3s;
}

.nav-links a:hover { background-color: black; }

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

#loginBtn:hover { background-color: black; transform: scale(1.05); }

/* Fő tartalom */
.main-content {
    display: flex;
    flex: 1;
    background-color: var(--gray-bg);
    flex-wrap: wrap;
}

/* Bal oszlop */
.hablaty {
    width: 300px;
    background-color: #f9f9f9;
    color: #000;
    padding: 20px;
    border-right: 2px solid var(--gray-border);
    flex-shrink: 0;
}

.hablaty h1 { margin-top: 0; color: var(--orange); }
.hablaty h3 { line-height: 1.6; }

/* Kiemelt autók */
.featured-cars {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 40px 20px;
}

/* Vízszintes kártya */
.car-card {
    display: flex;
    flex-direction: row;
    width: 100%;
    max-width: 800px;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #ddd;
    overflow: hidden;
    transition: box-shadow 0.2s, transform 0.15s;
    margin: 0 auto;
}

.car-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.car-image {
    width: 300px;
    height: 200px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eee;
    position: relative;
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
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

.car-main {
    flex: 1;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.car-main h2 { margin: 0 0 8px 0; font-size: 18px; }
.tags { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
.tags span { background: #f1f1f1; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
.show-more-btn { background: none; border: none; color: var(--orange); font-size: 12px; cursor: pointer; padding: 0; }

.specs { margin-top: 6px; font-size: 13px; color: #444; display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px 10px; }
.plate { font-size: 13px; color: #666; margin-top: 4px; }

.car-price {
    width: 120px;
    background: #fafafa;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 10px;
    border-left: 1px solid #eee;
}
#kiem{
    text-align: center;
}

.owner { font-size: 13px; color: #555; margin-bottom: 4px; text-align: center; }
.price { font-size: 20px; font-weight: bold; color: var(--orange); }
.perday { font-size: 13px; color: #777; }

/* ===== MOBIL BARÁT ===== */
@media (max-width: 900px) {
    nav {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: auto;
        gap: 12px; /* kicsit nagyobb hely a gombok között */
        padding: 10px;
    }

    #logo {
        margin-bottom: 10px; /* logo alatt extra tér */
    }

    .nav-links {
        flex-direction: column;
        gap: 10px; /* gombok közötti távolság */
        width: 100%;
        align-items: center;
    }

    .nav-links a,
    #loginBtn {
        width: 80%;
        max-width: 300px;
        text-align: center;
        margin: 0 auto; /* középre */
        padding: 10px 0; /* kényelmesebb kattintási terület */
        font-size: 14px;
    }

    /* Fő tartalom mobilon */
    .main-content {
        flex-direction: column;
    }

    .hablaty {
        width: 100%;
        border-right: none;
        border-bottom: 2px solid var(--gray-border);
        padding: 15px;
    }

    /* Kártyák mobilon */
    .car-card {
        flex-direction: column;
        max-width: 100%;
        margin: 0 auto 15px auto;
    }

    .car-image {
        width: 100%;
        height: 220px;
        margin: 0;
        border-radius: 0;
    }

    .car-main {
        width: 100%;
        padding: 10px 15px;
    }

    .specs {
        grid-template-columns: 1fr;
    }

    .car-price {
        width: 100%;
        border-left: none;
        border-top: 1px solid #eee;
        padding: 10px 0;
    }

    .car-card * { box-sizing: border-box; max-width: 100%; }
}
</style>
</head>
<body>
<nav>
    <a href="index.php">
        <img id="logo" src="images/logo3.png" alt="Logo">
    </a>
    <div class="nav-links">
        <a href="szemelygepauto.php">Személygépautó</a>
        <a href="haszonauto.php">Haszonautó</a>
        <a href="munkagep.php">Munkagép</a>
        <a href="motorkerekpar.php">Motorkerékpár</a>
        <a href="egyeb.php">Egyéb</a>
    </div>
    <button id="loginBtn" onclick="location.href='login.php'">Bejelentkezés</button>
</nav>
<div class="main-content">
    <div class="hablaty">
        <h1>Válasszon minket.</h1>
        <h3>
            Nálunk a kényelem és a megbízhatóság alap! Modern,
            jól karbantartott autóinkkal gyorsan és rugalmasan utazhat,
            a foglalás egyszerű, az áraink átláthatóak, a kiszolgálás pedig mindig személyre szabott.
            Nálunk saját kocsiját is feltötheti és maga szabja meg hogy mennyiért adja ki naponta.
        </h3>
        <h1>Miért kölcsönözzön.</h1>
        <h3>
            Az autóbérlés rugalmas és költséghatékony megoldás: nem kell egyszerre nagy összeget kifizetni, és a karbantartás,
            biztosítás terhe sem a tiéd.
            Csak akkor fizetsz, amikor tényleg használod, így könnyen alkalmazkodik az igényeidhez és az utazási terveidhez.
        </h3>
    </div>

    <div class="featured-cars">
        <h1 id="kiem">Kiemelt ajánlatok</h1>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $stmtImgs = $conn->prepare("SELECT kep FROM item_images WHERE ItemsID=?");
                $stmtImgs->bind_param("i", $row['ItemsID']);
                $stmtImgs->execute();
                $imagesResult = $stmtImgs->get_result();
                $images = [];
                while ($img = $imagesResult->fetch_assoc()) { $images[] = htmlspecialchars($img['kep']); }
                if (empty($images)) $images[] = 'noimage.jpg';
                $imagesJson = json_encode($images);

                echo "
                <div class='car-card'>
                    <div class='car-image'>
                        <button onclick='prevImage({$row['ItemsID']})' style='left:5px;'>&lt;</button>
                        <img id='car-img-{$row['ItemsID']}' src='{$images[0]}' alt='autó'>
                        <button onclick='nextImage({$row['ItemsID']})' style='right:5px;'>&gt;</button>
                    </div>
                    <div class='car-main'>
                        <h2>".htmlspecialchars($row['marka'].' '.$row['modell'])."</h2>
                        <div class='tags'>
                            <span>".htmlspecialchars($row['uzemanyag'])."</span>
                            <span>".htmlspecialchars($row['kivitel'])."</span>
                            <span>".intval($row['ajtokszama'])." ajtó</span>
                            <span>".intval($row['sz_szem'])." fő</span>
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
                <script>
                    window['images_{$row['ItemsID']}'] = {$imagesJson};
                    window['imgIndex_' + {$row['ItemsID']}] = 0;
                </script>
                ";
            }
        } else {
            echo "<p>Nincsenek kiemelt járművek jelenleg.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

<script>
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
</script>
</body>
</html>
