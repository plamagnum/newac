<?php
require_once 'auth.php';
require_once 'config/db.php';

checkAuth();

$id = $_GET['id'] ?? 0;

if ($id) {
    $conn = getConnection();
    $sql = "DELETE FROM scan_results WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: index.php');
?>