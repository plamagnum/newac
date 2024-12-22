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
    $query = "SELECT * FROM scan_results";
    $result = $dbConn->query($query);
    $results = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    $dbConn->close();
    return $results;
}
?>