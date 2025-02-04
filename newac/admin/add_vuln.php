<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isAuthenticated()) {
    header('Location: login.php');
    exit();
};

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['hostname']) && isset($data['vulnerability_id']) && isset($data['vulnerability_link']) && isset($data['description']) {
    $hostname = $data['hostname'];
    $vulnerability_id = $data['vulnerability_id'];
    $vulnerability_link = $data['vulnerability_link'];
    $description = $data['description'];

    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO hosts_vulnerabilities (hostname, vulnerability_id, vulnerability_link, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('sis', $hostname, $vulnerability_id, $vulnerability_link, $description);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>