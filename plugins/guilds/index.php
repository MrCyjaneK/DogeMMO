<section class="bc-main">
    <div class="bc-main-content">
    <img src="<?= WEB ?>/img/dogecoin.svg" width="102" height="102">
    <h1><?php echo getString("Under construction.",$user->langcode); ?></h1>
    <p><?php
    $info = getString("I'm working on this... But I don't have much time. Plz PR",$user->langcode);;
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <!-- Well.. I'm not, I'll soon (dice first)-->
    <a style="width:49.5%" onclick="window.history.back()" class="bc-bot-open-btn">Go back</a>
  </div>
</section>
