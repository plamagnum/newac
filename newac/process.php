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