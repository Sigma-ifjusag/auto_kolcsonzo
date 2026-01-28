<?php
session_start();
include 'config.php';

$hiba = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$login || !$password) {
        $hiba = "Minden mező kötelező!";
    } else {

        $stmt = $conn->prepare(
            "SELECT UserID, username, name, password, jogosultsag
             FROM users
             WHERE username = ? OR email = ?
             LIMIT 1"
        );
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {

            if (password_verify($password, $user['password'])) {

                $_SESSION['userid'] = $user['UserID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['jogosultsag'] = $user['jogosultsag'];

                // 🔀 Átirányítás jogosultság alapján
                if ($user['jogosultsag'] == 1) {
                    header("Location: add_cars_admin.php"); // admin
                } else {
                    header("Location: add_cars_user.php"); // normál user
                }
                exit;

            } else {
                $hiba = "Hibás jelszó!";
            }

        } else {
            $hiba = "Nincs ilyen felhasználó!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
</head>

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
}
h2 {
    text-align: center;
    color: var(--dark);
    margin-bottom: 25px;
}
label {
    display: block;
    margin-bottom: 5px;
    color: var(--dark);
    font-weight: bold;
    font-size: 14px;
}
input {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border-radius: 6px;
    border: 1px solid var(--gray);
    font-size: 14px;
    box-sizing: border-box;
}
input:focus {
    outline: none;
    border-color: var(--orange);
    box-shadow: 0 0 5px rgba(255,129,2,0.4);
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
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}
p {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}
a {
    color: var(--orange);
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    text-decoration: underline;
}
</style>

<body>
<form method="POST" action="login.php">
    <h2>Bejelentkezés</h2>

    <?php if ($hiba): ?>
        <div class="error"><?= htmlspecialchars($hiba) ?></div>
    <?php endif; ?>

    <label>Felhasználónév vagy Email:</label>
    <input type="text" name="login" required>

    <label>Jelszó:</label>
    <input type="password" name="password" required>

    <button type="submit">Bejelentkezés</button>

    <p>Nem vagy még tag? <a href="register.php">Regisztráció</a></p>
</form>
</body>
</html>