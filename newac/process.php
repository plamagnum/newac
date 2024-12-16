<?php

error_reporting(E_ALL & ~E_NOTICE);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlfile'])) {
    $uploadDir = __DIR__;
    $uploadFile = $uploadDir . '/uploaded.xml';

    if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $uploadFile)) {
        
    
    $xml = simplexml_load_file('uploaded.xml');
    $results = [];

    foreach ($xml->host as $host) {
        $hostname = (string)$host->hostnames->hostname['name'];
        foreach ($host->ports->port as $port) {
            $portid = (string)$port['portid'];
            $protocol = (string)$port['protocol'];
            $state = (string)$port->state['state'];
            $service = (string)$port->service['name'];
            $product = (string)$port->service['product'];
            $results[] = "Host: $hostname, Port: $portid/$protocol, State: $state, Service: $service, Product: $product";
        }
    }

    if (!empty($results)) {
        file_put_contents('results.txt', implode("\n", $results));
        header('Location: index.php?success=1');
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