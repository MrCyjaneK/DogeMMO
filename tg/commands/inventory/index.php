<?php
/*
  Purpose: Display user's items
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($text == "/inv") {
        $user = user($chat_id, $name);
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
        foreach ($items as $item) {
            //$client->sendMessage($chat_id, "Items as Item: ".substr(print_r($items_dex[$item['in_shop_id']]["speed"],1),0,4000));
            $eq_or_no = [
                /* 0 */ "Is <b>not</b> in your eq /changeit_" . $item["ID"],
                /* 1 */ "Is already in your eq /changeit_" . $item["ID"]
            ];
            $reply_text =
                $items_dex[$item['in_shop_id']]['emoji'] .
                " " .
                $items_dex[$item['in_shop_id']]['name'] .
                "\n" .
                "Qty: " .
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
                    $item['boost_speed'] +
                        $items_dex[$item['in_shop_id']]["speed"],
                    2
                ) .
                "</code>\n" .
                $eq_or_no[$item['in_eq']] .
                "\n" .
                "Sell: /sell_" .
                $item["ID"] .
                " 1";
            $client->sendMessage(
                $chat_id,
                $reply_text,
                "HTML",
                null,
                null,
                null,
                $reply_markup
            );
        }
        $client->sendMessage(
            $chat_id,
            "Check this out: http://dogemmo.mrcyjanek.net (better /inv)"
        );
    }
}
