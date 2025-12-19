<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/homepage.css">
    <link rel="shortcut icon" href="/woofsearch_logo.png" type="image/png">
    <link rel="search" type="application/opensearchdescription+xml" title="WoofSearch" href="/opensearch.xml">
</head>
<body>
    <div class="container homepage">
        <!-- This is the homepage of my project I am working on locally, WoofSearch, which is being built to answer the question "is it possible to build your own search engine?" -->
        <header class="logo">
            <img src="/woofsearch_logo.png" alt="WoofSearch Logo" class="logo-image homepage">
            <p>WoofSearch</p>
        </header>
        <div class="search-box">
            <?php 
            $documentRoot = $_SERVER['DOCUMENT_ROOT'];
            include $documentRoot . '/init.php';
            $ua = $_SERVER['HTTP_USER_AGENT'];
            if (!preg_match('/Firefox/i', $ua)) {
                echo <<<HTML
                <script src="/browserpopup.js"></script>
                <div class="browser-popup" id="browser-popup">
                    <div class="title-and-close-btn">    
                        <h4><img src="fx-branding/icon_full_color/Fx-Browser-icon-fullColor.png" alt="Firefox Logo" class="logo-image"> WoofSearch recommends using Firefox</h4>
                        <button class="btn popup-close-btn" onclick="hideBrowserPopup()">x</button>
                    </div>
                    <p>
                        Get privacy features, better W3C compliance, and more by switching to Firefox. Plus, it is billionaire-free, just like this search engine.
                    </p>
                    <a href="https://firefox.com/en-US/?utm_campaign=thirdparty-project-woofsearch-browser-popup" class="btn">Download Firefox</a>
                </div>
                HTML;
            }
            ?>
            <form action="/search" method="get" class="search-form">
                <input type="text" name="q" class="textbox" placeholder="Search WoofSearch..." required>
                
                <button type="submit" class="btn">Search</button>
            </form>
            <?php
            if (!$luckyButtonDisabled) {
                echo <<<HTML
                <form action="/lucky" method="get" class="lucky-button">
                    <button type="submit" class="btn">I'm Feeling Lucky</button>
                </form>
                HTML;
            }
            ?>
        </div>  
        <footer>
            <p>&copy; 2025 John Charles Woods.</p>
        </footer>
    </div>
</body>
</html>