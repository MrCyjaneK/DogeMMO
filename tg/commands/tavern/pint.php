<?php
if ($text == $strings->tavern->pint) {
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

    if ($user->balance < 1) {
        $client->sendMessage(
            $chat_id,
            "Who you think you are? Is barman supposed to pay for you? Go to " .
                $strings->bank->name .
                ", get some money and be a man of honor you pathetic.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        die();
    }
    $upd = $db->prepare("UPDATE `users` SET `balance`=balance-1 WHERE ID=:id");
    $upd->bindParam(":id", $user->ID);
    $upd->execute();
    $client->sendMessage(
        $chat_id,
        "One beer please",
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
    // #TODO, nie wiem no.. jakiegoś crona tu zajebać czy cos, zeby seleepa nie było
    sleep(5);
    if (rand(0, 5) == 2) {
        $att = $db->prepare(
            "SELECT * FROM `users` WHERE fightfor=:teamid AND fightfor<>teamid"
        );
        $att->bindParam(":teamid", $user->teamid);
        $att->execute();
        $att = $att->fetchAll();
        $att = count($att);
        $client->sendMessage(
            $chat_id,
            "You have sat down with a beer and decited to listen what others are talking about, you have heard that your castle is being attacked by a total number of " .
                $att .
                " individuals.",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
    $txt = $db->prepare(
        "SELECT * FROM `quests_text` WHERE token=:token AND reward_from='beer'"
    );
    $txt->bindParam(":token", $config->token);
    $txt->execute();
    $txt = $txt->fetchAll();
    $txt = print_r($txt[array_rand($txt)]['string'], 1);
    $client->sendMessage(
        $chat_id,
        $txt,
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
}
