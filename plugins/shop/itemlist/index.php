<?php
include "./plugins/shop/note.php"; ?>

<section>
    <div>
    <img src="<?= WEB ?>/img/dogecoin.svg" width="102" height="102">
    <h1><?php echo getString("🏪AliÐoge",$user->langcode); ?></h1>

<?php
$inshop = 'castle';
$items = $db->prepare(
    "SELECT * FROM shop_items WHERE inshop=:inshop AND type=:type"
);
$items->bindParam(":type", $_GET['type']);
$items->bindParam(":inshop", $_GET['inshop']);
$items->execute();
$items = $items->fetchAll();
//print_r($items);
foreach ($items as $key => $item) {
    if ($item["minlvl"] > $user->lvl) {
        unset($items[$key]);
    }
}
if ($items == []) {
    echo "<script>window.location.href='".WEB."/inc/soft_error.php?e=".urlencode(getString("ERR_LEVEL_NOT_ENOUGH",$user->langcode))."'</script>";
}
foreach ($items as $item) {
    //$client->sendMessage($chat_id,print_r($item,1));
    $key = $item['ID'];
    $price = n($item["price"], 2) . " DOGE";
    if ($price == 0) {
        $price = getString("Free!",$user->langcode);
    }
}
$itemstring = gs($itemtypes[$_GET['type']]) . " ".getString("in",$user->langcode)." " . gs("🏪AliÐoge");
echo '<p>' . $itemstring . '</p>';

foreach ($items as $item) {
    echo '<a style="width:100%" href="'.WEB.'/game.php?action=shop/itemshow&id=' .
        $item['ID'] .
        '&inshop=' .
        $item['inshop'] .
        '" class="bc-bot-open-btn">' .
        $item['emoji'] .
        gs($item['name']) .
        '</a><br />';
}
?>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=shop/showall" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
