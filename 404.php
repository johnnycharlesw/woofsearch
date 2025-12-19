<?php
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
include $documentRoot . '/config.php';
$requestURI=$_SERVER['REQUEST_URI'];
$term = trim($requestURI);
$searchURI="/search?q=" . urlencode($term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page not found - WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <h1>Page not found</h1>
    <p>
        The page <?= $requestURI ?> was not found.
        Did you mean <a href="<?= $searchURI ?>">to search up <?= $term ?> using WoofSearch?</a>
    </p>
    <a href="/" class="btn">Go Back to Home</a>
</body>
</html>