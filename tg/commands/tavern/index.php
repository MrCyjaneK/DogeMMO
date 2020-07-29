<?php
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    include $__DIR__ . "/commands/tavern/home.php";
    include $__DIR__ . "/commands/tavern/dice.php";
    include $__DIR__ . "/commands/tavern/pint.php";
}
