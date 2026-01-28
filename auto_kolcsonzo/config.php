<?php
$servername = "localhost";
$username = "kili_boss";
$password = "1234";
$database = "kocsika";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>