<section>
    <div>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("PvP",$user->langcode); ?></h1>
    <p><?php
    $info = getString("This is PvP. Here you can with with other players.",$user->langcode);
    echo str_replace("\n", "<br />", print_r($info, 1));
    ?></p>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=battle/pvp/search" class="bc-bot-open-btn"><?php echo getString("Search for enemy",$user->langcode); ?></a><br />
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=battle" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
