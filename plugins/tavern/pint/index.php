<section class="bc-main">
    <div class="bc-main-content">
    <img src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Tavern",$user->langcode); ?></h1>
    <p align="left"><?php
    if ($user->balance < 1) {
        $info =
            getString("You don't have enough money, please go to bank and get some");
    } else {
        $upd = $db->prepare(
            "UPDATE `users` SET `balance`=balance-1 WHERE ID=:id"
        );
        $upd->bindParam(":id", $user->ID);
        $upd->execute();
        if (rand(0, 5) == 2) {
            $att = $db->prepare(
                "SELECT * FROM `users` WHERE fightfor=:teamid AND fightfor<>teamid"
            );
            $att->bindParam(":teamid", $user->teamid);
            $att->execute();
            $att = $att->fetchAll();
            $att = count($att);
            $info =
                getString("Your team is going to be attacked by",$user->langcode) .
                " " .
                $att .
                " " /
                getString("attackers",$user->langcode);;
        }
        $txt = $db->prepare(
            "SELECT * FROM `quests_text` WHERE AND reward_from='beer'"
        );
        $txt->execute();
        $txt = $txt->fetchAll();
        $txt = print_r($txt[array_rand($txt)]['string'], 1);
        if (!isset($info)) {
            $info = $txt;
        }
    }
    echo str_replace("\n", "<br />", print_r($info, 1));
    ?></p>
	<a style="width:100%" href="<?= WEB ?>/game.php?action=tavern/pint" class="bc-bot-open-btn"><?php echo getString("One more pint",$user->langcode); ?></a>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=tavern" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
