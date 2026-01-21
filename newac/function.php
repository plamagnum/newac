<?php
// function.php

require_once 'config.php';

function getDbConnection() {
    global $dbHost, $dbName, $dbUser, $dbPass;
    $dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($dbConn->connect_error) {
        error_log('Database connection failed: ' . $dbConn->connect_error);
        die('Database connection failed. Please try again later.');
    }
    
    // Set charset to UTF-8 for proper encoding
    $dbConn->set_charset('utf8mb4');

    return $dbConn;
}

function insertScanResult($address, $hostname, $portid, $protocol, $state, $service, $product, $version, $script_id, $script_output) {
    $dbConn = getDbConnection();
    $stmt = $dbConn->prepare("INSERT INTO scan_results (address, hostname, portid, protocol, state, service, product, version, script_id, script_output) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        error_log('Prepare statement failed: ' . $dbConn->error);
        $dbConn->close();
        return false;
    }
    
    $stmt->bind_param('ssssssssss', $address, $hostname, $portid, $protocol, $state, $service, $product, $version, $script_id, $script_output);
    $result = $stmt->execute();
    
    if (!$result) {
        error_log('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    $dbConn->close();
    return $result;
}

function insertHost($hostname, $address) {
    $dbConn = getDbConnection();
    $stmt = $dbConn->prepare("INSERT INTO hosts (hostname, address) VALUES (?, ?) ON DUPLICATE KEY UPDATE hostname=VALUES(hostname), address=VALUES(address)");
    
    if ($stmt === false) {
        error_log('Prepare statement failed: ' . $dbConn->error);
        $dbConn->close();
        return false;
    }
    
    $stmt->bind_param('ss', $hostname, $address);
    $result = $stmt->execute();
    
    if (!$result) {
        error_log('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    $dbConn->close();
    return $result;
}

function insertPortData($portid, $protocol, $state, $service, $product, $version, $script_id, $script_output) {
    $dbConn = getDbConnection();
    $stmt = $dbConn->prepare("INSERT INTO data (portid, protocol, state, service, product, version, script_id, script_output) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        error_log('Prepare statement failed: ' . $dbConn->error);
        $dbConn->close();
        return false;
    }
    
    $stmt->bind_param('ssssssss', $portid, $protocol, $state, $service, $product, $version, $script_id, $script_output);
    $result = $stmt->execute();
    
    if (!$result) {
        error_log('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    $dbConn->close();
    return $result;
}

function getScanResults() {
    $dbConn = getDbConnection();
    $query = "SELECT DISTINCT hostname FROM scan_results";
    $result = $dbConn->query($query);
    $results = [];

    if ($result && $result->num_rows > 0) {
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
    
    if ($stmt === false) {
        error_log('Prepare statement failed: ' . $dbConn->error);
        $dbConn->close();
        return [];
    }
    
    $stmt->bind_param('s', $hostname);
    $stmt->execute();
    $result = $stmt->get_result();
    $results = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
    }

    $stmt->close();
    $dbConn->close();
    return $results;
}
?>