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
?>