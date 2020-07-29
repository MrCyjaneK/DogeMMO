<?php
if ($text == $strings->tavern->dice) {
    $user = user($chat_id);
    $reply_markup = [
        "keyboard" => [
            [
                [
                    "text" => $strings->tavern->pint
                ],
                [
                    "text" => $strings->tavern->dice
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

    if ($user->balance < 5) {
        $client->sendMessage(
            $chat_id,
            "Trying to gamble without money just doesn't work.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        die();
    }

    if (rand(0, 1)) {
        $client->sendMessage(
            $chat_id,
            "You have won this round, good.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        $upd = $db->prepare(
            "UPDATE `users` SET `balance`=balance+5 WHERE ID=:id"
        );
        $upd->bindParam(":id", $user->ID);
        $upd->execute();
    } else {
        $client->sendMessage(
            $chat_id,
            "You have lost this round.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        $upd = $db->prepare(
            "UPDATE `users` SET `balance`=balance-5 WHERE ID=:id"
        );
        $upd->bindParam(":id", $user->ID);
        $upd->execute();
    }
}
