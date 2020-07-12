<?php
$it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
$it->execute();
$it = $it->fetchAll();
$itemtypes = [];
$taglist = [];
foreach ($it as $item) {
    $itemtypes[$item['ID']] = $item['type'];
    $taglist[$item['ID']] = $item['tag'];
}
$items = $db->prepare(
    "SELECT * FROM `user_items` WHERE `token`=:token AND `owned_by`=:owned_by AND `ID`=:ID"
);
$items->bindParam(":token", $token);
$items->bindParam(":owned_by", $user->ID);
$items->bindParam(":ID", $_GET['id']);
$items->execute();
$items = $items->fetchAll();
$item = $items[0];
$shop_items = $db->prepare(
    "SELECT * FROM `shop_items` WHERE `token`=:token AND `ID`=:isi"
);
$shop_items->bindParam(":isi", $items[0]['in_shop_id']);
$shop_items->bindParam(":token", $token);
$shop_items->execute();
$shop_items = $shop_items->fetchAll();
$items_dex = [];
foreach ($shop_items as $shop_item) {
    $items_dex[$shop_item['ID']] = [];
    $items_dex[$shop_item['ID']] = $shop_item;
    if ($shop_item['can_eq'] == 0) {
        die(
            "<script>window.location.href = '<?= WEB ?>/inc/soft_error.php?e=".urlencode(getString("You can't equip this item...",$user->langcode))."'</script>"
        );
    }
}
if ($items == []) {
    die(
        "<script>window.location.href = '<?= WEB ?>/inc/soft_error.php?e=".urlencode(getString("Invalid item provided",$user->langcode))."'</script>"
    );
}
$check = $items;
$info = "";
if ($check[0]['in_eq'] != 1) {
    $allitems = $db->prepare(
        "SELECT * FROM `shop_items` WHERE type=:type"
    );
    $allitems->bindParam(":type", $shop_items[0]['type']);
    $allitems->execute();
    foreach ($allitems as $i) {
        $c = $db->prepare(
            "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND in_shop_id=:in_shop_id"
        );
        $c->bindParam(":owned_by", $user->ID);
        $c->bindParam(":in_shop_id", $i['ID']);
        $c->execute();
        $c = $c->fetchAll();
        if ($c[0]['in_eq'] == 1) {
            $u = $db->prepare("UPDATE user_items SET `in_eq`=0 WHERE ID=:id");
            $u->bindParam(":id", $c[0]['ID']);
            $u->execute();
            $info .= getString("Unequipped",$user->langcode)." " . print_r($i['name'], 1) . ".\n";
        }
    }

    $update_eq = $db->prepare(
        "UPDATE user_items SET `in_eq`=:in_eq WHERE ID=:id"
    );
    $update_eq->bindParam(":id", $_GET['id']);
    $true = 1;
    $update_eq->bindParam(":in_eq", $true);
    $update_eq->execute();
    $info .= getString("Equipped!",$user->langcode)." " . $items_dex[$item['in_shop_id']]['name'];
} else {
    $update_eq = $db->prepare(
        "UPDATE user_items SET `in_eq`=:in_eq WHERE ID=:id"
    );
    $update_eq->bindParam(":id", $_GET['id']);
    $false = 0;
    $update_eq->bindParam(":in_eq", $false);
    $update_eq->execute();
    $info .= getString("Unequipped",$user->langcode)." " . $items_dex[$item['in_shop_id']]['name'];
}
?>
<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo $shop_items[0]['name']; ?></h1>
    <p><?php echo str_replace("\n", "<br />", $info); ?></p>
	<?php echo '<a style="width:100%" href="'.WEB.'/game.php?action=me/inventory/item/&id=' .
     $_GET['id'] .
     '" class="bc-bot-open-btn">'.getString("Go Back",$user->langcode).'</a>'; ?>
  </div>
</section>
