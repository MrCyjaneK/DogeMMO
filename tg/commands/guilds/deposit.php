<?php
if ($text == $strings->guilds->deposit) {
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
    $client->sendMessage(
        $chat_id,
        "To send money to guild use <code>/transfer x.xxxxxxxx</code> where x is amount you want to transfer inside.",
        "HTML"
    );
}
$exploded = explode(" ", $text);
if ($exploded[0] == "/transfer") {
    $user = user($chat_id);
    if ($user->in_guild_id == 0) {
        $client->sendMessage($chat_id, "What have you expected?");
        die();
    }
    $amt = $exploded[1];
    if (!is_numeric($amt)) {
        $client->sendMessage($chat_id, "NaN...");
        die();
    }
    $amt = n($amt);
    if ($amt <= 0) {
        $client->sendMessage($chat_id, "-Nah...");
        die();
    }
    if ($amt > $user->balance) {
        $client->sendMessage($chat_id, "Nah...");
        die();
    }
    $guild = $db->prepare(
        "SELECT * FROM `guilds` WHERE ID=:id AND token=:token"
    );
    $guild->bindParam(":id", $user->in_guild_id);
    $guild->bindParam(":token", $token);
    $guild->execute();
    $guild = $guild->fetchObject();
    $gupd = $db->prepare(
        "UPDATE `guilds` SET `balance`=balance+:balance WHERE ID=:id"
    );
    $gupd->bindParam(":balance", $amt);
    $gupd->bindParam(":id", $user->in_guild_id);
    $gupd->execute();
    $gupd = $db->prepare(
        "UPDATE `users` SET `balance`=balance-:balance WHERE ID=:id"
    );
    $gupd->bindParam(":balance", $amt);
    $gupd->bindParam(":id", $user->ID);
    $gupd->execute();
    $client->sendMessage(
        $chat_id,
        "Success, $amt " . $config->currency . " have been transfered to guild"
    );
    $requested = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $requested->bindParam(":id", $guild->admin);
    $requested->execute();
    $requested = $requested->fetchObject();
    $client->sendMessage(
        $requested->TG_id,
        $user->username .
            " have sent " .
            n($amt) .
            " " .
            $config->currency .
            " to guild."
    );
}
