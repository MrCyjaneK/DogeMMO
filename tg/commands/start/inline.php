<?php
if (isset($update->callback_query)) {
    $callback_id = $update->callback_query->id;
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
    $data = $update->callback_query->data;
    if ($data == 'home') {
        $client->deleteMessage($chat_id, $message_id);
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
            [
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
            ]
        );
    }
}
