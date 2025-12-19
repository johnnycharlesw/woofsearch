<?php
http_response_code(218);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>218 This is fine</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body class="fire-spread">
    <h1>218 This is fine</h1>
    <p>
        Your proxy is on fire, but it's fine.<br> <!-- subtle reference to the usage of 218 -->
        Oh, and that fire spread throughout the whole house, but it's fine.<br>
        Oh, and now the entire house is on fire, but it's fine. <br>
        OH NO! I AM ABOUT TO BE SET ON FIRE. I BETTER RUN! IT'S NOT FINE! 🐶🔥
        <style>
            /* Animating the meme */
            .fire-spread {
                animation: burn 8s linear;
                background:red;
                color:white;
            }

            @keyframes burn {
                0% {
                    background:white;
                    color:black;
                }
                100% {
                    background:red;
                    color:white;
                }
            }
        </style>
    </p>
</body>
</html>