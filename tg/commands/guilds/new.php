<?php
if ($text == $strings->guilds->new) {
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
            "So you want to create guild? That's not a problem all you need is 50 " .
                $config->currency .
                " to pay Italian gang owner for the registar, if you are sure that you want to belong to guild /pay_him",
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
            "You belong to guild, if you need other one you need to leave it.",
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
    //$client->sendMessage($chat_id, "Soon","HTML",null,null,null,$reply_markup);
}

/* Registar process */

if ($text == "/pay_him") {
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

        if ($user->balance < 50) {
            $client->sendMessage(
                $chat_id,
                "Who you think you are? You came to Italian Guy empty handed! Thank god that you are not dead"
            );
            die();
        }
        //Create guild
        $name = rand(0, 99999) . hash("sha256", time());
        $level = 0;
        $moderator = 0;
        $about =
            "This is good place for links, rules or stuff, if you want to change it use /setabout [text]";
        $create = $db->prepare(
            "INSERT INTO `guilds`(`name`, `level`, `admin`, `moderator`, `about`, `token`) VALUES (:name,:level,:admin,:moderator,:about,:token)"
        );
        $create->bindParam(":name", $name);
        $create->bindParam(":level", $level);
        $create->bindParam(":admin", $user->ID);
        $create->bindParam(":moderator", $moderator);
        $create->bindParam(":about", $about);
        $create->bindParam(":token", $token);
        $create->execute();

        $guild = $db->prepare("SELECT * FROM `guilds` ORDER BY `ID` DESC");
        $guild->execute();
        $guild = $guild->fetchAll();
        //Pay him
        $pay = $db->prepare(
            "UPDATE `users` SET `balance`=`balance`-50, `in_guild_id`=:gid WHERE ID=:id"
        );
        $pay->bindParam(":id", $user->ID);
        $pay->bindParam(":gid", $guild[0]['ID']);
        $pay->execute();
        $client->sendMessage(
            $chat_id,
            "He wasn't happy because you have interrupted his sleep, but he made a guild anyway.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        die();
    }
}
