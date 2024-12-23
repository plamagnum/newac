<?php
require_once 'function.php';

error_reporting(E_ALL & ~E_NOTICE);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlfile'])) {
    $uploadDir = __DIR__;
    $randomPrefix = mt_rand(1000, 9999); // Генерація рандомного числового значення
    $uploadFile = $uploadDir . '/' . $randomPrefix . '_uploaded.xml';

    if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $uploadFile)) {
        $xml = simplexml_load_file($uploadFile);
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

                // Insert data into database
                insertScanResult($hostname, $portid, $protocol, $state, $service, $product);
            }
        }

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