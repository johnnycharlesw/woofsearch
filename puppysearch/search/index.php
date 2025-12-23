<?php 
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
include $documentRoot . '/init.php';
include '../puppySearchInit.php';
$search = $_GET['q'];
$page = $_GET['p'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results for <?= $search ?> - WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/searchpage.css">
    <link rel="shortcut icon" href="/woofsearch_logo.png" type="image/png">
    <link rel="search" type="application/opensearchdescription+xml" title="WoofSearch" href="/opensearch.xml">
</head>
<body>
    <div class="container search-results-page">
        <header class="search-results-page">
            <a href="/puppysearch">
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
            include '../../search/lib.php';
            renderResults();
            ?>
        </ul>
        <div class="page-bar">
            <a href="/search/?q=<?= $_GET['q'] ?>&p=<?= $page_-1 ?>"><?= $page_-1 ?></a>
             <span><?=  $page_ ?></span> 
            <a href="/search/?q=<?= $_GET['q'] ?>&p=<?= $page_+1 ?>"><?= $page_+1 ?></a>
        </div>
        <br>
        <br>
        <br>
        <footer>
            <p>&copy; 2025 John Charles Woods.</p>
        </footer>
    </div>
</body>
</html>