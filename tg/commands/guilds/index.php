<?php
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    include $__DIR__ . "/commands/guilds/home.php";
    include $__DIR__ . "/commands/guilds/new.php";
    include $__DIR__ . "/commands/guilds/my.php";
    include $__DIR__ . "/commands/guilds/deposit.php";
}
