<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 0) {
    header("Location: login.php");
    exit;
}
?>