<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("⚔️ The Big War",$user->langcode); ?></h1>
    <?php
    $teams = $db->prepare("SELECT * FROM `teams`");
    $teams->execute();
    $teams = $teams->fetchAll();
    $d = 0;
    foreach ($teams as $team) {
        if ($team['ID'] == $user->teamid) {
            $d = $team['ID'];
            continue;
        }
        echo '<a style="width:100%" href="'.WEB.'/game.php?action=war/fight&id=' .
            $team['ID'] .
            '" class="bc-bot-open-btn">' .
            $team['emoji'] .
            "" .
            gs($team['name']) .
            '</a><br />';
    }
    echo '<a style="width:100%" href="/game.php?action=war/fight&id=' .
        $d .
        '" class="bc-bot-open-btn">'.gs("Defend").'</a>';
    ?>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=start" class="bc-bot-open-btn"><?php echo gs("Go Back"); ?></a>
  </div>
</section>
