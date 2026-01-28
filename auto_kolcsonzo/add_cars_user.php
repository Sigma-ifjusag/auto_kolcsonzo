<?php
session_start();

// Ha nincs bejelentkezve → logout.php → index.php
if (!isset($_SESSION['userid'])) {
    header("Location: logout.php");
    exit();
}

// (OPCIONÁLIS) Ha csak normál user léphet be (nem admin)
if ($_SESSION['jogosultsag'] != 0) {
    header("Location: logout.php");
    exit();
}
?>
<script>
function autoLogout() {
    navigator.sendBeacon("logout.php");
}

// Kilépéskor, lap elhagyásakor
window.addEventListener("unload", autoLogout);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>keep it real king user</h1>
</body>
</html>