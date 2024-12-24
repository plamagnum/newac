<?php
require_once 'function.php';

$hostname = $_GET['hostname'];
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
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($results)) {
                    foreach ($results as $row) {
                        echo "<tr>
                                <td>{$row['portid']}</td>
                                <td>{$row['protocol']}</td>
                                <td>{$row['state']}</td>
                                <td>{$row['service']}</td>
                                <td>{$row['product']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No results found for host: " . htmlspecialchars($hostname) . "</td></tr>";
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