<?php
require_once 'function.php';

// Validate and sanitize the hostname parameter
$hostname = isset($_GET['hostname']) ? filter_var($_GET['hostname'], FILTER_SANITIZE_STRING) : '';
if (empty($hostname)) {
    header('Location: index.php');
    exit();
}
$results = getHostResults($hostname);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Results: <?php echo htmlspecialchars($hostname); ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <h1>Results for Host: <?php echo htmlspecialchars($hostname); ?></h1>
    </header>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Port ID</th>
                    <th>Protocol</th>
                    <th>State</th>
                    <th>Service</th>
                    <th>Product</th>
                    <th>Version</th>
                    <th>Script ID</th>
                    <th>Output</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($results)) {
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['portid']) . "</td>
                                <td>" . htmlspecialchars($row['protocol']) . "</td>
                                <td>" . htmlspecialchars($row['state']) . "</td>
                                <td>" . htmlspecialchars($row['service']) . "</td>
                                <td>" . htmlspecialchars($row['product']) . "</td>
                                <td>" . htmlspecialchars($row['version']) . "</td>
                                <td>" . htmlspecialchars($row['script_id']) . "</td>
                                <td>" . htmlspecialchars($row['script_output']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No results found for host: " . htmlspecialchars($hostname) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>Footer</p>
    </footer>
</body>
</html>