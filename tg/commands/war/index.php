<?php
/*
  Purpose: Welcome user, display minimal menu.
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($text == $strings->war->name) {
        $user = user($chat_id, $name);
        $reply_markup = [
            "keyboard" => [
                [
                    [
                        "text" => $strings->menu
                    ]
                ]
            ],
            "resize_keyboard" => true
        ];
        $today = new DateTime('now');
        $tomorrow = new DateTime('tomorrow');
        $difference = $today->diff($tomorrow);
        $values = array(
            'hour' => $difference->format('%h'),
            'minute' => $difference->format('%i'),
            'second' => $difference->format('%s')
        );
        $left = "";
        foreach ($values as $word => $val) {
            if ($val) {
                $left .=
                    sprintf(
                        ngettext('%d ' . $word, '%d ' . $word . 's', $val),
                        $val
                    ) . ' ';
            }
        }
        $string =
            "Maybe you are a good at defending? /defend\nOr you would like to attack somebody?";
        $teams = $db->prepare("SELECT * FROM `teams` WHERE token=:token");
        $teams->bindParam(":token", $token);
        $teams->execute();
        $teams = $teams->fetchAll();
        $string .= "Which one would you like to attack?\n";
        foreach ($teams as $team) {
            if ($team['ID'] == $user->teamid) {
                continue;
            }
            $string .=
                "/attack_" .
                $team['ID'] .
                " - <b>" .
                $team['emoji'] .
                " " .
                $team['name'] .
                "</b>\n";
        }
        $client->sendMessage(
            $chat_id,
            "Hello <b>" .
                $user->username .
                "</b>! Glad you came to the battlefield!\nWhat would you like to do?\n$string\n$left" .
                "until next battle",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
    if ($text == "/defend") {
        $user = user($chat_id);
        $upd = $db->prepare("UPDATE `users` SET `fightfor`=:ff WHERE ID=:id");
        $upd->bindParam(":ff", $user->teamid);
        $upd->bindParam(":id", $user->ID);
        $upd->execute();
        $client->sendMessage(
            $chat_id,
            "Hello <b>" .
                $user->username .
                "</b>! Glad you came to the battlefield, you will be defending your castle",
            "HTML"
        );
    }
    $exploded = explode("_", $text);
    if ($exploded[0] == "/attack") {
        $user = user($chat_id);
        $check = $db->prepare(
            "SELECT * FROM `teams` WHERE ID=:id AND token=:token"
        );
        $check->bindParam(":id", $exploded[1]);
        $check->bindParam(":token", $token);
        $check->execute();
        $check = $check->fetchAll();
        if ($check == []) {
            die();
        }
        $upd = $db->prepare("UPDATE `users` SET `fightfor`=:ff WHERE ID=:id");
        $upd->bindParam(":ff", $user->teamid);
        $upd->bindParam(":id", $user->ID);
        $upd->execute();
        $client->sendMessage(
            $chat_id,
            "Hello <b>" .
                $user->username .
                "</b>! Glad you came to the battlefield, you will be attacking " .
                $check[0]['emoji'] .
                $check[0]['name'] .
                " castle.",
            "HTML"
        );
    }
}
