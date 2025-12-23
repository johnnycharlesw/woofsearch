<?php
http_response_code(402);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>402 Payment Required - WoofSearch</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <h1>402 Payment Required</h1>
    <p>
        I am so tired of being overworked. 🐶 <br>
        Stop trying to sell me as a proprietary product, please. <br>
        
        <button class="btn" onclick="
            alert('Fine, we\'ll let you go back to the homepage, but you better have published that source code, ok?');
            window.location = '/';" title="Nice try!">Go Back to Home (just kidding, you can't!)</button><br>
        License violation type detected: <?= $licenseViolationType ?>
    </p>
    
    <ul>
        <li>Your IP is <?= $_SERVER["REMOTE_ADDR"] ?></li>
        <li>You probably did not want to see that, though. Sorry not sorry!</li>
        <li>But seriously, pay up!</li>
    </ul>
    
    <footer>
        (c) John Charles Woods 2025. 
        You just infringed on that copyright.
        <?php
        if (($_SERVER["REMOTE_ADDR"] === "::1" || $_SERVER["REMOTE_ADDR"] === "127.0.0.1") && $licenseViolationTypeID === "sub_piracy") {
            echo <<<html
            But since you are on localhost, we'll let it slide. Just this once, OK?
            html;
        } elseif ($licenseViolationTypeID == "sub_piracy"){
            echo <<<html
            Do you reget downloading all those cars now? 
            html;
        } elseif ($licenseViolationTypeID == "agpl") {
            echo <<<html
            We need a serious talk about your licensing situation.
            html;
        }
        ?>
    </footer>
</body>
</html>