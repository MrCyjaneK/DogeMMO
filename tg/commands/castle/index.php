<?php
/*
  Purpose: Allow user to go on missions.
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($text == $strings->castle) {
        $user = user($chat_id, $name);
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->shop->name
                    ],
                    [
                        "text" => $strings->bank->name
                    ]
                ],
                [
                    [
                        "text" => $strings->battle->name
                    ],
                    [
                        "text" => $strings->tavern->name
                    ],
                    [
                        "text" => $strings->guilds->name
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
            "<b>" . $user->username . "'s castle</b>",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
}
