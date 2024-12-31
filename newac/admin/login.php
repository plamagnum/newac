<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (isAuthenticated()) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    if (login($conn, $username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Невірний логін або пароль';
    }
}


include 'login_form.html';
?>