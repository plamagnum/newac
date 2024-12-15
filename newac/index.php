<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Website</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <h1>Header</h1>
    </header>
    <div class="container">
        <div class="left-column">
            <h2>Upload XML File</h2>
            <form action="process.php" method="post" enctype="multipart/form-data">
                <input type="file" name="xmlfile" accept=".xml" required>
                <button type="submit">Upload</button>
            </form>
        </div>
        <div class="right-column">
            <h2>XML File Content</h2>
            <?php
            if (isset($_GET['success']) && $_GET['success'] == 1 && file_exists('results.txt')) {
                echo nl2br(file_get_contents('results.txt'));
            }
            ?>
        </div>
    </div>
    <footer>
        <p>Footer</p>
    </footer>
</body>
</html>