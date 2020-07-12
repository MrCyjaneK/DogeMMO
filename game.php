<?php
session_name('st34lm3');
session_start();
include getcwd() . '/inc/config.php';
include getcwd() . '/inc/translation.php';
include getcwd() . '/inc/strings.php'; // deprecated
include getcwd() . '/inc/db.php';
$config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
$config->bindParam(":token", $token);
$config->execute();
$config = $config->fetchObject();
include getcwd() . "/inc/top.php";
include getcwd() . "/inc/class.php";
try {
    switch ($_SESSION['version']) {
        case '1':
            include getcwd() . "/inc/check.php";
            $user = new user($_SESSION['user']['id'],1);
            break;

        case '2':
            $user = new user($_SESSION['ID'],2);
            break;

        case '3':
            $user = new user($_SESSION['authid'],3);
            break;

        default:
            die("Critical error, \$_SESSION['version'] not provided.");
            break;
    }
} catch (Exception $e) {
    die(print_r($e));
}
if ($_GET['action'] == "") {
    $_GET['action'] = 'start';
    header('Location: '.WEB.'/game.php?action=start');
}
$plugin =
    "/plugins/" .
    preg_replace("/[^a-zA-Z0-9\/]+/", "", $_GET['action']) .
    "/index.php";
if (file_exists(getcwd() . $plugin)) {
    //include $plugin;
    //It's moved to other place
} else {
    $e = "Oups! Something went wrong with activating '" . $plugin . "'.";
    include getcwd() . "/inc/soft_error.php";
    die();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DogeMMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="<?= WEB ?>/styles.css">
  </head>
  <body style="max-width: 650px">
    <?php
    function n($n) { return $n; } // Don't ask
    include getcwd() . "/inc/notif.php";
    include getcwd() . $plugin;
    ?>
  </body>
</html>
