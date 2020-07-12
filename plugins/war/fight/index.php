<section>
    <div>
    <img src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo gs("💬Social"); ?></h1>
    <?php
    $check = $db->prepare(
        "SELECT * FROM `teams` WHERE ID=:id"
    );
    $check->bindParam(":id", $_GET['id']);
    $check->execute();
    $check = $check->fetchAll();
    if ($check == []) {
        echo getString("Invalid team ID!",$user->langcode);
    } else {
        $upd = $db->prepare("UPDATE `users` SET `fightfor`=:ff WHERE ID=:id");
        $upd->bindParam(":ff", $user->teamid);
        $upd->bindParam(":id", $user->ID);
        $upd->execute();
        if ($user->teamid == $_GET['id']) {
            echo getString("Thanks for coming to the battlefield!",$user->langcode).
                " <b>" .
                $user->username .
                "</b>! ".getString("You will be defending",$user->langcode).
                " ".
                $check[0]['emoji'] .
                $check[0]['name'] .
                " ".
                getString("castle.",$user->langcode);
        } else {
            echo "".
                getString("Thanks for coming to the battlefield!",$user->langcode).
                " <b>" .
                $user->username .
                "</b>! " .
                getString("You will be attacking",$user->langcode).
                " ".
                $check[0]['emoji'] .
                $check[0]['name'] .
                " ".
                getString("castle.",$user->langcode);
        }
    }
    ?>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=start" class="bc-bot-open-btn"><?php gs("Go Back"); ?></a>
  </div>
</section>
