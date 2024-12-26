<?php
require_once 'auth.php';
require_once 'config/db.php';

checkAuth();

$conn = getConnection();

// Отримання всіх записів
$sql = "SELECT * FROM scan_results ORDER BY id DESC";
$result = $conn->query($sql);

include 'index_view.html';
?>