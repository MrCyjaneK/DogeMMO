<?php
if ($text == $strings->guilds->my) {
    $user = user($chat_id);
    if ($user->in_guild_id == 0) {
        $client->sendMessage($chat_id, "What have you expected?");
        die();
    }
    $guild = $db->prepare(
        "SELECT * FROM `guilds` WHERE ID=:id AND token=:token"
    );
    $guild->bindParam(":id", $user->in_guild_id);
    $guild->bindParam(":token", $token);
    $guild->execute();
    $guild = $guild->fetchObject();
    $guild_info = $db->prepare(
        "SELECT * FROM `guilds_info` WHERE token=:token AND lvl_id=:level"
    );
    $guild_info->bindParam(":token", $token);
    $guild_info->bindParam(":level", $guild->level);
    $guild_info->execute();
    $guild_info = $guild_info->fetchObject();

    $members = $db->prepare("SELECT * FROM `users` WHERE `in_guild_id`=:gid");
    $members->bindParam(":gid", $guild->ID);
    $members->execute();
    $members = $members->fetchAll();
    $members = count($members);

    $reply_markup = [
        "keyboard" => [
            [
                [
                    "text" => $strings->guilds->members
                ],
                [
                    "text" => $strings->guilds->deposit
                ]
            ],
            [
                [
                    "text" => $strings->menu
                ]
            ]
        ],
        "resize_keyboard" => true
    ];

    $string =
        "🏯<b>" .
        htmlspecialchars($guild->name) .
        "</b>\n" .
        "🏅Lvl: " .
        $guild->level .
        " | xp: " .
        $guild->xp .
        "\n" .
        "🗿Members: " .
        $members .
        " / " .
        $guild_info->max_members .
        "\n" .
        "💰Balance: " .
        n($guild->balance) .
        " " .
        $config->currency .
        "\n" .
        "📜" .
        htmlspecialchars($guild->about);

    $client->sendMessage(
        $chat_id,
        $string,
        "HTML",
        null,
        null,
        null,
        $reply_markup
    );

    //$client->debug($chat_id, $guild, $guild_info);
}
if ($text == $strings->guilds->members) {
    $user = user($chat_id);
    if ($user->in_guild_id == 0) {
        $client->sendMessage($chat_id, "What have you expected?");
        die();
    }
    $guild = $db->prepare(
        "SELECT * FROM `guilds` WHERE ID=:id AND token=:token"
    );
    $guild->bindParam(":id", $user->in_guild_id);
    $guild->bindParam(":token", $token);
    $guild->execute();
    $guild = $guild->fetchObject();
    $guild_info = $db->prepare(
        "SELECT * FROM `guilds_info` WHERE token=:token AND lvl_id=:level"
    );
    $guild_info->bindParam(":token", $token);
    $guild_info->bindParam(":level", $guild->level);
    $guild_info->execute();
    $guild_info = $guild_info->fetchObject();
    $members = $db->prepare("SELECT * FROM `users` WHERE `in_guild_id`=:gid");
    $members->bindParam(":gid", $guild->ID);
    $members->execute();
    $members = $members->fetchAll();
    $string = "🏯<b>" . htmlspecialchars($guild->name) . "</b> members\n";
    foreach ($members as $member) {
        $string .= "🗿" . htmlspecialchars($member['username']) . "\n";
        if ($guild->admin == $user->ID) {
            $string .= "<code>    - </code> /kick_" . $member['ID'] . "\n";
        }
    }
    if ($guild->admin == $user->ID) {
        $string .=
            "\nIf you want to add new members, they need to do /join_" .
            $guild->ID;
    }
    $client->sendMessage($chat_id, $string, "HTML");
}
$exploded = explode("_", $text);
if ($exploded[0] == "/kick") {
    $user = user($chat_id);
    if ($user->in_guild_id == 0) {
        $client->sendMessage($chat_id, "What have you expected?");
        die();
    }
    $guild = $db->prepare(
        "SELECT * FROM `guilds` WHERE ID=:id AND token=:token"
    );
    $guild->bindParam(":id", $user->in_guild_id);
    $guild->bindParam(":token", $token);
    $guild->execute();
    $guild = $guild->fetchObject();
    if ($user->ID != $guild->admin) {
        $client->sendMessage(
            $chat_id,
            "You don't have permissions to use this command"
        );
        die();
    }
    $requested = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $requested->bindParam(":id", $exploded[1]);
    $requested->execute();
    $tokick = $requested->fetchObject();
    if ($tokick->in_guild_id != $user->in_guild_id) {
        $client->sendMessage(
            $chat_id,
            "But why? Why would you do something like this?"
        );
        die();
    }
    $kick = $db->prepare("UPDATE `users` SET `in_guild_id`=0 WHERE ID=:id");
    $kick->bindParam(":id", $tokick->ID);
    $kick->execute();
    $client->sendMessage(
        $chat_id,
        $tokick->username . " is no longer in " . $guild->name
    );
    $client->sendMessage(
        $tokick->TG_id,
        "We have news from " .
            $user->username .
            " to you as a member of " .
            $guild->name .
            "... Well, not anymore. You no longer belond to " .
            $guild->name
    );
}
if ($exploded[0] == "/join") {
    $guild = $db->prepare("SELECT * FROM `guilds` WHERE ID=:id");
    $guild->bindParam(":id", $exploded[1]);
    $guild->execute();
    $guild = $guild->fetchAll();
    if ($guild == []) {
        $client->sendMessage($chat_id, "I don't know this guild.");
        die();
    }
    /* Pay the postman */
    $user = user($chat_id);
    if ($requested->in_guild_id != 0) {
        $client->sendMessage($chat_id, "Nah...");
        die();
    }
    if ($user->balance < 1) {
        $client->sendMessage(
            $chat_id,
            "Oh dear... I see that you want to join a force? But paying the fee of 1 " .
                $config->currency .
                " is required, and sadly you don't have enough."
        );
        die();
    }
    $pay = $db->prepare(
        "UPDATE `users` SET `balance`=`balance`-1 WHERE ID=:id"
    );
    $pay->bindParam(":id", $user->ID);
    $pay->execute();

    $owner = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $owner->bindParam(":id", $guild[0]['admin']);
    $owner->execute();
    $owner = $owner->fetchObject();

    $client->sendMessage(
        $owner->TG_id,
        "<b>Postman:</b> Howdy " .
            $owner->username .
            " I have great news! " .
            $user->username .
            " wants to join " .
            $guild[0]['name'] .
            " guild. Invite fee is already paid, do you wish sir to accept him or no? If you want to do so, /accept_" .
            $user->ID,
        "HTML"
    );
    $client->sendMessage($user->TG_id, "Your request have been sent.");
}
if ($exploded[0] == "/accept") {
    $user = user($chat_id);
    $requested = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $requested->bindParam(":id", $exploded[1]);
    $requested->execute();
    $requested = $requested->fetchObject();
    if ($requested->in_guild_id != 0) {
        $client->sendMessage(
            $chat_id,
            "Nah... " . $requested->username . " has a guild"
        );
        die();
    }
    if ($requested->lvl < 1) {
        $client->sendMessage(
            $chat_id,
            "Nah... " . $requested->username . " needs to be on level 1"
        );
        die();
    }
    $user = user($chat_id);
    //$client->debug($chat_id,print_r($user,1));

    if ($user->in_guild_id < 1) {
        $client->sendMessage($chat_id, "Nah... you need to be in guild");
        die();
    }
    $guild = $db->prepare("SELECT * FROM `guilds` WHERE ID=:id");
    $guild->bindParam(":id", $user->in_guild_id);
    $guild->execute();
    $guild = $guild->fetchObject();
    if ($guild->admin != $user->ID) {
        $client->sendMessage($chat_id, "Nah... You aren't admin");
        die();
    }
    $guild_info = $db->prepare(
        "SELECT * FROM `guilds_info` WHERE token=:token AND lvl_id=:level"
    );
    $guild_info->bindParam(":token", $token);
    $guild_info->bindParam(":level", $guild->level);
    $guild_info->execute();
    $guild_info = $guild_info->fetchObject();

    $members = $db->prepare("SELECT * FROM `users` WHERE `in_guild_id`=:gid");
    $members->bindParam(":gid", $guild->ID);
    $members->execute();
    $members = $members->fetchAll();
    $members = count($members);
    if ($members >= $guild_info->max_members) {
        $client->sendMessage(
            $chat_id,
            "Wooah, you made Italian Guy really mad, why, why, why are you willing to invite more people than it's allowed to? He forgived you, but nex time watch your move and upgrade your guild to be able to invite more people."
        );
        die();
    }
    $upd = $db->prepare("UPDATE `users` SET `in_guild_id`=:gid WHERE ID=:id");
    $upd->bindParam(":gid", $guild->ID);
    $upd->bindParam(":id", $requested->ID);
    $upd->execute();

    $client->sendMessage(
        $user->TG_id,
        $requested->username . " is now part of " . $guild->name . " guild!"
    );
    $client->sendMessage(
        $requested->TG_id,
        $user->username .
            " accepted your request, you are now part of " .
            $guild->name .
            " guild!"
    );
}
$exploded = explode(" ", $text, 2);
if ($exploded[0] == "/setabout") {
    $user = user($chat_id);
    if ($user->in_guild_id == 0) {
        $client->sendMessage($chat_id, "What have you expected?");
        die();
    }
    $guild = $db->prepare(
        "SELECT * FROM `guilds` WHERE ID=:id AND token=:token"
    );
    $guild->bindParam(":id", $user->in_guild_id);
    $guild->bindParam(":token", $token);
    $guild->execute();
    $guild = $guild->fetchObject();
    if ($user->ID != $guild->admin) {
        $client->sendMessage(
            $chat_id,
            "You don't have permissions to use this command"
        );
        die();
    }
    $upd = $db->prepare("UPDATE `guilds` SET `about`=:about WHERE ID=:id");
    $upd->bindParam(":about", $exploded[1]);
    $upd->bindParam(":id", $guild->ID);
    $upd->execute();
    $client->sendMessage($chat_id, "Updated");
}
