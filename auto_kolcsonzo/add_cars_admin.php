<?php
session_start();

if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 1) {
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
    <h1>keep it real king admin</h1>
</body>
</html>