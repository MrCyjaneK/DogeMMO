<section>
    <div>
    <img src="<?= WEB ?>/img/planning.svg" width="256" height="256">
    <h1>pushNotification("This is a test notification", microtime(true))</h1>
    <p><?php
    $user->pushNotification("This is a test notification",microtime(true));
    ?></p>
    <a href="<?= WEB ?>/game.php?action=debug/test">Go Back</a>
  </div>
</section>
