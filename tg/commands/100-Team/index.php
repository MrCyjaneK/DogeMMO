<?php
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    $user = user($chat_id);
    if (empty($user->teamid)) {
        $exploded = explode("_", $text);
        if ($exploded[0] == "/team") {
            $teams = $db->prepare(
                "SELECT * FROM `teams` WHERE token=:token AND ID=:id"
            );
            $teams->bindParam(":token", $token);
            $teams->bindParam(":id", $exploded[1]);
            $teams->execute();
            $teams = $teams->fetchObject();
            if (!isset($teams->ID)) {
                die();
            }
            $upd = $db->prepare(
                "UPDATE `users` SET `teamid`=:teamid WHERE ID=:id"
            );
            $upd->bindParam(":teamid", $teams->ID);
            $upd->bindParam(":id", $user->ID);
            $upd->execute();
            $string =
                "You have joined " .
                $teams->emoji .
                " " .
                $teams->name .
                ". Have a nice play.";
            $client->sendMessage($chat_id, $string);
            die();
        }
        $client->sendMessage(
            $chat_id,
            "Howdy! For which castle are you fighting?."
        );
        $teams = $db->prepare("SELECT * FROM `teams` WHERE token=:token");
        $teams->bindParam(":token", $token);
        $teams->execute();
        $teams = $teams->fetchAll();
        $string = "Which one would you like to join?\n";
        foreach ($teams as $team) {
            $string .=
                "/team_" .
                $team['ID'] .
                " - <b>" .
                $team['emoji'] .
                " " .
                $team['name'] .
                "</b>\n";
        }
        $client->sendMessage($chat_id, $string, "HTML");
        die();
    }
}
