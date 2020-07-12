<?php
include "./plugins/shop/note.php"; ?>

<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php gs("🏪AliÐoge") ?></h1>

<?php
$item = $db->prepare(
    "SELECT * FROM `shop_items` WHERE ID=:id AND inshop=:inshop"
);
$item->bindParam(":id", $_GET['id']);
$item->bindParam(":inshop", $_GET['inshop']);
$item->execute();
$item = $item->fetchAll();

if ($item == []) {
    //$client->answerCallbackQuery($callback_id,"We don't have this item in our ".$strings->bank->name,true);
    echo str_replace(
        "\n",
        "<br />",
        getString("I'm sorry but we don't have this item in",$user->langcode)." " . gs("🏪AliÐoge")
    );
} else {
    $wanttobuy = [
        "ID" => $_GET['id'],
        "inshop" => $_GET['inshop']
    ];
    $useritems = $db->prepare(
        "SELECT * FROM `user_items` WHERE owned_by=:owned AND in_shop_name=:in_shop_name"
    );
    $useritems->bindParam(":owned", $user->ID);
    $useritems->bindParam(":in_shop_name", $wanttobuy['inshop']);
    $useritems->execute();
    $useritems = $useritems->fetchAll();
    //$client->sendMessage($chat_id, "Your Items: ".print_r($useritems,1),"HTML",null,null,null);
    $item_to_buy = $db->prepare(
        "SELECT * FROM `shop_items` WHERE ID=:id AND inshop=:inshop"
    );
    $item_to_buy->bindParam(":id", $wanttobuy['ID']);
    $item_to_buy->bindParam(":inshop", $wanttobuy['inshop']);
    $item_to_buy->execute();
    $item_to_buy = $item_to_buy->fetchAll();
    foreach ($item_to_buy[0] as $key => $part) {
        if (!is_numeric($key)) {
            $wanttobuy[$key] = $part;
        }
    }
    //$client->sendMessage($chat_id, "You want to buy: ".print_r($wanttobuy,1),"HTML",null,null,null);
    // Proceed to buy
    if ($user->balance < $wanttobuy['price']) {
        $e = getString("You don't have enough money!",$user->langcode);
    }
    // Check if own_limit is not exceded
    $check = $db->prepare(
        "SELECT * FROM `user_items` WHERE in_shop_id=:in_shop_id AND owned_by=:owned_by"
    );
    $check->bindParam(":in_shop_id", $wanttobuy["ID"]);
    $check->bindParam(":owned_by", $user->ID);
    $check->execute();
    $check = $check->fetchAll();
    $totalcap = 0;
    foreach ($check as $item_to_check) {
        //$client->sendMessage($chat_id, ".".print_r($itemtocheck,1));
        $totalcap += $item_to_check['capacity'];
    }
    if ($totalcap => $wanttobuy['own_limit']) {
        $e =
            getString("You are trying to buy",$user->langcode).
            " $totalcap " .
            $wanttobuy['emoji'] .
            gs($wanttobuy['name']) .
            " " . gs("and that's too much!");
    }

    if (!isset($e)) {
        $insert_items = $db->prepare("INSERT INTO `user_items`(`in_shop_id`, `owned_by`, `capacity`, `in_shop_name`) VALUES (:in_shop_id, :owned_by, :capacity, :in_shop_name)");
        $insert_items->bindParam(":in_shop_id", $wanttobuy["ID"]);
        $insert_items->bindParam(":owned_by", $user->ID);
        $one = 1;
        $insert_items->bindParam(":capacity", $one);
        $insert_items->bindParam(":in_shop_name", $wanttobuy["inshop"]);
        $insert_items->execute();

        $update_user = $db->prepare(
            "UPDATE `users` SET `balance`=`balance`-:price WHERE ID=:id"
        );
        $update_user->bindParam(":price", $wanttobuy["price"]);
        $update_user->bindParam(":id", $user->ID);
        $update_user->execute();
        //$client->sendMessage($chat_id, "Purshased $one ".$wanttobuy['emoji'].$wanttobuy['name']." for ".$wanttobuy['price']." ".$config->currency);

        $item = $item[0];
        $price = n($item['price']);
        if ($price == 0) {
            $price = getString("Free!",$user->langcode);
        }
        $itemstring =
            getString("You have purchased",$user->langcode) . " " .
            $item["emoji"] .
            " " .
            getString($item['name']) .
            " " . getString("for",$user->langcode) . " " .
            $price .
            " " .
            $config->currency;
    } else {
        $itemstring = $e;
    }
    echo str_replace("\n", "<br />", $itemstring);
}
?>
    <a style="width:100%" onclick="window.history.back()" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
