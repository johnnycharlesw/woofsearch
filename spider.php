<?php
include 'init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go on, my spider - WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/spiderstart.css">
</head>
<body>
    <div class="container spiderstart">
        <header class="logo">
            <img src="/woofsearch_logo.png" alt="WoofSearch Logo" class="logo-image spiderstart">
            <p>WoofSearch</p>
        </header>
        <h1>Go on, my spider!</h1>
        <p>
            <code>WoofSearchBot,</code> you have been hired to compete in a competition against <code>Googlebot</code> to scan the entire internet.<br>
            <code>WoofSearchBot,</code> you are AGPLv3 licensed and here to provide an open alternative to <code>Googlebot</code> in terms of search engine backends.
        </p>
        <ul>
            <?php
            foreach ($spiderStartLinks as $url => $name) {
                echo <<<HTML
                <li>
                    <a href="{$url}">
                        {$name}
                    </a>
                </li>
                HTML;
            }
            ?>
        </ul>
        <footer>
            <p>&copy; 2025 John Charles Woods.</p>
        </footer>
    </div>
    
</body>
</html>