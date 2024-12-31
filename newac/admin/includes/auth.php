<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

// Перевірка автентифікації
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

// Авторизація користувача
function login($conn, $username, $password) {
    $user_id = verifyUser($conn, $username, $password);
    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        return true;
    }
    return false;
}

// Вихід користувача
function logout() {
    session_unset();
    session_destroy();
}

// Отримання даних поточного користувача
function getCurrentUser($conn) {
    if (isAuthenticated()) {
        return getUserById($conn, $_SESSION['user_id']);
    }
    return null;
}
?>