<section">
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("🏪AliÐoge",$user->langcode); ?></h1>
    <p><?php
    $info = gs("Categories in") . " " . gs($strings->shop->name);
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=shop/showall" class="bc-bot-open-btn"><?php echo getString("Categories",$user->langcode);; ?></a><br />
    <a style="width:100%" href="<?= WEB ?>/game.php?action=castle" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
