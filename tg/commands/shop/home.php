<?php
if ($text == $strings->shop->name) {
    $user = user($chat_id, $name);
    $reply_markup = [
        "keyboard" => [
            [
                [
                    "text" => $strings->shop->list_all
                ]
            ],
            [
                [
                    "text" => $strings->menu
                ]
            ]
        ],
        "resize_keyboard" => true,
        "one_time_keyboard" => true
    ];
    $client->sendMessage(
        $chat_id,
        "Welcome to <b>" . $strings->shop->name . "</b>",
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
}
