<?php
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    $expl = explode(' ', $text);
    if ($expl[0] == '/setlang') {
        $update_user = $db->prepare(
            "UPDATE `users` SET `langcode`=:langcode WHERE token=:token AND ID=:id"
        );
        $langcode = strtoupper(substr($expl[1], 0, 5));
        $update_user->bindParam(":langcode", $langcode);
        $update_user->bindParam(":token", $token);
        $update_user->bindParam(":id", $user->ID);
        $update_user->execute();
    }
}