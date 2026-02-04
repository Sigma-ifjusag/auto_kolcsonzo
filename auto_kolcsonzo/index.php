<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hasznaltauto</title>
    <style>
        :root {
            --gray-bg: #f9f9f9;
            --gray-border: #3f3f3f;
            --orange: #ff8102;
            --dark-gray: #3f3f3f; /* a nav és border színe */
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

        a {
            text-decoration: none;
        }

        /* FEJLÉC */
        nav {
            width: 97.90%;
            height: 80px;
            background-color: var(--dark-gray);
            border-bottom: 2px solid var(--gray-border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 20px;
        }

        #logo {
            width: 60px;
            border-radius: 6px;
            border: 2px solid var(--gray-border);
            background-color: #fff;
        }

        /* Navbar gombok */
        .nav-links {
            display: flex;
            gap: 15px;
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

        .nav-links a:hover {
            background-color: black;
        }

        #loginBtn {
            padding: 10px 20px;
            margin-right: 25px;
            font-size: 16px;
            background-color: var(--orange);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #loginBtn:hover {
            background-color: black;
            transform: scale(1.05);
        }

        /* Fő tartalom: bal oszlop + jobb rész */
        .main-content {
            display: flex;
            flex: 1; /* kitölti a maradék magasságot */
            background-color: var(--gray-bg);
        }

        .hablaty {
            width: 300px; /* fix szélesség az oszlopnak */
            background-color: #f9f9f9; /* sötét háttér */
            color: #fff; /* fehér szöveg a kontrasztért */
            padding: 20px;
            text-align: left;
            border-right: 2px solid var(--gray-border);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .main-right {
            flex: 1; /* kitölti a fennmaradó helyet */
            padding: 20px;
            background-color: var(--gray-bg);
        }

        /* Szöveg stílus */
        .hablaty h1 {
            margin-top: 0;
            color: var(--orange); /* narancs címsor a kiemeléshez */
        }

        .hablaty h3 {
            line-height: 1.6;
            color: black; /* fehér szöveg */
        }
    </style>
</head>
<body>

    <nav>
        <img id="logo" src="images/logo.png">

        <div class="nav-links">
            <a href="http://localhost/auto_kolcsonzo/szemelygepauto.php">Személygépautó</a>
            <a href="http://localhost/auto_kolcsonzo/haszonauto.php">Haszonautó</a>
            <a href="http://localhost/auto_kolcsonzo/munkagep.php">Munkagép</a>
            <a href="http://localhost/auto_kolcsonzo/motorkerekpar.php">Motorkerékpár</a>
            <a href="http://localhost/auto_kolcsonzo/egyeb.php">Egyéb</a>
        </div>

        <button id="loginBtn"
            onclick="location.href='http://localhost/auto_kolcsonzo/login.php'">
            Bejelentkezés
        </button>
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

        <div class="main-right">
            <!-- Ide jöhet a jobb oldali tartalom -->
        </div>
    </div>

</body>
</html>
