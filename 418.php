<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>418 I'm A Teapot - WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <style>
        .coffee-icon {
            position: absolute;
            top: 25%;
            left: 75%;
            transform: translate(-25%, -75%);
        }
    </style>
    <h1>418 I'm A Teapot</h1>
    <img src="/coffee-icon.png" alt="Coffee Pot" class="coffee-icon" title="According to RFC 2324, WoofSearch is neither a coffee pot or teapot so it should not be able to brew coffee upon a BREW request" />
    <p>
        <?php
        http_response_code(418);
        $date = new DateTime('now');
        $year = $date->format("Y");
        ?>
        Sorry, but this server does not brew coffee.<br>
        Use an actual coffee machine to brew your morning coffee, please.<br>
<br>
        Oh, you're not sure which one to get?<br>
        <a href="/search/?q=best%20coffee%20pots%20<?= $year ?>">WoofSearch that!</a>
    </p>
</body>
</html>