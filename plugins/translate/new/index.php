<section class="bc-main">
    <div class="bc-main-content">
    <img src="/img/dogecoin.svg" width="102" height="102">
    <h1>Submission</h1>
    <p><?php
    // Check if user have Translator(id 1) rank
    $check = $db->prepare("SELECT * FROM `user_achievements` WHERE user_id=:userid AND achievementid=1");
    $check->bindParam(":userid",$user->ID);
    $check->execute();
    $check = $check->fetchAll();
    if ($check == []) {
        $error_message = "You don't have 'Translation' badge yet.";
        echo "<script>window.location.href='https://dogemmo.mrcyjanek.net/inc/soft_error.php?e=".urlencode($error_message)."'</script>";
        die();
    }
    $info = "Translation submited!";
    $info = $_GET;
    $textname = htmlspecialchars_decode(urldecode($_GET['textname']));
    $language = htmlspecialchars_decode(urldecode($_GET['language']));
    // Substr is needed because from an End Of Line is sent in request.
    //edit: not anymore
    $content_init = htmlspecialchars_decode(substr(urldecode($_GET['content']),0));
    $content = strip_tags($content_init, '<a><p><b><i><pre><code>');
    if ($content != $content_init) {
        $error_message = "Malicious code detected! Submission removed.
You tried to add:
$content_init
But our system said that this is safe:
$content

Please remove all other tags than <p>,<b>,<i>,<pre>,<code>,<a>";
        $error_message = str_replace("\n", "<br />", htmlspecialchars(print_r($error_message, 1)));
        echo "<script>window.location.href='https://dogemmo.mrcyjanek.net/inc/soft_error.php?e=".urlencode($error_message)."'</script>";
        die();
    }
    $d = $db->prepare("DELETE FROM `translation` WHERE textname=:textname AND language=:language");
    $d->bindParam(":textname",$textname);
    $d->bindParam(":language",$language);
    $d->execute();
    $insert = $db->prepare("INSERT INTO `translation`(`textname`, `language`, `text`, `submitat`) VALUES (:textname, :language, :text, :submitat)");
    $insert->bindParam(":textname",$textname);
    $insert->bindParam(":language",$language);
    $time = time();
    $insert->bindParam(":submitat",$time);
    $insert->bindParam(":text",$content);
    $insert->execute();
    $info = "Added :)";
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <a style="width:49.5%" href="http://dogemmo.mrcyjanek.net/game.php?action=translate/browse/language&lang=<?php echo $language; ?>" class="bc-bot-open-btn">Go back</a>
  </div>
</section>