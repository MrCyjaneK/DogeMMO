<?php
/*
  Purpose: Allow user to go on quest
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    $user = user($chat_id, $name);
    if ($text == $strings->quest) {
        $quests = $db->prepare("SELECT * FROM `quests` WHERE token=:token");
        $quests->bindParam(":token", $token);
        $quests->execute();
        $quests = $quests->fetchAll();
        foreach ($quests as $quest) {
            if ($quest["minlvl"] <= $user->lvl) {
                //$client->sendMessage($chat_id, print_r($quest,1),"HTML");
                $strtosend =
                    "<b>Name:</b> " .
                    $quest['name'] .
                    "\n" .
                    "<b>Time:</b> " .
                    $quest['minutes'] .
                    " Minutes\n" .
                    "Accept it: /quest_" .
                    $quest['ID'];
                $client->sendMessage($chat_id, $strtosend, "HTML");
            }
        }
        $client->sendMessage($chat_id, "Return: /me");
    }
    //$client->sendMessage($chat_id, print_r($text,1));
    $exploded = explode("_", $text);
    if ($exploded[0] == "/quest") {
        $qid = $exploded[1];
        $quests = $db->prepare(
            "SELECT * FROM `quests` WHERE token=:token AND id=:id"
        );
        $quests->bindParam(":token", $token);
        $quests->bindParam(":id", $qid);
        $quests->execute();
        $quests = $quests->fetchAll();
        if ($quests == []) {
            $client->sendMessage(
                $chat_id,
                "Uhh... It looks like invalid quest id...",
                "HTML"
            );
            die();
        }
        $quest = $quests[0];
        if ($quest["minlvl"] <= $user->lvl) {
            // Check if user is already on quest...
            $check = $db->prepare(
                "SELECT * FROM `active_quests` WHERE user_id=:user_id AND token=:token"
            );
            $check->bindParam(":user_id", $user->ID);
            $check->bindParam(":token", $token);
            $check->execute();
            $check = $check->fetchAll();
            if ($check != []) {
                $client->sendMessage(
                    $chat_id,
                    "You are too busy with different adventure.",
                    "HTML"
                );
                die();
            }
            //$client->sendMessage($chat_id, print_r($check,1));
            // Add new quest
            $newquest = $db->prepare(
                "INSERT INTO `active_quests`(`quest_id`, `user_id`, `timestarted`, `token`) VALUES (:quest_id, :user_id, :timestarted, :token)"
            );
            $newquest->bindParam(":token", $token);
            $newquest->bindParam(":quest_id", $qid);
            $newquest->bindParam(":user_id", $user->ID);
            $now = time();
            $newquest->bindParam(":timestarted", $now);
            $newquest->execute();
            $client->sendMessage(
                $chat_id,
                "You have went to " . $quest['name'],
                "HTML"
            );
        } else {
            $client->sendMessage(
                $chat_id,
                "I don't know where have you got that command, but it's not going to work because you need to have higher level.",
                "HTML"
            );
            die();
        }
    }
}
