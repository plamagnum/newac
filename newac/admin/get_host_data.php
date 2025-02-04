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


    // Отримання вразливостей хоста
    $stmt = $conn->prepare("SELECT hv.vulnerability_link, v.name AS vulnerability_name, hv.description FROM hosts_vulnerabilities hv JOIN vulnerabilities v ON hv.vulnerability_id = v.id WHERE hv.hostname = ?");
    $stmt->bind_param('s', $hostname);
    $stmt->execute();
    $result = $stmt->get_result();

    $vulnerabilities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $vulnerabilities[] = $row;
        }
    }



    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode([
        'hostData' => $host_data,
        'vulnerabilities' => $vulnerabilities
    ]);
}
?>