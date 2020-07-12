<?php
include "./plugins/shop/note.php"; ?>

<section>
    <div>
    <img src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Categories",$user->langcode);; ?></h1>
    <p><?php
    $info = getString("Here you can see all categories",$user->langcode);
    echo str_replace("\n", "<br />", print_r($info, 1));
    ?></p>

<?php foreach ($itemtypes as $key => $value) {
    $inshop = "castle";
    $items = $db->prepare(
        "SELECT * FROM shop_items WHERE inshop=:inshop AND type=:type"
    );
    $items->bindParam(":type", $key);
    $items->bindParam(":inshop", $inshop);
    $items->execute();
    $items = $items->fetchAll();
    foreach ($items as $key_ => $item) {
        if ($item["minlvl"] > $user->lvl) {
            unset($items[$key_]);
        }
    }
    if ($items == []) {
        continue;
    }
    //$text .= "/show_$key - $value\n";
    //window.history.back();
    echo '<a style="width:100%" href="'.WEB.'/game.php?action=shop/itemlist&inshop=' .
        $inshop .
        '&type=' .
        $key .
        '" class="bc-bot-open-btn">' .
        getString($value,$user->langcode,0,$value) .
        '</a><br />';
} ?>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=shop" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
