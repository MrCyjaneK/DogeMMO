<?php
/*
  Purpose: Allow user to buy stuff
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    $user = user($chat_id);
    include $__DIR__ . "/commands/battle/home.php";
    include $__DIR__ . "/commands/battle/pvp.php";
}
