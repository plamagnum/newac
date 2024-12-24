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
        <!-- Ліва колонка з формою для завантаження XML файлів -->
        <div class="left-column">
            <form action="process.php" method="post" enctype="multipart/form-data">
                <label for="xmlfile">Upload XML file:</label>
                <input type="file" name="xmlfile" id="xmlfile" required>
                <button type="submit">Upload</button>
            </form>

            <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p>File uploaded and processed successfully!</p>
            <?php endif; ?>
        </div>

        <!-- Права колонка з результатами сканування -->
        <div class="right-column">
            <ul>
            <?php
            if (!empty($results)) {
                foreach ($results as $hostname) {
                    echo "<li><a href='view_result.php?hostname={$hostname}'>{$hostname}</a></li>";
                }
            } else {
                echo "<li>No results found</li>";
            }
            ?>
            </ul>
        </div>
    </div>
    <footer>
        <p>Footer</p>
    </footer>
</body>
</html>