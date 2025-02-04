<?php
// Функції для роботи з користувачами
function getUserById($conn, $id) {
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function verifyUser($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user['id'];
    }
    return false;
}

// Функції для роботи з хостами
function getAllHosts($conn) {
    $conn = getConnection();
    $result = $conn->query("SELECT * FROM hosts ORDER BY hostname");
    $hosts = [];
    while ($row = $result->fetch_assoc()) {
        $hosts[] = $row;
    }
    return $hosts;
}

function getHostById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM hosts WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getResults($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM scan_results WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function addHost($conn, $hostname, $address) {
    $stmt = $conn->prepare("INSERT INTO hosts (hostname, address) VALUES (?, ?)");
    $stmt->bind_param('ss', $hostname, $address);
    return $stmt->execute();
}

function updateHost($conn, $id, $hostname, $address) {
    $stmt = $conn->prepare("UPDATE hosts SET hostname = ?, address = ? WHERE id = ?");
    $stmt->bind_param('ssi', $hostname, $address, $id);
    return $stmt->execute();
}

function deleteHost($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM hosts WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Функції для роботи з вразливостями
function getAllVulnerabilities($conn) {
    $result = $conn->query("SELECT * FROM vulnerabilities ORDER BY severity DESC, name");
    $vulnerabilities = [];
    while ($row = $result->fetch_assoc()) {
        $vulnerabilities[] = $row;
    }
    return $vulnerabilities;
}

function getVulnerabilityById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM vulnerabilities WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Функції для роботи з вразливостями хостів
function getHostVulnerabilities($conn, $host_id) {
    $stmt = $conn->prepare("
        SELECT hv.*, v.name, v.description, v.severity 
        FROM host_vulnerabilities hv 
        JOIN vulnerabilities v ON hv.vulnerability_id = v.id 
        WHERE hv.host_id = ?
        ORDER BY v.severity DESC
    ");
    $stmt->bind_param('i', $host_id);
    $stmt->execute();
    
    $vulnerabilities = [];
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $vulnerabilities[] = $row;
    }
    return $vulnerabilities;
}

function addHostVulnerability($conn, $host_id, $vulnerability_id, $details) {
    $stmt = $conn->prepare("INSERT INTO host_vulnerabilities (host_id, vulnerability_id, details) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $host_id, $vulnerability_id, $details);
    return $stmt->execute();
}

function updateHostVulnerability($conn, $id, $details) {
    $stmt = $conn->prepare("UPDATE host_vulnerabilities SET details = ? WHERE id = ?");
    $stmt->bind_param('si', $details, $id);
    return $stmt->execute();
}

function deleteHostVulnerability($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM host_vulnerabilities WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Допоміжні функції
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}
?>