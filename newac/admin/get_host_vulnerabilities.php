<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isAuthenticated()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

if (isset($_GET['host_id'])) {
    $host_id = (int)$_GET['host_id'];
    $vulnerabilities = getHostVulnerabilities($conn, $host_id);
    jsonResponse($vulnerabilities);
} else {
    jsonResponse(['error' => 'Host ID not provided'], 400);
}
?>