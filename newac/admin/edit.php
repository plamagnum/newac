<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isAuthenticated()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}
$conn = getConnection();
$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "UPDATE scan_results SET hostname = ?, portid = ?, protocol = ?, state = ?, service = ?, product = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $_POST['hostname'], $_POST['portid'], $_POST['protocol'], $_POST['state'], $_POST['service'], $_POST['product'], $id);
    
    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        $error = "Помилка при оновленні запису";
    }
} else {
    $sql = "SELECT * FROM scan_results WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    if (!$data) {
        header('Location: index.php');
        exit();
    }
}

include 'edit_form.html';
?>