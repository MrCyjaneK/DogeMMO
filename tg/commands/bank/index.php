<?php
/*
  Purpose: Allow user to (withdraw) and deposit
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($text == $strings->bank->name) {
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->bank->deposit->name
                    ],
                    [
                        "text" => $strings->bank->withdraw->name
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
            "Hello <b>" . $user->username . "</b>. How may I help you?",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }

    if ($text == $strings->bank->deposit->name) {
        $user = user($chat_id, $name);
        $client->sendMessage(
            $chat_id,
            $strings->bank->deposit->text,
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
        $client->sendMessage(
            $chat_id,
            $user->depositaddress,
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }

    if ($text == $strings->bank->withdraw->name) {
        $client->sendMessage(
            $chat_id,
            $strings->bank->withdraw->text,
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
}
