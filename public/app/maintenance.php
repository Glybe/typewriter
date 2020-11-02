<?php
declare(strict_types=1);

http_response_code(503);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8"/>
    <meta name="robots" content="noindex"/>
    <meta name="viewport" content="width=device-with, initial-scale=1.0"/>
    <title>Maintenance &mdash; TypeWriter</title>
    <link rel="stylesheet" href="https://font.mili.us/css2?family=apple-sf-pro-text"/>
    <style>
        body
        {
            display: flex;
            margin: 0;
            min-height: 100vh;
            font-family: apple-sf-pro-text, sans-serif;
            font-size: 17px;
        }

        main
        {
            position: relative;
            margin: auto;
            max-width: 540px;
            width: calc(100vw - 48px);
        }

        h1
        {
            color: #0b63e9;
            font-size: 24px;
        }

        hr
        {
            margin: 30px 0;
            border: 0;
            border-top: 3px solid hsl(212deg, 26.3157894737%, 88.8235294118%);
        }

        p
        {
            line-height: 27px;
        }

        a
        {
            color: #0b63e9;
            text-decoration-color: hsl(212deg, 26.3157894737%, 88.8235294118%);
        }

        a:hover
        {
            text-decoration-color: initial;
        }
    </style>
</head>
<body>
<main>
    <img src="https://cdn.glybe.nl/public/brand/SVG/logo.svg" alt="Glybe-logo" height="54" width="54"/>

    <h1>Maintenance - <?= $_SERVER['HTTP_HOST'] ?></h1>
    <p>
        This website is currently in maintenance mode. The main reason that causes this message is
        that we're updating the website at this right moment. Please check back in a minute!
    </p>
    <hr/>
    <p style="font-size: .8rem">
        This website is powered by <a href="https://github.com/basmilius/typewriter" rel="noopener" target="_blank">TypeWriter</a>,
        which is created by <a href="https://glybe.nl" rel="noopener" target="_blank">Glybe.nl</a>.
    </p>
</main>
</body>
</html>
