<?php
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    $user = user($chat_id);
    $boomed = explode(" ", $text, 2);
    if ($text == "/name") {
        $client->sendMessage(
            $chat_id,
            "Ah... So you don't like when other's call you <i>" .
                $user->username .
                "</i>? That's not a problem. To change your name use /name Your Name, for example <b>/name John Nowak</b>\nIn your name you can use only letters.",
            "HTML"
        );
        die();
    }
    if ($boomed[0] == "/name") {
        $name = $boomed[1];
        $name = strtolower(preg_replace('/[^a-zA-Z ]+/', '', $name));
        if (3 < strlen($name) && strlen($name) < 17) {
            $check = $db->prepare(
                "SELECT * FROM `users` WHERE `username`=:username AND token=:token"
            );
            $check->bindParam(":username", $name);
            $check->bindParam(":token", $token);
            $check->execute();
            $check = $check->fetchAll();
            if ($check == []) {
                $update = $db->prepare(
                    "UPDATE `users` SET `username`=:username WHERE ID=:id"
                );
                $update->bindParam(":username", $name);
                $update->bindParam(":id", $user->ID);
                $update->execute();
                $client->sendMessage($chat_id, "Nice to meet you $name.");
            } else {
                $client->sendMessage(
                    $chat_id,
                    "I know one guy called $name, and one guy like this is enough."
                );
            }
        } else {
            $client->sendMessage(
                $chat_id,
                "Our scientists found out, that knight's name is directly proportional to his sword size. If it is too short (up to 4 signs), noone will ever accept you in any castle. Same thing if it is too long (more than 16 signs), but already for a different reason😏."
            );
        }
    }
}
