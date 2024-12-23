<?php
// function.php

require_once 'config.php';

function getDbConnection() {
    global $dbHost, $dbName, $dbUser, $dbPass;
    $dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($dbConn->connect_error) {
        die('Database connection failed: ' . $dbConn->connect_error);
    }

    return $dbConn;
}

function insertScanResult($hostname, $portid, $protocol, $state, $service, $product) {
    $dbConn = getDbConnection();
    $stmt = $dbConn->prepare("INSERT INTO scan_results (hostname, portid, protocol, state, service, product) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $hostname, $portid, $protocol, $state, $service, $product);
    $stmt->execute();
    $stmt->close();
    $dbConn->close();
}

function getScanResults() {
    $dbConn = getDbConnection();
    $query = "SELECT DISTINCT hostname FROM scan_results";
    $result = $dbConn->query($query);
    $results = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row['hostname'];
        }
    }

    $dbConn->close();
    return $results;
}

function getHostResults($hostname) {
    $dbConn = getDbConnection();
    $stmt = $dbConn->prepare("SELECT * FROM scan_results WHERE hostname = ?");
    $stmt->bind_param('s', $hostname);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    $stmt->close();
    $dbConn->close();
    return $results;
}
?>