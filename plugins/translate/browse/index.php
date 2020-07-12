
<section class="tgme_main">
    <div class="bc-main-content">
<?php





$languages = $db->prepare("SELECT DISTINCT `language` FROM translation");
$languages->execute();
$languages = $languages->fetchall();

$translations = $db->prepare("SELECT `textname` FROM `translation` WHERE language='EN'");
$translations->execute();
$translations = $translations->fetchall();

foreach ($translations as $t) {
    foreach ($languages as $l) {
        getString($t['textname'],$l['language']);
    }
}

$total_langs = count($languages);
//$total_translations = $db->prepare("SELECT count(`language`) as total FROM translation WHERE language='EN'");
//$total_translations->execute();
//$total_translations = $total_translations->fetchAll()['total'];
$total_translations = count($translations);
foreach ($languages as $lang) { 
    $language = $lang['language'];

    $langcount = $db->prepare("SELECT count(`language`) AS count FROM `translation` WHERE language=:lang AND SUBSTR(text,1,35)!='++++ THIS STRING IS NOT TRANSLATED,'");
    $langcount->bindParam(":lang", $language);
    $langcount->execute();
    $langcount = $langcount->fetchall()[0]['count'];
?>
    <div class="tgme_widget_message force_userpic js-widget_message">
      <div class="tgme_widget_message_bubble">
        <div class="tgme_widget_message_author"><a class="tgme_widget_message_owner_name" href="/game.php?action=translate/browse/language&lang=<?php echo urlencode($language); ?>"><span dir="auto"><?php echo $language; ?></span></a>
        </div>
        <div class="tgme_widget_message_text js-message_text before_footer" dir="auto">
            <img src="http://progress.mrcyjanek.net/?progress=<?php echo $langcount; ?>&title=Translation%20to%20<?php echo urlencode($language); ?>&suffix=%20of%20<?php echo $total_translations; ?>&scale=<?php echo $total_translations; ?>">
            <br />
            <a href="/game.php?action=translate/browse/language&lang=<?php echo urlencode($language); ?>">Translate</a>
            <span style="display: inline-block; width: 90px;"></span></div>
        <div class="tgme_widget_message_footer compact js-message_footer">
       </div>
      </div>
    </div>
    <br />
<?php } ?>
</div>
</section>