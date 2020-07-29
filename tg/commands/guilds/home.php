<?php
if ($text == $strings->guilds->name) {
    $user = user($chat_id);
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

    if ($user->lvl < 1) {
        $client->sendMessage(
            $chat_id,
            "Being in a Guild is not that easy, you should be at least lvl 1.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        die();
    }
    if ($user->in_guild_id == 0) {
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->guilds->join
                    ],
                    [
                        "text" => $strings->guilds->new
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
            "You don't belong to any of existing guilds.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        die();
    } else {
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->guilds->my
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
            "You belong to guild",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        die();
    }
    //$upd = $db->prepare("UPDATE `users` SET `balance`=balance-1 WHERE ID=:id");
    //$upd->bindParam(":id",$user->ID);
    //$upd->execute();
    $client->sendMessage(
        $chat_id,
        "Soon",
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
}
