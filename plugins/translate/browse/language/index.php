<section class="tgme_main">
    <div class="bc-main-content">
<?php
if (!isset($_GET['lang'])) {
    die('No lang');
}
$lang = $_GET['lang'];
$l = intval($_GET['l']); //Sql injection yay!
$query = $db->prepare("SELECT * FROM `translation` WHERE language=:language ORDER BY ID DESC LIMIT $l,15");
$query->bindParam(":language",$lang);
$query->execute();
$query = $query->fetchAll();
if ($query == []) {
    echo "<script>window.location.href='https://dogemmo.mrcyjanek.net/inc/soft_error.php?e=".urlencode("This language doesn't exist.")."'</script>";
    die();
}

foreach ($query as $translation) {
?>

    <div class="tgme_widget_message force_userpic js-widget_message">
      <div class="tgme_widget_message_bubble">
        <div class="tgme_widget_message_author"><a class="tgme_widget_message_owner_name" href="/game.php?action=translate&textname=<?php echo urlencode($translation['textname']); ?>&language=<?php echo urlencode($translation['language']); ?>"><span dir="auto"><?php echo htmlspecialchars($translation['textname']); ?></span></a>
        </div>
        <div class="tgme_widget_message_text js-message_text before_footer" dir="auto">
            <pre style="text-align: left;max-width: 540 px"><?php echo str_replace("\n", '<br />', htmlentities($translation['text'])); ?></pre>
            <br />
            <span style="display: inline-block; width: 90px;"></span></div>
        <div class="tgme_widget_message_footer compact js-message_footer">
       </div>
      </div>
    </div>
    <br />

<?php 
}
?>
<a href="/game.php?action=translate/browse/language&lang=<?php echo $_GET['lang']; ?>&l=<?php echo ($_GET['l'] - 15); ?>">Prev</a>
<a href="game.php?action=translate/browse/language&lang=<?php echo $_GET['lang']; ?>&l=<?php echo ($_GET['l'] + 15); ?>">Next</a>
</div>
</section>
