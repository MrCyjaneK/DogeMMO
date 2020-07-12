<section class="bc-main">
    <div class="bc-main-content">
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Deposit",$user->langcode); ?></h1>
    <p><?php
    $info = getString("Here you can deposit some Dogecoin into game. Better don't",$user->langcode);
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <code style="font-size:16px;"><?php echo $user->depositaddress; ?></code>
    <a style="width:100%" href="<?php echo strtolower(
        $config->currency
    ); ?>coin:<?php echo $user->depositaddress; ?>?label=DogeMMO" class="bc-bot-open-btn"><?php echo getString("Open wallet",$user->langcode); ?></a><br />
    <a style="width:100%" href="<?= WEB ?>/game.php?action=bank" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
