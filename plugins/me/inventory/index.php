<section>
    <div>
    <img src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("Inventory",$user->langcode); ?></h1>
    <?php
    $items = $user->items();
    $shop_items_class = new shop_items();
    $shop_items = $shop_items_class->all();
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
    $it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
    $it->execute();
    $it = $it->fetchAll();
    $itemtypes = [];
    $taglist = [];
    foreach ($it as $item) {
        $itemtypes[$item['ID']] = $item['type'];
        $taglist[$item['ID']] = $item['tag'];
    }
    $oneitem = [];
    foreach ($itemtypes as $key => $value) {
        $a = false;
        foreach ($items_dex as $item) {
            if ($item['type'] == $key) {
                $a = true;
            }
        }
        if (!$a) {
            continue;
        }
        $oneitem[$key] = $value;
    }
    //print_r($oneitem);
    foreach ($oneitem as $key => $value) {
        echo '<a style="width:100%" href="'.WEB.'/game.php?action=me/inventory/category&id=' .
            $key .
            '">' .
            getString($value,$user->langcode) .
            '</a>';
    }
    ?>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=me" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
