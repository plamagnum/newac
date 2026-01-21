<?php
define('DB_HOST', 'mysql');
define('DB_USER', 'root');
define('DB_PASS', 'root13');
define('DB_NAME', 'newac');

function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        die("Connection failed. Please try again later.");
    }
    
    // Set charset to UTF-8 for proper encoding
    $conn->set_charset('utf8mb4');
    
    return $conn;
}

$conn = getConnection();

?>