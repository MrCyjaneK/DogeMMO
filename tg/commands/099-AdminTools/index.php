<?php
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($chat_id == $config->owner) {
        $exploded = explode(' ', $text, 2);
        if ($exploded[0] == "/broadcast") {
            $users = $db->prepare("SELECT * FROM `users` WHERE token=:token");
            $users->bindParam(":token", $token);
            $users->execute();
            $users = $users->fetchAll();
            foreach ($users as $user) {
                $user = user($user['TG_id']);
                $client->sendMessage(
                    $user->TG_id,
                    "Hello, <b>" . $user->username . "</b>!\n" . $exploded[1],
                    "HTML"
                );
            }
        }
    }
}
