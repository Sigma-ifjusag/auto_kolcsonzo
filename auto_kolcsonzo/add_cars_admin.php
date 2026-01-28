<?php
session_start();
if (!isset($_SESSION['userid']) || $_SESSION['jogosultsag'] != 1) {
    header("Location: login.php");
    exit;
}
?>