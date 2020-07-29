<?php
if ($text == $strings->tavern->name) {
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
    $client->sendMessage(
        $chat_id,
        "You walk inside tavern, loud and overcrowded as usual. Next to the bar you see some soldiers bragging about the recent news from the battle lines. In the back of the tavern some farmers are playing dice.

You can buy a pint of ale and sit down next to the soldiers: take a rest, listen to some gossips. If you are lucky, you might hear something interesting.
Price of one pint: " .
            n(1) .
            " " .
            $config->currency .
            "💰

Or you can sit next to the gamblers and try your luck in dice.
Entry fee: " .
            n(5) .
            " " .
            $config->currency .
            "💰",
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );
}
