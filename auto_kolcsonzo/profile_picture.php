<?php
session_start();
include 'config.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

$hiba = "";
$siker = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0) {

        $allowed = ["jpg", "jpeg", "png", "webp"];
        $filename = $_FILES["profile_pic"]["name"];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $hiba = "Csak JPG, PNG vagy WEBP fájl engedélyezett!";
        } else {

            $newName = "uploads/profile_" . $_SESSION['userid'] . "_" . time() . "." . $ext;

            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $newName)) {

                $stmt = $conn->prepare("UPDATE users SET profile_pic=? WHERE UserID=?");
                $stmt->bind_param("si", $newName, $_SESSION['userid']);
                $stmt->execute();

                $siker = "Profilkép sikeresen frissítve!";
            } else {
                $hiba = "Hiba történt a feltöltés során!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profilkép változtatása</title>

<style>
:root {
    --orange: #ff8102;
    --dark: #2b2b2b;
    --gray: #e6e6e6;
    --bg: #f9f9f9;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: var(--bg);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

form {
    background-color: #fff;
    padding: 30px 40px;
    width: 340px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-top: 6px solid var(--orange);
    text-align: center;
}

h2 {
    color: var(--dark);
    margin-bottom: 25px;
}

input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border-radius: 6px;
    border: 1px solid black;
    font-size: 14px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 12px;
    background-color: var(--dark);
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
}

button:hover {
    background-color: var(--orange);
    transform: translateY(-2px);
}

.error {
    color: red;
    margin-bottom: 15px;
    font-weight: bold;
}

.success {
    color: green;
    margin-bottom: 15px;
    font-weight: bold;
}

.back-link {
    display: block;
    margin-top: 15px;
    color: var(--orange);
    text-decoration: none;
    font-weight: bold;
}

.back-link:hover {
    text-decoration: underline;
}

.preview {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--orange);
    margin-bottom: 15px;
}
</style>
</head>

<body>

<form method="POST" enctype="multipart/form-data">

    <h2>Profilkép változtatása</h2>

    <?php if ($hiba): ?>
        <div class="error"><?= htmlspecialchars($hiba) ?></div>
    <?php endif; ?>

    <?php if ($siker): ?>
        <div class="success"><?= htmlspecialchars($siker) ?></div>
    <?php endif; ?>

    <img src="images/defavatar.webp" class="preview" id="preview">

    <input type="file" name="profile_pic" accept="image/*" required onchange="previewImage(event)">

    <button type="submit">Feltöltés</button>

    <a href="index.php" class="back-link">Vissza a főoldalra</a>

</form>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>