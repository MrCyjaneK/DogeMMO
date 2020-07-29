<?php
$exploded = explode("_", $text);
if ($exploded[0] == "/buy") {
    $user = user($chat_id, $name);
    $wanttobuy = [
        "ID" => $exploded[2],
        "inshop" => $exploded[1]
    ];
    $useritems = $db->prepare(
        "SELECT * FROM `user_items` WHERE owned_by=:owned AND token=:token AND in_shop_name=:in_shop_name"
    );
    $useritems->bindParam(":owned", $user->ID);
    $useritems->bindParam(":token", $token);
    $useritems->bindParam(":in_shop_name", $wanttobuy['inshop']);
    $useritems->execute();
    $useritems = $useritems->fetchAll();
    //$client->sendMessage($chat_id, "Your Items: ".print_r($useritems,1),"HTML",null,null,null);
    $item_to_buy = $db->prepare(
        "SELECT * FROM `shop_items` WHERE ID=:id AND token=:token AND inshop=:inshop"
    );
    $item_to_buy->bindParam(":id", $wanttobuy['ID']);
    $item_to_buy->bindParam(":token", $token);
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
        $client->sendMessage(
            $chat_id,
            "Uh... You don't have enough money...",
            "HTML",
            null,
            null,
            null
        );
        die();
    }
    // Check if own_limit is not exceded
    $check = $db->prepare(
        "SELECT * FROM `user_items` WHERE in_shop_id=:in_shop_id AND owned_by=:owned_by AND token=:token"
    );
    $check->bindParam(":in_shop_id", $wanttobuy["ID"]);
    $check->bindParam(":owned_by", $user->ID);
    $check->bindParam(":token", $token);
    $check->execute();
    $check = $check->fetchAll();
    $totalcap = 0;
    foreach ($check as $item_to_check) {
        //$client->sendMessage($chat_id, ".".print_r($itemtocheck,1));
        $totalcap += $item_to_check['capacity'];
    }
    if ($totalcap >= $wanttobuy['own_limit']) {
        $client->sendMessage(
            $chat_id,
            "Sorry.. You can't have more than $totalcap " .
                $wanttobuy['emoji'] .
                $wanttobuy['name'] .
                "."
        );
        die();
    }
    // Można? Można
    $insert_items = $db->prepare(
        "INSERT INTO `user_items`(`in_shop_id`, `owned_by`, `capacity`, `token`, `in_shop_name`) VALUES (:in_shop_id, :owned_by, :capacity, :token, :in_shop_name)"
    );
    $insert_items->bindParam(":in_shop_id", $wanttobuy["ID"]);
    $insert_items->bindParam(":owned_by", $user->ID);
    $one = 1;
    $insert_items->bindParam(":capacity", $one);
    $insert_items->bindParam(":token", $token);
    $insert_items->bindParam(":in_shop_name", $wanttobuy["inshop"]);
    $insert_items->execute();

    $update_user = $db->prepare(
        "UPDATE `users` SET `balance`=`balance`-:price WHERE token=:token AND ID=:id"
    );
    $update_user->bindParam(":price", $wanttobuy["price"]);
    $update_user->bindParam(":token", $token);
    $update_user->bindParam(":id", $user->ID);
    $update_user->execute();
    $client->sendMessage(
        $chat_id,
        "Purshased $one " .
            $wanttobuy['emoji'] .
            $wanttobuy['name'] .
            " for " .
            $wanttobuy['price'] .
            " " .
            $config->currency
    );
}
