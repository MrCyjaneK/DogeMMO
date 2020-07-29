<?php
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
}
