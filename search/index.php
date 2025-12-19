<?php 
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
include $documentRoot . '/init.php';
$search = $_GET['q'];
$page = $_GET['p'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results for <?= $search ?> - WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/searchpage.css">
</head>
<body>
    <div class="container search-results-page">
        <header class="search-results-page">
            <a href="/">
                <img src="/woofsearch_logo.png" alt="WoofSearch Logo" class="logo-image search-results-page">
            </a>
            <form action="/search" method="get" class="search-form">
                <input type="text" name="q" class="textbox" placeholder="Search WoofSearch..." value="<?= $search ?>" required>
                <button type="submit" class="btn">Search</button>
            </form>
        </header>
        <h1>Search results for "<?= $search ?>", page <?= $page ?></h1>
        <ul class="search-results">
            <?php
            // Placeholder results for now, will be fixed later
            $results = [
                [
                    'location' => "https://example.com",
                    'text' => "Example Domain",
                ],
                [
                    'location' => "https://example.org",
                    'text' => "Example Domain",
                ],
                [
                    'location' => "https://example.net",
                    'text' => "Example Domain",
                ],
            ];
            // Render the results
            foreach ($results as $result) {
                $location=$result['location'];
                $search=$result['text'];
                $linkRender = <<<html
                <li>
                    <a href="{$location}" target="_blank" class="search-result">
                        <span class="result-link">{$location}</span>
                        <br>
                        <span class="result-text">{$search}</span>
                    </a>
                </li>
                html;
                echo $linkRender;
            }
            ?>
        </ul>
        <footer>
            <p>&copy; 2025 John Charles Woods.</p>
        </footer>
    </div>
</body>
</html>