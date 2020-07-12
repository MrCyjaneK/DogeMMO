<section>
    <div>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Items",$user->langcode);?></h1></h1>
    <p><?php
    $it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
    $it->execute();
    $it = $it->fetchAll();
    $itemtypes = [];
    $taglist = [];
    foreach ($it as $item) {
        $itemtypes[$item['ID']] = $item['type'];
        $taglist[$item['ID']] = $item['tag'];
    }
    if (!isset($itemtypes[$_GET['id']])) {
        die(
            "<script>window.location.href = '".WEB."/inc/soft_error.php?e=".urlencode(getString("Oups! It looks like given category doesn't exist",$user->langcode))."'</script>"
        );
    }
    //echo getString($itemtypes[$_GET['type']],$user->langcode,0,$itemtypes[$_GET['type']]);
    ?></p>
    <?php
    $items = $db->prepare(
        "SELECT * FROM `user_items` WHERE `owned_by`=:owned_by"
    );
    $items->bindParam(":owned_by", $user->ID);
    $items->execute();
    $items = $items->fetchAll();
    $shop_items = $db->prepare(
        "SELECT * FROM `shop_items`"
    );
    $shop_items->execute();
    $shop_items = $shop_items->fetchAll();
    $items_dex = [];
    //$client->sendMessage($chat_id, "SHOPITEMS: ".substr(print_r($shop_items,1),0,4000));
    foreach ($shop_items as $shop_item) {
        $c = false;
        foreach ($items as $item) {
            if ($item['in_shop_id'] == $shop_item['ID']) {
                $c = true;
            }
        }
        if (!$c) {
            continue;
        }
        $items_dex[$shop_item['ID']] = [];
        $items_dex[$shop_item['ID']] = $shop_item;
    }
    $a = ['', '✅'];
    foreach ($items as $item) {
        if ($items_dex[$item['in_shop_id']]['type'] == $_GET['id']) {
            echo '<a style="width:100%" href="'.WEB.'/game.php?action=me/inventory/item&id=' .
                $item['ID'] .
                '" >' .
                $a[$item['in_eq']] .
                $items_dex[$item['in_shop_id']]['emoji'] .
                getString($items_dex[$item['in_shop_id']]['name']) .
                '</a> <br />';
        }
    }
    ?>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=me/inventory" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode);?></a>
  </div>
</section>
