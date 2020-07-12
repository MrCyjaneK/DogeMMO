<!-- TODO: Don't store quests in mysql, store them somewhere in file. -->
<section>
    <div>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?= gs('Quests') ?></h1>
    <p><?php
    $info = "Here you can see quests";
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <?php
    $quests = $db->prepare("SELECT * FROM `quests`");
    $quests = $quests->fetchAll();
    foreach ($quests as $quest) {
        ver_dump($quests);
        if ($quest["minlvl"] <= $user->lvl) {
            //$strtosend =
            //    "<b>Name:</b> " .
            //    $quest['name'] .
            //    "\n" .
            //    "<b>Time:</b> " .
            //    $quest['minutes'] .
            //    " Minutes\n" .
            //    "Accept it: /quest_" .
            //    $quest['ID'];
            echo '<a style="width:100%" href="' . WEB . '/game.php?action=quest/view&id=' .
                $quest['ID'] .
                //'" class="bc-bot-open-btn">' .
                $quest['name'] .
                ' (' .
                $quest['minutes'] .
                'm)</a>';
        }
    }
    ?>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=start" class="bc-bot-open-btn"<?= gs('Go back') ?></a>
  </div>
</section>
