<section class="bc-main">
    <div class="bc-main-content">
    <img src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Society",$user->langcode); ?></h1>
    <p><?php
    /*$info = "📯Communication with other castles
Join @DogeMMOsociety and start talking with citizens.

📢Game news
Join @DogeMMO to keep up with the latest updates.";*/
    $info = getString("Blah blah, links are below",$user->langcode);
    $teams = $db->prepare(
        "SELECT * FROM `teams` WHERE ID=:id"
    );
    $teams->bindParam(":id", $user->teamid);
    $teams->execute();
    $teams = $teams->fetchObject();
    /* Do this in case of invite link problem */
    //$client->exportChatInviteLink($teams->groupid);
    echo str_replace("\n", "<br />", $info);
    ?></p>
    <a href="https://t.me/EarnWalletChat">EarnWallet Chat</a>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=start" class="bc-bot-open-btn"><?php echo getString("Home",$user->langcode); ?></a>
  </div>
</section>
