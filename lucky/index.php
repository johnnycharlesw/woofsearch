<?php
// lucky/index.php
function handleLuckyButton() {
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    include $documentRoot . '/init.php';
    if ($luckyButtonDisabled) {
        http_response_code(403);
        return;
    }
    $searches = [
        "surprise1",
        "surprise2",
        "surprise3",
        // Add more searches as desired.
    ];
    $randomIndex = array_rand($searches);
    http_response_code(301);
    header("Location: /search/?q=" . $searches[$randomIndex]);
}
handleLuckyButton();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="container">
        <h1>You're Feeling Lucky, Aren't You?</h1>
        <p>Well, too bad! This feature was disabled by your admin. I just want to be a good boy, so can you please:</p>
        <a href="/" class="btn">Go Back to Home</a>
    </div>
</body>
</html>