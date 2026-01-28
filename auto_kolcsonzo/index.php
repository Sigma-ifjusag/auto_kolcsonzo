<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hasznaltauto</title>
    <style>
        :root {
    --gray-bg: #f9f9f9;
    --gray-dark: #ccc;
    --gray-light: #e6e6e6;
    --gray-border: #3f3f3f;
    --text-light: #f2f2f2;
    --orange: #ff8102;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: var(--gray-bg);
    color: var(--text-light);
}

.navbar {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    padding: 15px;
    background-color: var(--gray-dark);
    border-bottom: 2px solid var(--gray-border);
    margin-top: 80px;
}

.navbar a {
    background-color: #2b2b2b;
    padding: 12px;
    font-size: 22px;
    text-align: center;
    border-radius: 6px;
    transition: background 0.2s, transform 0.1s;
}

.navbar a:hover {
    background-color: var(--orange);
    color: #fff;
    transform: translateY(-2px);
}

a:link,
a:visited {
    color: var(--text-light);
    text-decoration: none;
}

img {
    display: block;
    margin: 10px auto;
    width: 180px;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid var(--gray-border);
    transition: transform 0.2s, border-color 0.2s;
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
    transition: background-color 0.3s;
}
#loginBtn:hover {
    background-color: black;
    transform: scale(1.05);
}
.logo{
    position:fixed;
    top: 0px;
    left: 10px;  
    width: 60px;
    height: auto;
    border-radius: 6px;
    border: 2px solid var(--gray-border);
    z-index: 1000;
}
.logo:hover {
    transform: scale(1.05);
    border-color: var(--orange);
}
    </style>
</head>
<body>
<img src="images/placeholder_logo.png" alt="Logó" class="logo">
        <button id="loginBtn" onclick="location.href='http://localhost/auto_kolcsonzo/login.php'">Bejelentkezés</button>
    <div class="navbar">
        <img src="images/trabi.jfif">
        <img src="images/van.jpg">
        <img src="images/tractor.jpg">
        <img src="images/motor.jfif">
        <img src="images/utanfuto.jpg">
        <a href="http://localhost/auto_kolcsonzo/szemelygepauto.php">Személygépautó</a>
        <a href="http://localhost/auto_kolcsonzo/haszonauto.php">Haszonautó</a>
        <a href="http://localhost/auto_kolcsonzo/munkagep.php">Munkagép</a>
        <a href="http://localhost/auto_kolcsonzo/motorkerekpar.php">Motorkerékpár</a>
        <a href="http://localhost/auto_kolcsonzo/egyeb.php">Egyéb</a>
    </div>
</nav>
</body>
</html>