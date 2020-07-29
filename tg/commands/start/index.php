<?php
/*
  Purpose: Welcome user, display minimal menu.
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if (explode(" ", $text, 2)[0] == "/start" || $text == $strings->menu) {
        $user = user($chat_id, $name);
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->castle
                    ],
                    [
                        "text" => $strings->quest
                    ],
                    [
                        "text" => $strings->community
                    ]
                ],
                [
                    [
                        "text" => $strings->war->name
                    ]
                ]
            ],
            "resize_keyboard" => true
        ];
        $today = new DateTime('now');
        $tomorrow = new DateTime('tomorrow');
        $difference = $today->diff($tomorrow);
        $values = array(
            'hour' => $difference->format('%h'),
            'minute' => $difference->format('%i'),
            'second' => $difference->format('%s')
        );
        $left = "";
        foreach ($values as $word => $val) {
            if ($val) {
                $left .=
                    sprintf(
                        ngettext('%d ' . $word, '%d ' . $word . 's', $val),
                        $val
                    ) . ' ';
            }
        }
        $client->sendMessage(
            $chat_id,
            "Hello <b>" .
                $user->username .
                "</b> /me\n$left" .
                "until next battle",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
}
