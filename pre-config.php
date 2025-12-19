<?php
# Config needed to load the config
$luckyButtonDisabled=false;
$spiderStartLinks = [
    "https://en.wikipedia.org/wiki/Main_Page" => "Wikipedia",
    "https://mozilla.org" => "Mozilla",
    "https://github.com" => "GitHub",
    "https://mastodon.social" => "Mastodon (you already know about this from indexing it, don't you, <code>WoofSearchBot</code>?)"
];

# Database config
$dbUser="woofsearch";
$dbPassword="RuffRuffRuff311K!";
$dbHost="localhost";
$dbName="woofsearch";

# Interface settings
$pageResultCount = 50;