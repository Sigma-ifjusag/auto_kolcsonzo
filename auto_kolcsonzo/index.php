<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hasznaltauto</title>
    <style>
        :root {
            --gray-bg: #f9f9f9;
            --gray-card: #e6e6e6;
            --gray-header: #d0d0d0;
            --gray-border: #3f3f3f;
            --orange: #ff8102;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--gray-bg);
            color: #000;
        }

        a {
            text-decoration: none;
        }

        /* FEJLÉC */
        nav {
            width: 100%;
            height: 80px;
            background-color: #3f3f3f;
            border-bottom: 2px solid var(--gray-border);
            position: relative;
        }

        #logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 60px;
            border-radius: 6px;
            border: 2px solid var(--gray-border);
            background-color: #fff;
        }

        #loginBtn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: var(--orange);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #loginBtn:hover {
            background-color: black;
            transform: scale(1.05);
        }

        /* OLDALSÁV */
        .sidebar {
            margin: 20px;
            width: 460px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* KÁRTYA */
        .card {
            display: flex;
            gap: 15px;
            background-color: var(--gray-card);
            padding: 10px;
            border-radius: 10px;
            border: 1px solid var(--gray-border);
            align-items: center;
        }

        /* BAL OLDAL: KÉP + GOMB */
        .card-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .card-left img {
            width: 112px;
            height: 112px;
            object-fit: contain;
            background-color: #fff;
            border-radius: 8px;
            border: 2px solid var(--gray-border);
        }

        .card-left a {
            width: 140px;
            text-align: center;
            padding: 8px 0;
            background-color: var(--orange);
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
        }

        .card-left a:hover {
            background-color: black;
        }

        /* JOBB OLDAL: LEÍRÁS */
        .card-content p {
            margin: 0;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>

    <nav>
        <img id="logo" src="images/logo.png">
        <button id="loginBtn"
            onclick="location.href='http://localhost/auto_kolcsonzo/login.php'">
            Bejelentkezés
        </button>
    </nav>

    <div class="sidebar">
        <div class="card">
            <div class="card-left">
                <img src="images/kocsi.webp">
                <a href="http://localhost/auto_kolcsonzo/szemelygepauto.php">Személygépautó</a>
            </div>
            <div class="card-content">
                <p>Kényelmes személyautók mindennapi használatra.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-left">
                <img src="images/GMC_Syclone.webp">
                <a href="http://localhost/auto_kolcsonzo/haszonauto.php">Haszonautó</a>
            </div>
            <div class="card-content">
                <p>Nagy rakterű járművek szállításhoz.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-left">
                <img src="images/tractor.jpg">
                <a href="http://localhost/auto_kolcsonzo/munkagep.php">Munkagép</a>
            </div>
            <div class="card-content">
                <p>Erős gépek építési és mezőgazdasági munkákhoz.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-left">
                <img src="images/motor.jfif">
                <a href="http://localhost/auto_kolcsonzo/motorkerekpar.php">Motorkerékpár</a>
            </div>
            <div class="card-content">
                <p>Gyors és élvezetes motoros közlekedés.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-left">
                <img src="images/utanfuto.jpg">
                <a href="http://localhost/auto_kolcsonzo/egyeb.php">Egyéb</a>
            </div>
            <div class="card-content">
                <p>Egyéb járművek és speciális eszközök.</p>
            </div>
        </div>
    </div>

</body>
</html>
