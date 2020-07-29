<?php
require_once __DIR__ . "/vendor/autoload.php";
include 'inc/db.php';
include 'inc/fn.php';
include 'inc/strings.php';
include "inc/user.php";
use TuriBot\Client;
if (!isset($_GET["api"])) {
    die();
}
date_default_timezone_set('Europe/Warsaw');
// Note to self:
// Setting up bot:
//    api.telegram.org/botTELEGRAM:API_TOKEN/setWebhook?url=
//    https://dogemmo.mrcyjanek.net/tg/index.php?api=TELEGRAM:API_TOKEN
//
try {
    $token = explode(':', $_GET['api']);
    // Get bot's token
    $token = $token[0];
    $config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
    $config->bindParam(":token", $token);
    $config->execute();
    $config = $config->fetchObject();
    $client = new Client($_GET["api"], false);
    $update = $client->getUpdate();
    if (isset($update->callback_query)) {
        $__DIR__ = __DIR__;
        $cmdlist = scandir("commands");
        unset($cmdlist[0]); // .
        unset($cmdlist[1]); // ..
        foreach ($cmdlist as $cmd) {
            // If reply is an callback_query (buttons)
            //bot will look for inline.php files in commands dir
            if (file_exists("./commands/$cmd/inline.php")) {
                include "./commands/$cmd/inline.php";
            }
        }
    }
    if ($update->message->chat->type != 'private') {
        if (
            $update->message->chat->type == 'supergroup' &&
            isset($update->message->new_chat_participant)
        ) {
            // Welcome new user and exportChatInviteLink to make sure that nobody will use that link again
            $client->exportChatInviteLink($update->chat->id);
            $user = user($update->message->from->id);
            $client->sendMessage(
                $update->message->chat->id,
                "Hey " .
                    $user->username .
                    "! Welcome to " .
                    $update->message->chat->title
            );
        }
        die();
    }
    $__DIR__ = __DIR__;
    // Load all other commands (non inline)
    $cmdlist = scandir("commands");
    unset($cmdlist[0]); // .
    unset($cmdlist[1]); // ..
    foreach ($cmdlist as $cmd) {
        include "./commands/$cmd/index.php";
    }
} catch (Exception $e) {
    // Error handling, it is safe to throw errors in code.
    include 'db.php';
    $err = $db->prepare("INSERT INTO `errors`(`error`) VALUES (:error)");
    $er = print_r($e, 1);
    $err->bindParam(":error", $er);
    $err->execute();
}
