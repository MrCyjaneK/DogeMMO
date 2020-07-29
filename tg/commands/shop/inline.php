<?php
include $__DIR__ . "/commands/shop/note.php";
if (isset($update->callback_query)) {
    $callback_id = $update->callback_query->id;
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
    $data = $update->callback_query->data;
    $user = user($chat_id);
    $exploded = explode(":", $data);
    //$client->debug($chat_id, $update);
    if ($exploded[0] == 'shop_show') {
        $inshop = "castle";
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->shop->name
                    ]
                ],
                [
                    [
                        "text" => $strings->menu
                    ]
                ]
            ],
            "resize_keyboard" => true
        ];
        $items = $db->prepare(
            "SELECT * FROM shop_items WHERE token=:token AND inshop=:inshop AND type=:type"
        );
        $items->bindParam(":type", $exploded[1]);
        $items->bindParam(":token", $token);
        $items->bindParam(":inshop", $inshop);
        $items->execute();
        $items = $items->fetchAll();
        foreach ($items as $key => $item) {
            if ($item["minlvl"] > $user->lvl) {
                unset($items[$key]);
            }
        }
        if ($items == []) {
            $client->sendMessage(
                $chat_id,
                "I'm sure that I've it, but you need to have more experience to get this."
            );
        }
        $menu = ['inline_keyboard' => []];
        foreach ($items as $item) {
            //$client->sendMessage($chat_id,print_r($item,1));
            $key = $item['ID'];
            $price = n($item["price"], 2) . " DOGE";
            if ($price == 0) {
                $price = "Free";
            }
            $menu['inline_keyboard'][] = [
                [
                    "text" => $item['emoji'] . " " . $item['name'],
                    "callback_data" => "item_show:$key"
                ],
                [
                    "text" => "Buy",
                    "callback_data" => "item_buy:$key"
                ]
            ];
        }
        $menu['inline_keyboard'][] = [
            [
                "text" => $strings->menu,
                "callback_data" => "home"
            ]
        ];
        $itemstring = $itemtypes[$exploded[1]] . " in " . $strings->shop->name;
        $client->deleteMessage($chat_id, $message_id);
        $client->sendMessage(
            $chat_id,
            $itemstring,
            "HTML",
            null,
            null,
            null,
            $menu
        );
        //$client->sendMessage($chat_id, "Interested in anything?","HTML");
    }
    if ($exploded[0] == 'item_show') {
        $id = $exploded[1];
        $item = $db->prepare(
            "SELECT * FROM `shop_items` WHERE ID=:id and TOKEN=:token"
        );
        $item->bindParam(":id", $id);
        $item->bindParam(":token", $token);
        $item->execute();
        $item = $item->fetchAll();
        if ($item == []) {
            $client->answerCallbackQuery(
                $callback_id,
                "We don't have this item in our " . $strings->bank->name,
                true
            );
            die();
        }
        $item = $item[0];
        $price = n($item['price']);
        if ($price == 0) {
            $price = "Free";
        }
        $itemstring =
            "" .
            $item["emoji"] .
            " " .
            $item["name"] .
            " [" .
            $taglist[$item["tag"]] .
            "]\n" .
            "⚔" .
            round($item["attack"], 2) .
            " 🛡️" .
            round($item["defense"], 2) .
            " 🏋‍♂" .
            round($item["weight"], 2) .
            "kg 🏃‍♂" .
            round($item["speed"], 2) .
            "\n" .
            "" .
            $item["emoji"] .
            " " .
            $itemtypes[$item["type"]] .
            "\n" .
            "💰 " .
            $price .
            "\n" .
            "❓" .
            $item["about"] .
            "";
        $client->answerCallbackQuery(
            $callback_id,
            substr(print_r($itemstring, 1), 0, 200),
            true
        );
    }
    if ($exploded[0] == 'item_buy') {
        $user = user($chat_id, $name);
        $wanttobuy = [
            "ID" => $exploded[1],
            "inshop" => 'castle'
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
            $client->answerCallbackQuery(
                $callback_id,
                "You don't have enough money, go to " .
                    $strings->bank->name .
                    " and deposit some.",
                true
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
            $client->answerCallbackQuery(
                $callback_id,
                "Sorry.. You can't have more than $totalcap " .
                    $wanttobuy['emoji'] .
                    $wanttobuy['name'] .
                    ".",
                true
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
        $client->answerCallbackQuery(
            $callback_id,
            "Purshased $one " .
                $wanttobuy['emoji'] .
                $wanttobuy['name'] .
                " for " .
                $wanttobuy['price'] .
                " " .
                $config->currency,
            true
        );
    }
}
