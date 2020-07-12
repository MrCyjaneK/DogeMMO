<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Dice",$user->langcode); ?></h1>
    <p><?php
    $info = getString("I'm working on it. (Not really, pr are welcome)",$user->langcode);;
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <!-- Well.. I'm not, I'll <strike>soon</strike> sometime -->
    <a style="width:49.5%" onclick="window.history.back()" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
