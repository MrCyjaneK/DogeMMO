<?php
/*
  Purpose: Allow user to buy stuff
*/

if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    include $__DIR__ . "/commands/shop/home.php";
    include $__DIR__ . "/commands/shop/list_all.php";
    include $__DIR__ . "/commands/shop/buy.php";
    include $__DIR__ . "/commands/shop/sell.php";
    include $__DIR__ . "/commands/shop/changeit.php";
    include $__DIR__ . "/commands/shop/inline.php";
}
