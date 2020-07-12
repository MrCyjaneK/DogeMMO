<section class="bc-main">
    <div class="bc-main-content">
    <img src="/img/dogecoin.svg" width="102" height="102">
    <h1><?= gs('Quests'); ?></h1>
    <?php
    $qid = $_GET['id'];
    $quests = $db->prepare(
        "SELECT * FROM `quests` WHERE id=:id"
    );
    $quests->bindParam(":id", $qid);
    $quests->execute();
    $quests = $quests->fetchAll();
    if ($quests == []) {
        $info = gs('Uhh... It looks like invalid quest id...');
    } else {
        $quest = $quests[0];
        if ($quest["minlvl"] <= $user->lvl) {
            // Check if user is already on quest...
            $check = $db->prepare(
                "SELECT * FROM `active_quests` WHERE user_id=:user_id"
            );
            $check->bindParam(":user_id", $user->ID);
            $check->execute();
            $check = $check->fetchAll();
            $info =
                $quest['name'] .
                "\n" .
                $quest['about'] .
                "\n" .
                '<a style="width:100%" href="'.WEB.'/game.php?action=quest/start&id=' .
                $qid .
                '" class="bc-bot-open-btn">' . gs('Start Quest') . '</a>';
        } else {
            $info = gs('I don\'t know where have you got that command, but it\'s not going to work because you need to have higher level.');
        }
    }
    echo str_replace("\n", "<br />", print_r($info, 1));
    ?>
    <a style="width:100%" href="/game.php?action=quest" class="bc-bot-open-btn"><?= gs('Go back') ?></a>
  </div>
</section>
