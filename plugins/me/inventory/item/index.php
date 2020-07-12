<?php
$it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
$it->execute();
$it = $it->fetchAll();
$itemtypes = [];
$taglist = [];
foreach ($it as $item) {
    $itemtypes[$item['ID']] = $item['type'];
    $itemtypes[$item['ID']] = $item['tag'];
}
$items = $db->prepare(
    "SELECT * FROM `user_items` WHERE `owned_by`=:owned_by AND `ID`=:ID"
);
$items->bindParam(":owned_by", $user->ID);
$items->bindParam(":ID", $_GET['id']);
$items->execute();
$items = $items->fetchAll();
$item = $items[0];
$shop_items = $db->prepare(
    "SELECT * FROM `shop_items` WHERE `ID`=:isi"
);
$shop_items->bindParam(":isi", $items[0]['in_shop_id']);
$shop_items->execute();
$shop_items = $shop_items->fetchAll();
$items_dex = [];
foreach ($shop_items as $shop_item) {
    $items_dex[$shop_item['ID']] = [];
    $items_dex[$shop_item['ID']] = $shop_item;
}
if ($items == []) {
    die(
        "<script>window.location.href = WEB.'/inc/soft_error.php?e='".urlencode(getString("ERR_INVALID_ITEM",$user->langcode))."'</script>"
    );
}
?>
<section>
    <div>
    <img src="<?= WEB; ?>/img/dogecoin.svg" width="102" height="102">
    <h1><?php echo gs($shop_items[0]['name']); ?></h1>
    <p><?php echo str_replace(
        "\n",
        "<br />",
        $items_dex[$item['in_shop_id']]['emoji'] .
            " " .
            gs($items_dex[$item['in_shop_id']]['name']) .
            "\n" .
            gs("Qty:") . " " .
            $item['capacity'] .
            "\n" .
            "<code>⚔" .
            round(
                $item["boost_attack"] +
                    $items_dex[$item['in_shop_id']]['attack'],
                2
            ) .
            " 🛡️" .
            round(
                $item['boost_defense'] +
                    $item_dex[$item['in_shop_id']]["defense"],
                2
            ) .
            " 🏋‍♂" .
            round($items_dex[$item['in_shop_id']]["weight"], 2) .
            "kg 🏃‍♂" .
            round(
                $item['boost_speed'] + $items_dex[$item['in_shop_id']]["speed"],
                2
            ) .
            "</code>\n" .
            "" .
            gs($items_dex[$item['in_shop_id']]['about'])
    ); ?></p>
	<?php
 if ($item['in_eq'] == 0 && $items_dex[$item['in_shop_id']]['can_eq'] == 1) {
     echo '<a style="width:100%" href="'.WEB.'/game.php?action=me/inventory/item/eq&id=' .
         $_GET['id'] .
         '" class="bc-bot-open-btn">'.getString("Equip",$user->langcode).'</a>';
 } else {
     echo '<a style="width:100%" href="'.WEB.'/game.php?action=me/inventory/item/eq&id=' .
         $_GET['id'] .
         '" class="bc-bot-open-btn">'.getString("Unequip",$user->langcode).'</a>';
 }
 echo '<a style="width:100%" href="'.WEB.'/game.php?action=me/inventory/" class="bc-bot-open-btn">'.getString("Go Back",$user->langcode).'</a>';
 ?>
  </div>
</section>
