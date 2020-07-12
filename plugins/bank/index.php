<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("🏦ReÐolut",$user->langcode); ?></h1>
    <p><?php
    $info = getString("Welcome to 🏦ReÐolut! How may I help you?",$user->langcode);
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <a style="width:49.5%" href="<?= WEB ?>/game.php?action=bank/deposit" class="bc-bot-open-btn"><?php echo getString("Deposit",$user->langcode); ?></a>
    <a style="width:49.5%" href="<?= WEB ?>/game.php?action=bank/withdraw" class="bc-bot-open-btn"><?php echo getString("Withdraw",$user->langcode); ?></a><br />
    <a style="width:100%" onclick="<?= WEB ?>/game.php?action=castle" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
