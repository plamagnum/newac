<?php
require_once 'includes/db.php';

// Створення таблиць, якщо вони не існують
function createTables($conn) {
    // Таблиця користувачів
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);

    // Таблиця хостів
    $sql = "CREATE TABLE IF NOT EXISTS hosts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        hostname VARCHAR(255) NOT NULL,
        address VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);

    // Таблиця вразливостей
    $sql = "CREATE TABLE IF NOT EXISTS vulnerabilities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        severity ENUM('Low', 'Medium', 'High', 'Critical') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);

    // Таблиця вразливостей хостів
    $sql = "CREATE TABLE IF NOT EXISTS host_vulnerabilities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        host_id INT,
        vulnerability_id INT,
        details TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (host_id) REFERENCES hosts(id),
        FOREIGN KEY (vulnerability_id) REFERENCES vulnerabilities(id)
    )";
    $conn->query($sql);
}

// Додавання адміністратора
function createAdmin($conn) {
    $username = 'admin';
    $password = 'admin13'; // Змініть на бажаний пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT IGNORE INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $hashed_password);
    $stmt->execute();
    $stmt->close();
}

// Додавання списку веб-вразливостей
function addVulnerabilities($conn) {
    $vulnerabilities = [
        ['SQL Injection', 'Можливість виконання SQL-запитів через вхідні дані', 'High'],
        ['Cross-Site Scripting (XSS)', 'Можливість виконання шкідливого скрипта у браузері', 'High'],
        ['Cross-Site Request Forgery (CSRF)', 'Виконання небажаних дій від імені авторизованого користувача', 'Medium'],
        ['Remote Code Execution', 'Можливість виконання довільного коду на сервері', 'Critical'],
        ['Directory Traversal', 'Доступ до файлів поза межами веб-кореня', 'High'],
        ['File Upload Vulnerability', 'Завантаження небезпечних файлів на сервер', 'High'],
        ['Information Disclosure', 'Витік конфіденційної інформації', 'Medium'],
        ['Broken Authentication', 'Недоліки в системі автентифікації', 'Critical'],
        ['Security Misconfiguration', 'Неправильні налаштування безпеки', 'High'],
        ['Insecure Direct Object References', 'Прямий доступ до об\'єктів без перевірки прав', 'Medium'],
        ['Missing Function Level Access Control', 'Відсутність контролю доступу до функцій', 'High'],
        ['Using Components with Known Vulnerabilities', 'Використання компонентів з відомими вразливостями', 'Medium'],
        ['Unvalidated Redirects and Forwards', 'Незахищені перенаправлення', 'Medium'],
        ['XML External Entities (XXE)', 'Вразливості при обробці XML', 'High'],
        ['Broken Access Control', 'Порушення контролю доступу', 'High']
    ];

    $stmt = $conn->prepare("INSERT IGNORE INTO vulnerabilities (name, description, severity) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Помилка підготовки запиту: " . $conn->error);
    }
    foreach ($vulnerabilities as $vuln) {
        $stmt->bind_param('sss', $vuln[0], $vuln[1], $vuln[2]);
        $stmt->execute();
    }
    
    $stmt->close();
}

// Виконання налаштування
try {
    createTables($conn);
    createAdmin($conn);
    addVulnerabilities($conn);
    echo "Налаштування успішно завершено!";
} catch (Exception $e) {
    echo "Помилка: " . $e->getMessage();
}

$conn->close();
?>