<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isAuthenticated()) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}
$conn = getConnection();


$hostname = $_GET['hostname'] ?? '';

if ($hostname) {
    $conn = getConnection();
    $conn->begin_transaction();

    try {
        // Видалення з таблиці scan_results
        $stmt = $conn->prepare("DELETE FROM scan_results WHERE hostname = ?");
        $stmt->bind_param("s", $hostname);
        $stmt->execute();
        
        // Видалення з таблиці hosts
        $stmt = $conn->prepare("DELETE FROM hosts WHERE hostname = ?");
        $stmt->bind_param("s", $hostname);
        $stmt->execute();

        // Підтвердження транзакції
        $conn->commit();
        //echo "Records deleted successfully.";
    } catch (Exception $e) {
        // Відміна транзакції у випадку помилки
        $conn->rollback();
        echo "Failed to delete records: " . $e->getMessage();
    }

    $stmt->close();
    $conn->close();


} else {
    echo "Hostname is required.";
}


/*
$id = $_GET['id'] ?? 0;

if ($id) {
    //$conn = getConnection();
    $sql = "DELETE FROM scan_results WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
*/


header('Location: index.php');
exit();

?>