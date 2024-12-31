<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

isAuthenticated();

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