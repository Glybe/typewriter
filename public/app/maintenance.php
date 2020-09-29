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
            color: #4b8aff;
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
            color: #057dcd;
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
    <svg height="54" viewBox="0 0 117.19 117.19">
        <defs>
            <linearGradient id="a" x1="101.82" y1="101.82" x2="15.37" y2="15.37" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#4b8aff"/>
                <stop offset="1" stop-color="#377dff"/>
            </linearGradient>
        </defs>
        <rect x="7.5" y="7.5" width="105" height="105" rx="31.65" transform="rotate(21 63.082 55.502)" fill="#4be4ff" fill-opacity=".85"/>
        <rect x="6.1" y="6.1" width="105" height="105" rx="31.65" fill="url(#a)"/>
        <path d="M39.48 34.6H63.1c8.78 0 13.68 5.47 13.68 12.24 0 6.05-3.89 10.08-8.35 11 5.11.8 9.29 5.91 9.29 11.81 0 7.42-5 13-14 13H39.48zm21.89 19.86c4.32 0 6.77-2.59 6.77-6.19S65.69 42 61.37 42H47.91v12.46zm.36 20.74c4.61 0 7.35-2.52 7.35-6.7 0-3.6-2.52-6.62-7.35-6.62H47.91V75.2z" fill="#fff"/>
    </svg>

    <h1>Maintenance - <?= $_SERVER['HTTP_HOST'] ?></h1>
    <p>
        This website is currently in maintenance mode. The main reason that causes this message is
        that we're updating the website at this right moment. Please check back in a minute!
    </p>
    <hr/>
    <p style="font-size: .8rem">
        This website is powered by <a href="https://github.com/basmilius/typewriter" rel="noopener" target="_blank">TypeWriter</a>,
        which is created by <a href="https://bas.dev" rel="noopener" target="_blank">Bas Milius</a>.
    </p>
</main>
</body>
</html>
