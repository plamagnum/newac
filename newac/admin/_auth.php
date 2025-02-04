<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header('Location: login.php');
        exit();
    }
}
?>