<!-- If you see it, fix it. -->
<!--
<script>
  var username = "<?php echo htmlspecialchars($user->username); ?>";
  const http = new XMLHttpRequest();
  const url=document.location.protocol+"//"+window.location.host+'/ajax/wartime.php';
  setInterval(() => {
    http.open("GET", url);
    http.send();
  },1000);

  http.onreadystatechange = (e) => {
    if (http.readyState == 4 && http.status == 200) {
      document.getElementById('main_info').innerHTML = 'Hello <b>'+username+"</b> Time to next war: <br />"+http.responseText;
    }
  }
</script>
-->
<section class="bc-main">
    <div class="bc-main-content">
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?= gs("Home"); ?></h1>
    <p id='main_info'><i><?= gs("[WIP] Loading..."); ?></i></p>
    <!-- That loading should display some stats about user.. or idk -->
    <div/>
        <a style="width: 33%; float: left;  text-align: left;"   href="<?= WEB; ?>/game.php?action=castle" class="bc-bot-open-btn"><?php echo getString("🏰Castle",$user->langcode); ?></a>
        <!-- Can somebody center that element? -->
        <a style="" href="<?= WEB ?>/game.php?action=quest" class="bc-bot-open-btn"><?php echo getString("🗺Quest",$user->langcode); ?></a>
        <a style="width: 33%; float: right; text-align: right;"  href="<?= WEB; ?>/game.php?action=social" class="bc-bot-open-btn"><?php echo getString("💬Social",$user->langcode); ?></a><br />
        <a style="width: 50%; float: left;  text-align: left;"   href="<?= WEB; ?>/game.php?action=me" class="bc-bot-open-btn"><?php echo getString("🎖Hero",$user->langcode); ?></a>
        <a style="width: 50%; float: right; text-align: right;"  href="<?= WEB; ?>/game.php?action=war" class="bc-bot-open-btn"><?php echo getString("⚔️ The Big War",$user->langcode); ?></a>
    </div>
</section>
