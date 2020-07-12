<?php
if (session_status() == PHP_SESSION_NONE) {
    session_name('st34lm3');
    session_start();
}
session_destroy();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DogeMMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="<?= WEB ?>/styles.css">
  </head>
  <body>
    <img class="center" src="<?= WEB ?>/img/sad.svg" width="102" height="102">
    <h1>Error</h1>
    <p><?php echo str_replace(
        "\n",
        "<br />",
        htmlspecialchars(print_r($e, 1))
    ); ?></p>
    </body>
</html>
<?php die();
?>
