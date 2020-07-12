<section>
    <div>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo $stle->name; ?></h1>
    <p><?php
    $info = getString("Battle",$user->langcode);
    echo str_replace("\n", "<br />", print_r($info, 1));
    ?></p>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=battle/pvp" class="bc-bot-open-btn"><?php echo getString("PvP",$user->langcode); ?></a><br />
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=castle" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
