<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isAuthenticated()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}
if (isset($_GET['hostname'])) {
    $hostname = $_GET['hostname'];

    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM scan_results WHERE hostname = ?");
    $stmt->bind_param('s', $hostname);
    $stmt->execute();
    $result = $stmt->get_result();

    $host_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $host_data[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($host_data);
}
?>