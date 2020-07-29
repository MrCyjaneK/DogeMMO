<?php
/*
  Purpose: Show info about game
*/
if (isset($update->message) or isset($update->edited_message)) {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    if ($text == $strings->community) {
        $user = user($chat_id);
        $info =
            "📯Communication with other castles
Join @DogeMMOsociety and start talking with citizens.

📢Game news
Join @DogeMMO to keep up with the latest updates.

📊Ranking
Players: /top" .
            //Castles: /worldtop
            //Guilds: /guildtop
            "\n\n";
        $teams = $db->prepare(
            "SELECT * FROM `teams` WHERE token=:token AND ID=:id"
        );
        $teams->bindParam(":token", $token);
        $teams->bindParam(":id", $user->teamid);
        $teams->execute();
        $teams = $teams->fetchObject();
        /* Do this in case of invite link problem */
        //$client->exportChatInviteLink($teams->groupid);

        $info .=
            $teams->emoji .
            "To join " .
            $teams->emoji .
            $teams->name .
            " castle chat use this link: " .
            $client->getChat($teams->groupid)->result->invite_link;
        $client->sendMessage(
            $chat_id,
            "$info",
            "HTML",
            null,
            null,
            null,
            $reply_markup
        );
    }
}
