<?php
require_once 'auth.php';
require_once 'config/db.php';

checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getConnection();
    
    $sql = "INSERT INTO scan_results (hostname, portid, protocol, state, service, product) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $_POST['hostname'], $_POST['portid'], $_POST['protocol'], $_POST['state'], $_POST['service'], $_POST['product']);
    
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Помилка при додаванні запису";
    }
}

include 'add_form.html';
?>