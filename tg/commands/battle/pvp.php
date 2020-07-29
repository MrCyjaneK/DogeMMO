<?php
$itemtypes = [
    /* 0 */ "Sword",
    /* 1 */ "Helmet",
    /* 2 */ "Breastplate",
    /* 3 */ "Leg",
    /* 4 */ "Boots",
    /* 5 */ "Throwing weapon", // Bow, Slingshot
    /* 6 */ "Cartridge", // Arrow, Rock
    /* 7 */ "Potions",
    /* 8 */ "Magical Powers"
];
$taglist = [
    /* 0 */ "Common",
    /* 1 */ "Normal",
    /* 2 */ "Uncommon",
    /* 3 */ "Rare",
    /* 4 */ "Very Rare",
    /* 5 */ "Legendary",
    /* 6 */ "Undefinable",
    /* 7 */ "It must be cheated",
    /* 8 */ "As rare as men who understand womens"
];
if ($text == $strings->battle->PVP->name) {
    $client->sendMessage(
        $chat_id,
        "So you want to go on fight? That's good.\nMake sure that you have everything that is needed with you (/inv) and if you make sure that you want to go on battle click do /search, then you can decide if you want to fight or no.\nYour personal fightlink: /fight_" .
            $user->ID .
            "\nRemember that you can only fight with people who have level exactly the same as you or up to 2 levels higher, or 1 level lower."
    );
    die();
}
if ($text == "/search") {
    $randlevel = $user->lvl + rand(-1, 2);
    $pvp_users = $db->prepare(
        "SELECT * FROM `users` WHERE lvl=:lvl AND token=:token AND canfight=1 AND ID<>:id AND hp=100"
    );
    $pvp_users->bindParam(":lvl", $randlevel);
    $pvp_users->bindParam(":token", $token);
    $pvp_users->bindParam(":id", $user->ID);
    $pvp_users->execute();
    $pvp_users = $pvp_users->fetchAll();
    if ($pvp_users == []) {
        $client->sendMessage(
            $chat_id,
            "Uh... I was unable to find somebody who would like to fight with you... Try to do /search later or now."
        );
        die();
    }
    $pvp_rand = $pvp_users[array_rand($pvp_users)];
    $string =
        "<b>" .
        $pvp_rand['username'] .
        "</b> is interested. /fight_" .
        $pvp_rand['ID'];
    $client->sendMessage($chat_id, $string, "HTML");
}
$exploded = explode("_", $text);
if ($exploded[0] == "/fight") {
    $client->sendMessage(
        $chat_id,
        "Sorry bro, feature web-only dogemmo.mrcyjanek.net"
    );
    die();
    $id = $exploded[1];
    $enemy = $db->prepare(
        "SELECT * FROM `users` WHERE token=:token AND canfight=1 AND ID=:real_id AND ID<>:id AND hp=100"
    );
    $enemy->bindParam(":token", $token);
    $enemy->bindParam(":real_id", $id);
    $enemy->bindParam(":id", $user->ID);
    $enemy->execute();
    $enemy = $enemy->fetchAll();
    if ($enemy == []) {
        $client->sendMessage(
            $chat_id,
            "I'm sorry... but i don't know guy like this."
        );
        die();
    }
    $enemy = $enemy[0];
    $allowed_levels = [
        $user->lvl - 1,
        $user->lvl,
        $user->lvl + 1,
        $user->lvl + 2
    ];
    if (in_array($enemy["lvl"], $allowed_levels)) {
        /* Build profiles */
        /*
			$fighter[0] // $user
			$fighter[1] // $enemy
		*/
        $fighter = [];
        // $fighter[0] // $user

        $user_items = $db->prepare(
            "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND token=:token AND in_eq=1"
        );
        $user_items->bindParam(":owned_by", $user->ID);
        $user_items->bindParam(":token", $token);
        $user_items->execute();
        $user_items = $user_items->fetchAll();

        $totalattack = 0;
        $totaldefense = 0;
        $totalspeed = 0;
        $totalweight = 0;
        $weapons = [];

        foreach ($user_items as $item) {
            $iteminfo = $db->prepare(
                "SELECT * FROM `shop_items` WHERE ID=:in_shop_id"
            );
            $iteminfo->bindParam(":in_shop_id", $item['in_shop_id']);
            $iteminfo->execute();
            $iteminfo = $iteminfo->fetchAll();
            $iteminfo = $iteminfo[0];
            $weapons[] =
                "<b>" . $iteminfo["emoji"] . " " . $iteminfo["name"] . "</b>"; // <code>[".$taglist[$iteminfo["tag"]]."]</code> - <code>⚔".round($iteminfo["attack"],2)." 🛡️".round($iteminfo["defense"],2)." 🏋‍♂".round($iteminfo["weight"],2)."kg 🏃‍♂".round($iteminfo["speed"],2)."</code>";
            $totalattack += $iteminfo["attack"] * $item['capacity'];
            $totaldefense += $iteminfo["defense"] * $item['capacity'];
            $totalspeed += $iteminfo["speed"] * $item['capacity'];
            $totalweight += $iteminfo["weight"] * $item['capacity'];
        }

        if ($totalweight == 0) {
            $totalweight = 0.1;
        }
        if ($totaldefense == 0) {
            $totaldefense = 0.1;
        }
        if ($totalspeed == 0) {
            $totalspeed = 0.1;
        }
        if ($totalweight == 0) {
            $totalweight = 0.1;
        }

        $fighter[0] = [
            "hp" => $user->hp,
            "powers" => [
                "attack" => $totalattack,
                "defense" => $totaldefense,
                "speed" => $totalspeed,
                "weight" => $totalweight
            ],
            "weapons" => $weapons
        ];

        // $fighter[1] // $enemy

        $user_items = $db->prepare(
            "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND token=:token AND in_eq=1"
        );
        $user_items->bindParam(":owned_by", $enemy["ID"]);
        $user_items->bindParam(":token", $token);
        $user_items->execute();
        $user_items = $user_items->fetchAll();

        $totalattack = 0;
        $totaldefense = 0;
        $totalspeed = 0;
        $totalweight = 0;
        $weapons = [];

        foreach ($user_items as $item) {
            $iteminfo = $db->prepare(
                "SELECT * FROM `shop_items` WHERE ID=:in_shop_id"
            );
            $iteminfo->bindParam(":in_shop_id", $item['in_shop_id']);
            $iteminfo->execute();
            $iteminfo = $iteminfo->fetchAll();
            $iteminfo = $iteminfo[0];
            $weapons[] =
                "<b>" . $iteminfo["emoji"] . " " . $iteminfo["name"] . "</b>"; // <code>[".$taglist[$iteminfo["tag"]]."]</code> - <code>⚔".round($iteminfo["attack"],2)." 🛡️".round($iteminfo["defense"],2)." 🏋‍♂".round($iteminfo["weight"],2)."kg 🏃‍♂".round($iteminfo["speed"],2)."</code>";
            $totalattack += $iteminfo["attack"] * $item['capacity'];
            $totaldefense += $iteminfo["defense"] * $item['capacity'];
            $totalspeed += $iteminfo["speed"] * $item['capacity'];
            $totalweight += $iteminfo["weight"] * $item['capacity'];
        }

        if ($totalweight == 0) {
            $totalweight = 0.1;
        }
        if ($totaldefense == 0) {
            $totaldefense = 0.1;
        }
        if ($totalspeed == 0) {
            $totalspeed = 0.1;
        }
        if ($totalweight == 0) {
            $totalweight = 0.1;
        }
        $fighter[1] = [
            "hp" => $enemy['hp'],
            "powers" => [
                "attack" => $totalattack,
                "defense" => $totaldefense,
                "speed" => $totalspeed,
                "weight" => $totalweight
            ],
            "weapons" => $weapons
        ];

        // Let the fight begin...

        while ($user->hp > 0 && $enemy['hp'] > 0) {
            if (rand(0, 1)) {
                // $enemy attack
                $hit =
                    $fighter[1]['powers']['attack'] /
                    ($fighter[0]['powers']['defense'] / rand(1, 9));
                if ($hit < 0.0001) {
                    $hit = 0.0001;
                }
                $user->hp = $user->hp - $hit;
            } else {
                // $user attack
                $hit =
                    $fighter[0]['powers']['attack'] /
                    ($fighter[1]['powers']['defense'] / rand(1, 9));
                if ($hit < 0) {
                    $hit = 0;
                }
                $enemy['hp'] = $enemy['hp'] - $hit;
            }
            //$client->sendMessage($chat_id, $user->username." ".$user->hp." | ".$enemy['username']." ".$enemy['hp']);
        }
        $reply = "<code>~~~RESULTS~~~</code>\n";
        $reply .=
            "Attacker: " .
            $user->username .
            " (/fight_" .
            $user->ID .
            ") arrived on the battlefield with\n" .
            "" .
            join("\n", $fighter[0]['weapons']) .
            "\n" .
            "Defender was attacked with\n" .
            "" .
            join("\n", $fighter[1]['weapons']) .
            "\n\n\n" .
            "💙" .
            $user->username .
            " " .
            round($user->hp) .
            "hp\n" .
            "💙" .
            $enemy['username'] .
            " " .
            round($enemy['hp']) .
            "hp\n";
        if ($user->hp < 0) {
            // $user lose
            $lose = n($enemy['balance'] * 0.1 * ($enemy['hp'] / 100));
            $won = n($lose * 0.9);
            $client->sendMessage($enemy['TG_id'], "win");
            $client->sendMessage($user->TG_id, "lose");
            $reply .=
                $user->username .
                " lost " .
                n($won) .
                " " .
                $config->currency .
                "\n";
            $reply .=
                $enemy['username'] .
                " won " .
                n($lose) .
                " " .
                $config->currency;
            $bal_update = $db->prepare(
                "UPDATE `users` SET `balance`=balance-:bal WHERE ID=:id"
            );
            $bal_update->bindParam(":bal", $lose);
            $bal_update->bindParam(":id", $user->ID);
            $bal_update->execute();
            $bal_update = $db->prepare(
                "UPDATE `users` SET `balance`=balance+:bal WHERE ID=:id"
            );
            $bal_update->bindParam(":bal", $won);
            $bal_update->bindParam(":id", $enemy['ID']);
            $bal_update->execute();
        } else {
            // $user win
            $lose = n($user->balance * 0.01 * ($user->hp / 100));
            $won = n($lose * 0.9);
            $client->sendMessage($enemy['TG_id'], "lose");
            $client->sendMessage($user->TG_id, "win");
            $reply .=
                $user->username .
                " won " .
                n($won) .
                " " .
                $config->currency .
                "\n";
            $reply .=
                $enemy['username'] .
                " lost " .
                n($lose) .
                " " .
                $config->currency;
            $bal_update = $db->prepare(
                "UPDATE `users` SET `balance`=balance-:bal WHERE ID=:id"
            );
            $bal_update->bindParam(":bal", $lose);
            $bal_update->bindParam(":id", $enemy["ID"]);
            $bal_update->execute();
            $bal_update = $db->prepare(
                "UPDATE `users` SET `balance`=balance+:bal WHERE ID=:id"
            );
            $bal_update->bindParam(":bal", $won);
            $bal_update->bindParam(":id", $user->ID);
            $bal_update->execute();
        }
        $client->sendMessage($user->TG_id, $reply, "HTML");
        $client->sendMessage($enemy['TG_id'], $reply, "HTML");
        $update_user = $db->prepare("UPDATE `users` SET `hp`=:hp WHERE ID=:id");
        $update_user->bindParam(":id", $user->ID);
        $update_user->bindParam(":hp", $user->hp);
        $update_user->execute();
        $update_user = $db->prepare("UPDATE `users` SET `hp`=:hp WHERE ID=:id");
        $update_user->bindParam(":id", $enemy['ID']);
        $update_user->bindParam(":hp", $enemy['hp']);
        $update_user->execute();
    } else {
        $client->sendMessage($chat_id, "That battle would be unfair...");
        die();
    }
}
