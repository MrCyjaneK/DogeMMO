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
    <a href="/game.php?action=debug" class="bc-bot-open-btn">Refresh</a>
    <a href="/game.php" class="bc-bot-open-btn">Go Home</a>
  </div>
</section>
