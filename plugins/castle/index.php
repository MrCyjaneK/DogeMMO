<section>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo gs("🏰Castle"); ?></h1>
    <p><?php
    $info = htmlspecialchars($user->username) . gs("'s castle");
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <a style="width:49.5%; float: left; text-align: left;" href="<?= WEB; ?>/game.php?action=shop" ><?php echo getString("🏪AliÐoge",$user->langcode); ?></a>
    <a style="width:49.5%; float: right; text-align: right;" href="<?= WEB; ?>/game.php?action=bank" ><?php echo getString("🏦ReÐolut",$user->langcode); ?></a><br />
    <a style="width:33%; float: left; text-align: left;" href="<?= WEB; ?>/game.php?action=battle" ><?php echo getString("⚔️Battles",$user->langcode); ?></a>
    <a style="width:32%; float: center; text-align: center;" href="<?= WEB; ?>/game.php?action=tavern" ><?php echo getString("🍻Tavern",$user->langcode); ?></a>
    <a style="width:33%; float: right; text-align: right;" href="<?= WEB; ?>/game.php?action=guilds" ><?php echo getString("🏯Guilds",$user->langcode); ?></a><br />
    <a style="width:33%; text-align: center; float: center;" href="<?= WEB; ?>/game.php?action=start" ><?php echo getString("🏠Menu",$user->langcode); ?></a>
</section>
