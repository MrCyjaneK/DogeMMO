<section>
    <div>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?= gs('Notifications') ?></h1>
    <p><?php
        foreach($user->notifications() as $notif) {
            ?>
            <h3><?= $notif['title'] ?> <small><i>@ <?= $notif['date']; ?></i></small></h3>
            <i><?= htmlspecialchars(str_replace("\n", "<br />",$notif['message'])); ?></i>
            <?php
        }
    ?></p>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=me"><?php echo getString("Go back",$user->langcode);?></a>
  </div>
</section>
