<?php
require_once 'function.php';

error_reporting(E_ALL & ~E_NOTICE);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlfile'])) {
    // Validate file upload
    $allowedMimeTypes = ['application/xml', 'text/xml'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $_FILES['xmlfile']['tmp_name']);
    finfo_close($fileInfo);
    
    // Check file extension
    $fileExtension = strtolower(pathinfo($_FILES['xmlfile']['name'], PATHINFO_EXTENSION));
    if ($fileExtension !== 'xml') {
        echo 'Invalid file extension. Only XML files are allowed!';
        exit();
    }
    
    // Check MIME type (allow common XML MIME types)
    if (!in_array($mimeType, $allowedMimeTypes)) {
        echo 'Invalid file type. Only XML files are allowed!';
        exit();
    }
    
    // Check file size (max 10MB)
    $maxFileSize = 10 * 1024 * 1024;
    if ($_FILES['xmlfile']['size'] > $maxFileSize) {
        echo 'File is too large. Maximum size is 10MB!';
        exit();
    }
    
    $uploadDir = __DIR__;
    $randomPrefix = mt_rand(1000, 9999); // Генерація рандомного числового значення
    $uploadFile = $uploadDir . '/' . $randomPrefix . '_uploaded.xml';

    if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $uploadFile)) {
        // Disable external entity loading for security (prevent XXE attacks)
        // Use LIBXML_NONET to prevent network access during XML parsing
        // Note: libxml_disable_entity_loader() is deprecated in PHP 8.0+
        if (PHP_VERSION_ID < 80000) {
            libxml_disable_entity_loader(true);
        }
        $xml = simplexml_load_file($uploadFile, 'SimpleXMLElement', LIBXML_NONET);
        
        if ($xml === false) {
            // Clean up uploaded file
            unlink($uploadFile);
            echo 'Invalid XML file format!';
            exit();
        }
        
        $results = [];

        foreach ($xml->host as $host) {
            $address = (string)$host->address['addr'];
            $hostname = (string)$host->hostnames->hostname['name'];
            insertHost($hostname, $address); // Додавання хоста у таблицю hosts

            foreach ($host->ports->port as $port) {
                $portid = (string)$port['portid'];
                $protocol = (string)$port['protocol'];
                $state = (string)$port->state['state'];
                $service = (string)$port->service['name'];
                $product = (string)$port->service['product'];
                $version = (string)$port->service['version'];
                $script_id = (string)$port->script['id'];
                $script_output = (string)$port->script['output'];
                $results[] = "Address: $address, Host: $hostname, Port: $portid/$protocol, State: $state, Service: $service, Product: $product, Version: $version, ID: $script_id, Output: $script_output";

                // Insert data into database
                insertScanResult($address, $hostname, $portid, $protocol, $state, $service, $product, $version, $script_id, $script_output);
                insertPortData($portid, $protocol, $state, $service, $product, $version, $script_id, $script_output); // Додавання даних у таблицю data
            
            }
        }
        
        // Clean up uploaded file after processing
        unlink($uploadFile);

        if (!empty($results)) {
            file_put_contents('results.txt', implode("\n", $results));
            header('Location: index.php?success=1');
            exit();
        } else {
            echo 'No port information found!';
            exit();
        }
    } else {
        echo 'File upload failed!';
        exit();
    }
}
?>