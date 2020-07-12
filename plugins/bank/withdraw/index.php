<section class="bc-main">
    <div class="bc-main-content">
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Withdraw",$user->langcode); ?></h1>
    <p><?php
    $info = getString("Withdrawals are not supported (Yet!)",$user->langcode);
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <a style="width:100%" onclick="window.history.back()" class="bc-bot-open-btn"><?php echo getString("Go Back.",$user->langcode); ?></a>
  </div>
</section>
