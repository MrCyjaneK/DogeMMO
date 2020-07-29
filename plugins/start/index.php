<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?= gs("Home"); ?></h1>
    <p id='main_info'><i><?php
        $notifs = $user->notifications('unread');
        if (count($notifs) !== 0 ) {
            ?>
             <a href="<?= WEB ?>/game.php?action=me/notifications"><?= $notifs[0]['title'] ?></a>
             <?php
        } else {
            gs("You don't have any notifications!");
        }
    ?></i></p>
    <div/>
        <a style="width: 33%; float: left;  text-align: left;"   href="<?= WEB; ?>/game.php?action=castle" class="bc-bot-open-btn"><?php echo getString("🏰Castle",$user->langcode); ?></a>
        <!-- Can somebody center that element? -->
        <a style="" href="<?= WEB ?>/game.php?action=quest"><?php echo getString("🗺Quest",$user->langcode); ?></a>
        <a style="width: 33%; float: right; text-align: right;"  href="<?= WEB; ?>/game.php?action=social" class="bc-bot-open-btn"><?php echo getString("💬Social",$user->langcode); ?></a><br />
        <a style="width: 50%; float: left;  text-align: left;"   href="<?= WEB; ?>/game.php?action=me" class="bc-bot-open-btn"><?php echo getString("🎖Hero",$user->langcode); ?></a>
        <a style="width: 50%; float: right; text-align: right;"  href="<?= WEB; ?>/game.php?action=war" class="bc-bot-open-btn"><?php echo getString("⚔️ The Big War",$user->langcode); ?></a>
    </div>
</section>
