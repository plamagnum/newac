<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['xmlfile'])) {
    $uploadDir = __DIR__;
    $uploadFile = $uploadDir . '/uploaded.xml';

    if (move_uploaded_file($_FILES['xmlfile']['tmp_name'], $uploadFile)) {
        header('Location: index.php?success=1');
    } else {
        echo 'File upload failed!';
    }
}

if (isset($_GET['success']) && $_GET['success'] == 1 && file_exists('uploaded.xml')) {
    $xml = simplexml_load_file('uploaded.xml');
    $results = [];

    foreach ($xml->host->ports->port as $port) {
        $portid = (string)$port['portid'];
        $protocol = (string)$port['protocol'];
        $state = (string)$port->state['state'];
        $service = (string)$port->service['name'];
        $product = (string)$port->service['product'];
        $results[] = "$portid/$protocol  $state   $service     $product";
    }

    file_put_contents('results.txt', implode("\n", $results));
}
?>