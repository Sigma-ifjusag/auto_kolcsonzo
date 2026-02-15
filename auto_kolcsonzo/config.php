<?php
// Docker környezetben a MySQL host 'mysql' (a service neve)
$servername = getenv('DB_HOST') ?: 'mysql';
$username = getenv('DB_USER') ?: 'kili_boss';
$password = getenv('DB_PASSWORD') ?: '1234';
$database = getenv('DB_NAME') ?: 'kocsika';

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Karakterkódolás beállítása
$conn->set_charset("utf8mb4");
?>