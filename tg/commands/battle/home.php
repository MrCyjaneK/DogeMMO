<?php
if ($text == $strings->battle->name) {
    $reply_markup = [
        "keyboard" => [
            [
                [
                    "text" => $strings->battle->PVP->name
                ] //,
                //[
                //	"text" => $strings->battle->guild->name
                //]
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
        "Welcome to <b>" . $strings->battle->name . "</b>",
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
}
