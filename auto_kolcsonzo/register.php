<?php
include 'config.php';

$hiba = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $szig = trim($_POST['szig'] ?? '');
    $lakc = trim($_POST['lakc'] ?? '');
    $jogosultsag = 0;

    if (!$name || !$username || !$password || !$email) {
        $hiba = "Minden mező kitöltése kötelező!";
    } elseif ($password !== $password2) {
        $hiba = "A jelszavak nem egyeznek!";
    } else {

        // 🔍 username / email ellenőrzés
        $check = $conn->prepare(
            "SELECT UserID FROM users WHERE username = ? OR email = ?"
        );
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $hiba = "A felhasználónév vagy email már foglalt!";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (username, name, email, password, szig, lakc, jogosultsag)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "ssssssi",
                $username,
                $name,
                $email,
                $hashedPassword,
                $szig,
                $lakc,
                $jogosultsag
            );

            if ($stmt->execute()) {
                header("Location: login.php?reg=ok");
                exit;
            } else {
                $hiba = "Hiba történt regisztráció közben!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
            width: 360px;
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
            margin-bottom: 15px;
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
    <form method="POST" action="register.php">
        <h2>Regisztráció</h2>
        <label>Név:</label>
        <input type="text" name="name" required><br>
        
        <label>Felhasználónév:</label>
        <input type="text" name="username" required><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Személyi igazolvány szám:</label>
        <input type="text" name="szig" required><br>
        <label>Lakcím:</label>
        <input type="text" name="lakc" required><br>

        <label>Jelszó:</label>
        <input type="password" name="password" required><br>
        <label>Jelszó megerősítése:</label>
        <input type="password" name="password2" required><br>
        
        <button type="submit">Regisztrálok</button><br><br>
            <p>Van fiókod? <a href="login.php">Bejelentkezés</a></p>
    </form>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const password2 = document.querySelector('input[name="password2"]').value;
            
            if (password !== password2) {
                e.preventDefault();
                alert('A jelszavak nem egyeznek!');
            }
        });
    </script>
</body>
</html>