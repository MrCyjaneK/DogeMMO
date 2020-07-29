<?php
$boomed = explode("@", $text);
if ($boomed[0] == $strings->shop->list_all) {
    $text = "Which stuff do you want?\n";
    $menu = ['inline_keyboard' => []];
    foreach ($itemtypes as $key => $value) {
        $inshop = "castle";
        $items = $db->prepare(
            "SELECT * FROM shop_items WHERE token=:token AND inshop=:inshop AND type=:type"
        );
        $items->bindParam(":type", $key);
        $items->bindParam(":token", $token);
        $items->bindParam(":inshop", $inshop);
        $items->execute();
        $items = $items->fetchAll();
        foreach ($items as $key_ => $item) {
            if ($item["minlvl"] > $user->lvl) {
                unset($items[$key_]);
            }
        }
        if ($items == []) {
            continue;
        }
        //$text .= "/show_$key - $value\n";
        $menu['inline_keyboard'][] = [
            [
                "text" => "$value",
                "callback_data" => "shop_show:$key"
            ]
        ];
    }
    $menu['inline_keyboard'][] = [
        [
            "text" => $strings->menu,
            "callback_data" => "home"
        ]
    ];
    $client->sendMessage($chat_id, $text, "HTML", null, null, null, $menu);
}
$boomed = explode("_", $text);
if ($boomed[0] == "/show") {
    if (count($boomed) == 2) {
        $inshop = "castle";
    } else {
        $inshop = $boomed[1];
    }
    $user = user($chat_id, $name);
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
    $client->sendMessage(
        $chat_id,
        "Items in <b>" . $strings->shop->name . "</b>",
        "HTML"
    );
    $items = $db->prepare(
        "SELECT * FROM shop_items WHERE token=:token AND inshop=:inshop AND type=:type"
    );
    $items->bindParam(":type", $boomed[1]);
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
    foreach ($items as $item) {
        //$client->sendMessage($chat_id,print_r($item,1));
        $price = n($item["price"], 2) . " DOGE";
        if ($price == 0) {
            $price = "Free";
        }
        $itemstring =
            "<b>" .
            $item["emoji"] .
            " " .
            $item["name"] .
            "</b> <code>[" .
            $taglist[$item["tag"]] .
            "]</code>\n" .
            "<code>⚔" .
            round($item["attack"], 2) .
            " 🛡️" .
            round($item["defense"], 2) .
            " 🏋‍♂" .
            round($item["weight"], 2) .
            "kg 🏃‍♂" .
            round($item["speed"], 2) .
            "</code>\n" .
            "<code>🏅</code> <b>Required level:</b> " .
            $item["minlvl"] .
            "\n" .
            "<code>" .
            $item["emoji"] .
            "</code> <b>" .
            $itemtypes[$item["type"]] .
            "</b>\n" .
            "<code>💰</code> <b>" .
            $price .
            "</b>\n" .
            "❓<i>" .
            $item["about"] .
            "</i>\n" .
            "<b>Buy:</b> /buy_$inshop" .
            "_" .
            $item["ID"];
        $client->sendMessage($chat_id, $itemstring, "HTML");
    }
    $client->sendMessage(
        $chat_id,
        "Interested in anything?",
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
}
