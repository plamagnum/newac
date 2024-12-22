<?php
require_once 'function.php';

$results = getScanResults();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Results</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <h1>Scan Results</h1>
    </header>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hostname</th>
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
                                <td>{$row['id']}</td>
                                <td>{$row['hostname']}</td>
                                <td>{$row['portid']}</td>
                                <td>{$row['protocol']}</td>
                                <td>{$row['state']}</td>
                                <td>{$row['service']}</td>
                                <td>{$row['product']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No results found</td></tr>";
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