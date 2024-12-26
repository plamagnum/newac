<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Тут повинні бути ваші реальні дані для входу
    if ($username === 'admin' && password_verify($password, '$2y$10$O/qp1G.nNmF74NMJ.mQHB.95wMJqMjfeVmtBNdrQNwnkcSVVllMAS')) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit();
    } else {
        $error = "Невірний логін або пароль";
    }
}
include 'login_form.html';
?>