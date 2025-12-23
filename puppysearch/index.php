<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WoofSearch "Puppy Search"</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/puppysearch/home.css">
    <link rel="stylesheet" href="/homepage.css">
    <link rel="shortcut icon" href="/woofsearch_logo.png" type="image/png">
    <link rel="search" type="application/opensearchdescription+xml" title="WoofSearch" href="/opensearch.xml">
</head>
<body>
    <div class="container homepage">
        <!-- This is the homepage of my project I am working on locally, WoofSearch, which is being built to answer the question "is it possible to build your own search engine?" -->
        <header>
            <ul style="display:flex;list-style-type:none;">
                <li>
                    <a href="wwwLessons">Internet Lessons</a>
                </li>
                <li>
                    <a href="badges">Badges</a>
                </li>

            </ul>
        </header>
        <div class="logo">
            <img src="/woofsearch_logo.png" alt="WoofSearch Logo" class="logo-image homepage">
            <p>WoofSearch</p>
        </div>
        <div class="search-box">
            <?php 
            $documentRoot = $_SERVER['DOCUMENT_ROOT'];
            include $documentRoot . '/init.php';
            include 'puppySearchInit.php';
            $ua = $_SERVER['HTTP_USER_AGENT'];
            if (!preg_match('/Firefox/i', $ua)) {
                echo <<<HTML
                <script src="/browserpopup.js"></script>
                <link rel="stylesheet" href="/puppysearch/browserpopup.css">
                <div class="browser-popup kids-browser-popup" id="browser-popup">
                    <h4>WoofSearch recommends using Firefox</h4>
                    <p>
                    <strong><img src="/woofsearch_logo.png" alt="Ruffy" class="logo-image" width="64" height="64" />Ruffy:</strong> 
                    Sniff sniff...I smell a Chromium user! <br>
                    <strong><img src="/fx-branding/icon_full_color/Fx-Browser-icon-fullColor.png" alt="Kit" class="logo-image" width="64" height="64" /> Kit:</strong><br>
                    Let me check their user agent...it's like Gecko but not Gecko. It indeed is a Chromium user. Ok, time to go on with the explanation.<br>
                    It looks like you might be using Chromium. Firefox follows <a href="https://www.w3.org" target="_blank" title="Click here to enter the W3C's place on the internet!">the rules of the internet</a> and is billionaire-free.<br> 
                    I'd recommend you check it out once you have your parent here.
                    </p>
                    <a href="https://firefox.com/en-US/?utm_campaign=thirdparty-project-woofsearch-browser-popup" class="btn">Download Firefox (if mommy or daddy gave permission)</a>
                    <div class="title-and-close-btn">    
                        
                        <button class="btn popup-close-btn" onclick="hideBrowserPopup()">x</button>
                    </div>
                </div>
                HTML;
            }
            ?>
            <form action="/puppysearch/search" method="get" class="search-form">
                <input type="text" name="q" class="textbox" placeholder="What do you want to explore today?" required>
                
                <button type="submit" class="btn">Search</button>
            </form>
            <?php
            if (!$luckyButtonDisabled) {
                echo <<<HTML
                <form action="/lucky" method="get" class="lucky-button">
                    <input type="checkbox" name="puppySearch" checked hidden />
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