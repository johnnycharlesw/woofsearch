<?php
include '../../init.php';
include '../puppySearchInit.php';
# Initialize the session
session_start();
$_SESSION['child_name'] = $_POST['username'];
$_SESSION['badges_earned'] = [
    'goodboy'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WoofSearch "Puppy Search"</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/puppysearch/intro/style.css">
    <link rel="stylesheet" href="/puppysearch/intro/meetruffy.css">
    <link rel="shortcut icon" href="/woofsearch_logo.png" type="image/png">
    <link rel="search" type="application/opensearchdescription+xml" title="WoofSearch" href="/opensearch.xml">
</head>
<body>
    <div class="container">
        <div class="ruffy">
            <form action="/puppysearch" method="get" class="speechbubble" width=200 height=100>
                <h1>Hi, <?= $_SESSION['child_name'] ?>!</h1>
                <p>
                    Welcome to Puppy Search.
                    I'm Ruffy.
                    <input type="submit" value="Hi, Ruffy!" />
                </p>
            </form>
            <img src="/woofsearch_logo.png" alt="Ruffy" class="logo-image">
        </div> 
        <footer>
            <p>&copy; 2025 John Charles Woods.</p>
        </footer>
    </div>
</body>
</html>