<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isAuthenticated()) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hostname = sanitizeInput($_POST['hostname']);
    $address = sanitizeInput($_POST['address']);

    if (addHost($conn, $hostname, $address)) {
        $success = 'Хост успішно додано';
    } else {
        $error = 'Помилка при додаванні хоста';
    }
}


include 'add_form.html';
?>