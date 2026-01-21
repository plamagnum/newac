<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isAuthenticated()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
    return; // Explicit return for code clarity (jsonResponse already calls exit)
}

$conn = getConnection();
$sql = "SELECT id, name FROM vulnerabilities";
$result = $conn->query($sql);

$vulnerabilities = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vulnerabilities[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($vulnerabilities);
?>