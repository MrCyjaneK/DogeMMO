<section class="bc-main">
    <div class="bc-main-content">
    <img src="<?= WEB; ?>/img/dogecoin.svg" width="102" height="102">
    <h1><?= gs('You') ?></h1></h1>
    <p></p>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=me/inventory" class="bc-bot-open-btn"><?php echo getString("Inventory",$user->langcode); ?></a>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=start" class="bc-bot-open-btn"><?php echo getString("Go back",$user->langcode);?></a>
  </div>
</section>
