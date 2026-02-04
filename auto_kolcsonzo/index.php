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
            flex: 1; /* hogy a Bejelentkezés gomb a jobb oldalon legyen */
            justify-content: center; /* középre a gombok */
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
        .nav-links {
            display: flex;
            gap: 80px;
            flex: 1;
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

</body>
</html>
