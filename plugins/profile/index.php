<?php

$profile = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
$profile->bindParam(":id",$_GET['id']);
$profile->execute();
$profile = $profile->fetchAll();

if ($profile == []) {
    echo "<script>window.location.href='".WEB"./inc/soft_error.php?e=".urlencode("Invalid user")."'</script>";
    die();
}
$profile = $profile[0];
if (!empty($profile['teamid'])) {
    $teams = $db->prepare(
        "SELECT * FROM `teams` WHERE AND ID=:id"
    );
    $teams->bindParam(":id", $profile['teamid']);
    $teams->execute();
    $teams = $teams->fetchObject();
    $profile['username'] = $teams->emoji . " " . $profile['username'];
}

$datatoprint = [];

/*
$datatoprint[] = [
    'value',
    'short description (getString is used)'
];
*/
$datatoprint[] = [$profile['lvl'],'Level'];
$datatoprint[] = [floor($profile['xp']),'xp'];
$datatoprint[] = [floor($profile['hp']),'hp'];

?>
<section class="bc-main">
    <div class="bc-main-content">
        <div class="tgme_channel_info">
            <div class="tgme_channel_info_header">
                <i class="tgme_page_photo_image bgcolor3" data-content="U"><img height="160" width="160" src="/img/dogecoin.svg"></i>
                <div class="tgme_channel_info_header_title"><span dir="auto"><?php echo $profile['username']; ?></span></div>
                <!--<div class="tgme_channel_info_header_username"><a href="<?= WEB; ?>game.php?action=battle/pvp/fight&id=<?php echo $profile['id']; ?>"><?= gs('Attack') ?></a></div>-->
            </div>
            <div class="tgme_channel_info_counters">
<?php
foreach ($datatoprint as $key => $data) {
?>
        <div class="tgme_channel_info_counter">
            <span class="counter_value"><?php echo $data[0]; ?></span>
            <span class="counter_type"><?php echo getString($data[1], $user->langcode); ?></span>
        </div>
<?php 
if ((($key+1) % 4) == 0) {
    echo '<br /><br />';
}
?>
<?php } ?>

            </div>
            <div class="tgme_channel_info_description">TODO_USER_BIO</div>
            <a class="tgme_channel_download_telegram" href="<?= WEB; ?>game.php?action=battle/pvp/fight&id=<?php echo $profile['ID']; ?>">
            <?= gs('Attack') ?>
            </a>
            </div>
        </div>
    </div>
</section>
