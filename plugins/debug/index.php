<section>
    <div>
    <img src="<?= WEB ?>/img/planning.svg" width="256" height="256">
    <h1>$_SESSION</h1>
    <p align="left"><?php echo str_replace(
        "\n",
        "<br />",
        htmlspecialchars(print_r($_SESSION, 1))
    ); ?></p>
    <h1>$user</h1>
    <p align="left"><?php echo str_replace(
        "\n",
        "<br />",
        htmlspecialchars(print_r($user, 1))
    ); ?></p>
    <h1>$_SERVER</h1>
    <p align="left"><?php echo str_replace(
        "\n",
        "<br />",
        htmlspecialchars(print_r($_SERVER, 1))
    ); ?></p>
    <a href="<?= WEB ?>/game.php?action=debug">Refresh</a>
    <a href="<?= WEB ?>/game.php?action=debug/test">Tests</a>
    <a href="<?= WEB ?>/game.php">Go Home</a>
  </div>
</section>
