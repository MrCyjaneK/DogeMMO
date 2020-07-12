<?php
// TODO: Make this code shorter, use loops instead of copy pasted code.
$it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
$it->execute();
$it = $it->fetchAll();
$itemtypes = [];
$taglist = [];
foreach ($it as $item) {
    $itemtypes[$item['ID']] = $item['type'];
    $taglist[$item['ID']] = $item['tag'];
}
?>
<section class="bc-main">
    <div class="bc-main-content">
    <img src="<?= WEB ?>/img/dogecoin.svg" width="102" height="102">
<?php
$id = $_GET['id'];
$enemy = $db->prepare(
    "SELECT * FROM `users` WHERE canfight=1 AND ID=:real_id AND ID<>:id AND hp=100"
);
$enemy->bindParam(":real_id", $id);
$enemy->bindParam(":id", $user->ID);
$enemy->execute();
$enemy = $enemy->fetchAll();
if ($enemy == []) {
    $e = getString("Unknown user",$user->langcode);
    echo "<script>window.location.href='".WEB."/inc/soft_error.php?e=" .
        urlencode($e) .
        "';</script>";
    die();
}
$enemy = $enemy[0];
$allowed_levels = [$user->lvl - 1, $user->lvl, $user->lvl + 1, $user->lvl + 2];
if ($user->hp != 100) {
    ?>
    <script>
    window.location.href = "<?= WEB ?>/inc/soft_error.php?e=".urlencode(getString("You don't have enough hp!",$user->langcode));
    </script>
    <?php
    die();
}
if (in_array($enemy["lvl"], $allowed_levels)) {
    /* Build profiles */
    /*
		$fighter[0] // $user
		$fighter[1] // $enemy
    */
    $fighter = [];
    // $fighter[0] // $user

    $user_items = $db->prepare(
        "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND in_eq=1"
    );
    $user_items->bindParam(":owned_by", $user->ID);
    $user_items->execute();
    $user_items = $user_items->fetchAll();

    $totalattack = 0;
    $totaldefense = 0;
    $totalspeed = 0;
    $totalweight = 0;
    $weapons = [];
    $magic = [];
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
        $magic[$iteminfo['ID']] = $iteminfo['magic'];
    }

    if ($totalweight < 0) {
        $totalweight = 0.1;
    }
    if ($totaldefense < 0) {
        $totaldefense = 0.1;
    }
    if ($totalspeed < 0) {
        $totalspeed = 0.1;
    }
    if ($totalweight < 0) {
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
        "weapons" => $weapons,
        "magic" => $magic
    ];

    // $fighter[1] // $enemy

    $user_items = $db->prepare(
        "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND in_eq=1"
    );
    $user_items->bindParam(":owned_by", $enemy["ID"]);
    $user_items->execute();
    $user_items = $user_items->fetchAll();

    $totalattack = 0;
    $totaldefense = 0;
    $totalspeed = 0;
    $totalweight = 0;
    $weapons = [];
    $magic = [];
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
        $magic[$iteminfo['ID']] = $iteminfo['magic'];
    }

    if ($totalweight < 0) {
        $totalweight = 0.1;
    }
    if ($totaldefense < 0) {
        $totaldefense = 0.1;
    }
    if ($totalspeed < 0) {
        $totalspeed = 0.1;
    }
    if ($totalweight < 0) {
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
        "weapons" => $weapons,
        "magic" => $magic
    ];

    foreach ($fighter as $key => $fig) {
        if (!is_array($fig)) continue;
        foreach ($fig as $ke => $fi) {
            if (!is_array($fi)) continue;
            foreach ($fi as $k => $f) {
                if (!is_numeric($f)) continue;
                $fighter[$key][$ke][$k] = $f*(rand(70,150)/100);
            }
        }
    }
    //print_r($fighter);
    $enatc = 0;
    $usratc = 0;
    while ($user->hp > 0 && $enemy['hp'] > 0) {
        $totspeed =
            $fighter[0]['powers']['speed'] * 1000 +
            $fighter[1]['powers']['speed'] * 1000;


        if (rand(0, $totspeed) <= $fighter[1]['powers']['speed'] * 1000) {
            // $enemy attack
            $hit =
                $fighter[1]['powers']['attack'] * rand(0, 5) -
                $fighter[0]['powers']['defense'] * rand(0, 5);
            if ($hit < 0.0001) {
                $hit = 0.0001;
            }
            $enatc++;
            $user->hp = $user->hp - $hit;
        } else {
            // $user attack
            $hit =
                $fighter[0]['powers']['attack'] * rand(0, 5) -
                $fighter[1]['powers']['defense'] * rand(0, 5);
            if ($hit < 0.0001) {
                $hit = 0.0001;
            }
            $usratc++;
            $enemy['hp'] = $enemy['hp'] - $hit;
        }
    }
    $reply = getString("BATTLE->PVP->RESULTS",$user->langcode)."\n";
    //$reply .=
    //    $user->username .
    //    " made " .
    //    $usratc .
    //    " and " .
    //    $enemy['username'] .
    //    " made " .
    //    $enatc .
    //    " hits\n";
    //$reply .=
    //    "Attacker: " .
    //    $user->username .
    //    " (/fight_" .
    //    $user->ID .
    //    ") arrived on the battlefield with\n" .
    //    "" .
    //    join("\n", $fighter[0]['weapons']) .
    //    "\n" .
    //    "Defender was attacked with\n" .
    //    "" .
    //    join("\n", $fighter[1]['weapons']) .
    //    "\n\n\n" .
    //    "💙" .
    //    $user->username .
    //    " " .
    //    round($user->hp) .
    //    "hp\n" .
    //    "💙" .
    //    $enemy['username'] .
    //    " " .
    //    round($enemy['hp']) .
    //    "hp\n";
    if ($user->hp < 0) {
        // $user lose
        $lose = n($enemy['balance'] * 0.1 * ($enemy['hp'] / 100));
        $won = n($lose * 1.1);
        $reply = $enemy['username'] . ' ' . getString("was stronger than",$user->langcode) . ' ' . $user->username . ' ' . getString("and have won",$user->langcode).n($lose).$config->currency;
        //$client->sendMessage($enemy['TG_id'], "win");
        //$client->sendMessage($user->TG_id, "lose");
        //$reply .=
        //    $user->username .
        //    " lost " .
        //    n($won) .
        //    " " .
        //    $config->currency .
        //    "\n";
        //$reply .=
        //    $enemy['username'] . " won " . n($lose) . " " . $config->currency;
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
        //$reply .=
        //    $user->username .
        //    " won " .
        //    n($won) .
        //    " " .
        //    $config->currency .
        //    "\n";
        //$reply .=
        //    $enemy['username'] . " lost " . n($lose) . " " . $config->currency;
        $reply = $user->username . getString("BATTLE->PVP->WAS_STRONGER_THAN",$user->langcode) . $enemy['username'] .getString("BATTLE->PVP->AND_HAVE_WON",$user->langcode).n($won).$config->currency;
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
    $client->sendMessage($enemy['TG_id'], "You are under attack!");
    $client->sendMessage($enemy['TG_id'], $reply, "HTML");
    $update_user = $db->prepare("UPDATE `users` SET `hp`=:hp WHERE ID=:id");
    $update_user->bindParam(":id", $user->ID);
    $update_user->bindParam(":hp", $user->hp);
    $update_user->execute();
    $update_user = $db->prepare("UPDATE `users` SET `hp`=:hp WHERE ID=:id");
    $update_user->bindParam(":id", $enemy['ID']);
    $update_user->bindParam(":hp", $enemy['hp']);
    $update_user->execute();
    echo str_replace(
        "\n",
        "<br />",
        '<p algin="left">' .
        $reply .
        '</p>'

    );
} else {
    $e = getString("BATTLE->PVP->UNFAIR",$user->langcode);
    echo "<script>window.location.href='/inc/soft_error.php?e=" .
        urlencode($e) .
        "';</script>";
    die();
}
?>
	<a style="width:100%" href="/game.php?action=battle/pvp/search" class="bc-bot-open-btn"><?php echo getString("BATTLE->PVP->SEARCH",$user->langcode); ?></a><br />
  </div>
</section>
