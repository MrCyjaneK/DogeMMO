<?php
include getcwd() . '/inc/config.php';
?>
<!DOCTYPE html>
    <head>
        <title>DogeMMO</title>
        <link rel="stylesheet" href="<?= WEB ?>/styles.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="max-width: 650px">
        <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="45%">
        <h1>In Doge we trust</h1>
        <p>In <b>DogeMMO</b> Dogecoin is the one and only currency, withdraw and deposit whenever you want.</p>
        <p>
            <a style="float: left;"  href="<?= WEB ?>/login.php">Login</a>
            <a style="float: right;" href="<?= WEB ?>/register.php">Register</a>
    </body>
</html>
