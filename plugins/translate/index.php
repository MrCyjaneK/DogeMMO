<?php

if (!isset($_GET['textname']) || !isset($_GET['language'])) {
    die("<script>window.location.href='<?= WEB ?>/game.php?action=translate/browse'</script>");
}

$textname = urldecode($_GET['textname']);
$language = urldecode($_GET['language']);


$query = $db->prepare("SELECT * FROM `translation` WHERE `textname`=:textname");
$textname_db = $textname;
$query->bindParam(":textname",$textname_db);
$query->execute();
$query = $query->fetchAll();

if ($query == []) {
    echo "<script>window.location.href='https://dogemmo.mrcyjanek.net/inc/soft_error.php?e=".urlencode(gs("This translation doesn't exist."))."'</script>";
    die();
}

$count = count($query);
?>
<div class="bc-content-wrap">
  <section class="bc-content">
      <div class="bc-post widget_frame_base">
<div class="tgme_widget_message js-widget_message no_userpic">
  <div class="tgme_widget_message_user"><a href="#" target="_blank"><i class="tgme_widget_message_user_photo bgcolor6" data-content="@"><img src="/img/flags/us.svg"></i></a></div>
  <div class="tgme_widget_message_bubble">
    <i class="tgme_widget_message_bubble_tail">
      <svg class="bubble_icon" width="9px" height="20px" viewBox="0 0 9 20">
        <g fill="none">
          <path class="background" fill="#ffffff" d="M8,1 L9,1 L9,20 L8,20 L8,18 C7.807,15.161 7.124,12.233 5.950,9.218 C5.046,6.893 3.504,4.733 1.325,2.738 L1.325,2.738 C0.917,2.365 0.89,1.732 1.263,1.325 C1.452,1.118 1.72,1 2,1 L8,1 Z"></path>
          <path class="border_1x" fill="#d7e3ec" d="M9,1 L2,1 C1.72,1 1.452,1.118 1.263,1.325 C0.89,1.732 0.917,2.365 1.325,2.738 C3.504,4.733 5.046,6.893 5.95,9.218 C7.124,12.233 7.807,15.161 8,18 L8,20 L9,20 L9,1 Z M2,0 L9,0 L9,20 L7,20 L7,20 L7.002,18.068 C6.816,15.333 6.156,12.504 5.018,9.58 C4.172,7.406 2.72,5.371 0.649,3.475 C-0.165,2.729 -0.221,1.464 0.525,0.649 C0.904,0.236 1.439,0 2,0 Z"></path>
        </g>
      </svg>
    </i>
    <div class="tgme_widget_message_author"><a class="tgme_widget_message_owner_name" href="#" target="_blank"><span dir="auto" class="name"><?php echo $textname; ?></span></a></div>
    
    
    
    <div class="tgme_widget_message_text js-message_text" dir="auto">You are about to translate <?php echo $textname; ?> to <?php echo $language; ?><br />Here is an english version of this text:<br /><?php echo getString($textname, "EN"); ?><br /></div>
    <div class="tgme_widget_message_footer js-message_footer">
      <div class="tgme_widget_message_info js-message_info">
      </div>
    </div>
  </div>
</div></div>
    <div class="bc-header-wrap">
      <h3 class="bc-header"><?php echo $count; ?> Translations</h3>
    </div>
    <div class="bc-comments"><div class="bc-comment" data-comment-id="2">
  <?php 
  foreach ($query as $q) {
    $owner = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $owner->bindParam(':id',$q['addedby']);
    $owner->execute();
    $owner = $owner->fetchObject();
  ?>
  <div class="bc-comment-box">
    <a href="#" class="bc-comment-photo bc-photo-chars bc4" data-content="<?php echo strtoupper($q['language']); ?>"></a>
    <div class="bc-comment-body">
      <div class="bc-comment-author-flex">
        <span class="bc-comment-author-name"><a dir="auto" class="name tc4" href="#"><?php echo $owner->username ?></a></span>
        <a href="#" class="bc-comment-date"><time><?php echo date('m/d/Y H:i:s', $q['submitat']); ?></time></a>
      </div>
      <div class="bc-comment-text">
        <?php echo $q['text']; ?>
        <br />
        <pre style="text-align: left;max-width: 540 px"><?php echo htmlspecialchars($q['text']); ?></pre>
        </div>
      <div class="bc-comment-footer">
        <span class="bc-comment-voting need_tt">
          <span class="bc-comment-like"><span class="value" data-value="0"></span></span>
          <span class="bc-comment-dislike"><span class="value" data-value="0"></span></span>
        </span>
        <span class="bc-comment-actions">
        </span>
      </div>    
    </div>
  </div>
  <?php } ?>
</div>
      <div class="bc-comments-footer">
    <div class="bc-comments-footer-content">
      <div class="bc-comment-form bc-new-comment-form">
        <div class="bc-comment-file-wrap shide"></div>
        <div class="bc-comment-form-box">
          <div class="bc-comment-input-wrap">
            <div class="bc-input-field">
              <div id="comment-content" class="input form-control bc-form-control bc-comment-input empty" data-name="text" data-value="" data-placeholder="Write translation" contenteditable="true"><br></div>
            </div>
          </div>
          <div class="bc-comment-submit-wrap">
            <button onclick="window.location.href='/game.php?action=translate/new&textname=<?php echo urlencode($textname); ?>&language=<?php echo urlencode($language); ?>&content='+encodeURI(document.getElementById('comment-content').innerText)" class="btn btn-primary bc-submit-comment-btn"></button>
          </div>
        </div>
      </div>
    </div>
  </div>
    <div class="bc-arrow-footer">
      <div class="bc-arrow-down ohide">
        <div class="bc-badge-wrap"><span class="bc-badge"></span></div>
      </div>
    </div>
  </section>
</div>
