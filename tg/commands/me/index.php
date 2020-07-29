<?php
/*
  Purpose: Welcome user, display minimal menu.
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($text == "/me") {
        $user = user($chat_id, $name);
        $items = $db->prepare(
            "SELECT * FROM `user_items` WHERE `token`=:token AND `owned_by`=:owned_by"
        );
        $items->bindParam(":token", $token);
        $items->bindParam(":owned_by", $user->ID);
        $items->execute();
        $items = $items->fetchAll();
        $shop_items = $db->prepare(
            "SELECT * FROM `shop_items` WHERE `token`=:token"
        );
        $shop_items->bindParam(":token", $token);
        $shop_items->execute();
        $shop_items = $shop_items->fetchAll();
        $items_dex = [];
        //$client->sendMessage($chat_id, "SHOPITEMS: ".substr(print_r($shop_items,1),0,4000));
        foreach ($shop_items as $shop_item) {
            //->sendMessage($chat_id, print_r($items_dex,1),"HTML",null,null,null,$reply_markup);
            $items_dex[$shop_item['ID']] = [];
            $items_dex[$shop_item['ID']] = $shop_item;
        }
        //$client->sendMessage($chat_id, "DEX: ".print_r($items_dex,1),"HTML",null,null,null,$reply_markup);
        $attack = 0;
        $defense = 0;
        $weight = 0;
        $speed = 0;
        foreach ($items as $item) {
            //$client->sendMessage($chat_id, "Items as Item: ".substr(print_r($items_dex[$item['in_shop_id']]["speed"],1),0,4000));
            if ($item['in_eq']) {
                $attack += round(
                    ($item["boost_attack"] +
                        $items_dex[$item['in_shop_id']]['attack']) *
                        $item['capacity'],
                    2
                );
                $defense += round(
                    ($item['boost_defense'] +
                        $item_dex[$item['in_shop_id']]["defense"]) *
                        $item['capacity'],
                    2
                );
                $weight += round(
                    $items_dex[$item['in_shop_id']]["weight"] *
                        $item['capacity'],
                    2
                );
                $speed += round(
                    ($item['boost_speed'] +
                        $items_dex[$item['in_shop_id']]["speed"]) *
                        $item['capacity'],
                    2
                );
            }
        }
        //"<code>⚔".round($item["boost_attack"]+$items_dex[$item['in_shop_id']]['attack'],2)." 🛡️".round($item['boost_defense']+$item_dex[$item['in_shop_id']]["defense"],2)." 🏋‍♂".round($items_dex[$item['in_shop_id']]["weight"],2)."kg 🏃‍♂".round($item['boost_speed']+$items_dex[$item['in_shop_id']]["speed"],2)."</code>\n".
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->menu
                    ]
                ]
            ],
            "resize_keyboard" => true
        ];
        //$client->sendMessage($chat_id, "Hello <b>".print_r($user,1)."</b> /me","HTML",null,null,null,$reply_markup);
        $client->sendMessage(
            $chat_id,
            "🐒<b>Username:</b> <code>" .
                $user->username .
                "</code> /name\n" .
                "🏆<b>XP:</b> " .
                round($user->xp, 2) .
                "\n" .
                "🎖<b>Level:</b> " .
                $user->lvl .
                "\n" .
                "💙<b>HP:</b>" .
                round($user->hp, 2) .
                "\n" .
                "💰<b>Balance:</b> " .
                n($user->balance) .
                " " .
                $config->currency .
                "\n" .
                "<code>⚔" .
                round($attack, 2) .
                " 🛡️" .
                round($defense, 2) .
                " 🏋‍♂" .
                round($weight, 2) .
                "kg 🏃‍♂" .
                round($speed, 2) .
                "</code> /inv\n" .
                "\n",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
}
