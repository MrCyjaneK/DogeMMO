<?php

//increase this:
// $TOTAL_HOURS_WASTED_HERE = 7;

require_once __DIR__ . "/vendor/autoload.php";
include 'inc/db.php';
include 'inc/fn.php';
include 'inc/strings.php';
include "inc/user.php";
use TuriBot\Client;
//if (!isset($_GET["api"])) {
//    die();
//}
$bots = $db->prepare("SELECT * FROM `botconfig` WHERE 1");
$bots->execute();
$bots = $bots->fetchAll();
foreach ($bots as $bot) {
    $token = $bot['token'];
    $name = $bot['name'];
    $api = $bot['apikey'];
    $log = $bot['chat'];
    $client = new Client($api, false);
    $message = $client->sendMessage($log, "Battle is comming...");
    $chat_id = $message->result->chat->id;
    $message_id = $message->result->message_id;
    $text = "Battle is over, results:";

    $users = $db->prepare(
        "SELECT * FROM `users` WHERE token=:token AND canfight=1"
    );
    $users->bindParam(":token", $token);
    $users->execute();
    $users = $users->fetchAll();

    $teampower = [];

    $teams = $db->prepare("SELECT * FROM `teams` WHERE token=:token");
    $teams->bindParam(":token", $token);
    $teams->execute();
    $teams = $teams->fetchAll();

    foreach ($teams as $team) {
        $teampower[$team['ID']] = [
            "name" => $team['emoji'] . $team['name'],
            "attack" => 0,
            "defense" => 0,
            "hp" => 100 //10000
        ];
    }
    //$users = shuffle($users);
    //print_r($users);
    foreach ($users as $user) {
        if ($user['fightfor'] == 0) {
            continue;
        }
        if (empty($user['teamid'])) {
            continue;
        }
        $user_items = $db->prepare(
            "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND token=:token AND in_eq=1"
        );
        $user_items->bindParam(":owned_by", $user['ID']);
        $user_items->bindParam(":token", $token);
        $user_items->execute();
        $user_items = $user_items->fetchAll();

        $totalattack = 0;
        $totaldefense = 0;
        $totalspeed = 0;
        $totalweight = 0;

        foreach ($user_items as $item) {
            $iteminfo = $db->prepare(
                "SELECT * FROM `shop_items` WHERE ID=:in_shop_id"
            );
            $iteminfo->bindParam(":in_shop_id", $item['in_shop_id']);
            $iteminfo->execute();
            $iteminfo = $iteminfo->fetchAll();
            $iteminfo = $iteminfo[0];
            $totalattack += $iteminfo["attack"] * $item['capacity'];
            $totaldefense += $iteminfo["defense"] * $item['capacity'];
            $totalspeed += $iteminfo["speed"] * $item['capacity'];
            $totalweight += $iteminfo["weight"] * $item['capacity'];
        }

        if ($totalweight == 0) {
            $totalweight = 0.01;
        }
        if ($totaldefense == 0) {
            $totaldefense = 0.01;
        }
        if ($totalspeed == 0) {
            $totalspeed = 0.01;
        }
        if ($totalweight == 0) {
            $totalweight = 0.01;
        }

        if ($user['fightfor'] != $user['teamid']) {
            $teampower[$user['fightfor']]['attack'] += $totalattack;
        } else {
            $teampower[$user['fightfor']]['defense'] += $totaldefense;
        }
        $teampower[$user['teamid']]['hp'] += $user['hp'];
    }
    foreach ($teampower as $key => $value) {
        $teampower[$key]['attack'] =
            $teampower[$key]['attack'] * (rand(50, 150) / 100);
        $teampower[$key]['defense'] =
            $teampower[$key]['defense'] * (rand(50, 150) / 100);
        $teasmpower[$key]['hp'] =
            $teampower[$key]['hp'] * (rand(50, 150) / 100);
        $teampower[$key]['hp_start'] = $teampower[$key]['hp'];
    }

    //$text = print_r($teampower,1);

    $text = "<b>Battle</b>\n";

    foreach ($teampower as $team) {
        $text .=
            "(" .
            $team['hp'] .
            ") " .
            $team['name'] .
            " have been attacked (" .
            $team['attack'] .
            "), and were defending (" .
            $team['defense'] .
            ")\n";
    }

    /* LET THE BATTLE BEGIN */

    // Stage 1, pick winning and losing castles

    $fight = true;
    $round = 0;
    $maxround = rand(50, 69);
    while ($fight) {
        $round++;
        if ($maxround < $round) {
            break;
        }
        foreach ($teampower as $key => $team) {
            if ($team['hp'] < $team['hp_start'] / 2) {
                $teampower[$key]['state'] = "LOSE";
                continue;
            } else {
                $teampower[$key]['state'] = "DEFENDED";
            }
            if ($team['hp'] > $team['hp_start'] * 0.85) {
                $teampower[$key]['state'] = "WON";
            }
            if ($teampower[$key]['attack'] == 0) {
                continue;
            }
            if ($teampower[$key]['defense'] == 0) {
                $teampower[$key]['hp'] = -1;
                continue;
            }
            $def = $team['defense'] * rand(0, 2);
            if ($def == 0) {
                $def = 1;
            }
            $teampower[$key]['hp'] -=
                (0.01 + $teampower[$key]['attack'] * rand(0, 2)) / $def;
        }
    }

    //$text .= print_r($teampower,1);

    // Show final info

    foreach ($teampower as $team) {
        if ($team['state'] == "LOSE") {
            $t = "have lost the battle";
        }
        if ($team['state'] == "DEFENDED") {
            $t = "have defended their castle";
        }
        if ($team['state'] == "WON") {
            $t = "have won the battle";
        }
        $text .= $team['name'] . " $t. \n";
    }

    // Pay the users
    $total_took = 0;
    // 0 - Take money

    foreach ($users as $user) {
        if ($teampower[$user['teamid']]['state'] != "LOSE") {
            continue;
        }
        $took = $user['balance'] * (rand(1, 2) / 100);
        $newbal = $user['balance'] - $took;
        $total_took += $took;
        if ($took > 0.0000001) {
            $client->sendMessage(
                $user['TG_id'],
                "On the battlefield you have lost " .
                    n($took) .
                    " " .
                    $bot['currency'],
                "HTML"
            );
            //$client->sendMessage($log, $user['username'].": On the battlefield you have lost ".n($took)." ".$bot['currency'], "HTML");
        }
        $totalbal = $db->prepare(
            "UPDATE `users` SET `balance`=:bal WHERE `ID`=:id AND token=:token"
        );
        $totalbal->bindParam(":token", $token);
        $totalbal->bindParam(":bal", $newbal);
        $totalbal->bindParam(":id", $user["ID"]);
        $totalbal->execute();
    }
    $text .= "\nIn the battle, all 3 castles have lost $total_took ";
    // 1 - Give it to better

    $allusers = $db->prepare(
        "SELECT * FROM `users` WHERE token=:token AND fightfor<>0"
    );
    $allusers->bindParam(":token", $token);
    $allusers->execute();
    $allusers = $allusers->fetchAll();

    $toreward = [];
    $tonotif = [];
    foreach ($allusers as $user) {
        if (
            $teampower[$user['fightfor']]['state'] == "WON" &&
            $user['fightfor'] != $user['teamid']
        ) {
            $client->sendMessage(
                $user['TG_id'],
                "Sadly, " .
                    $teampower[$user['fightfor']]['name'] .
                    " have won the battle so you have not received your reward",
                "HTML"
            );
            //$client->sendMessage($log, $user['username'].": Sadly, ".$teampower[$user['fightfor']]['name']." have won the battle so you have not received your reward", "HTML");
            continue;
        }
        if (
            $teampower[$user['fightfor']]['state'] == "DEFENDED" &&
            $user['fightfor'] != $user['teamid']
        ) {
            $client->sendMessage(
                $user['TG_id'],
                "Sadly, " .
                    $teampower[$user['fightfor']]['name'] .
                    " have defended their castle so you have not received your reward",
                "HTML"
            );
            //$client->sendMessage($log, $user['username'].": Sadly, ".$teampower[$user['fightfor']]['name']." have defended their castle so you have not received your reward", "HTML");
            continue;
        }
        if ($user['fightfor'] == 0) {
            continue;
        }
        for ($i = 0; $i <= $user['lvl']; $i++) {
            $toreward[] = [
                'ID' => $user["ID"],
                'TG_id' => $user["TG_id"],
                'reward' => 0
            ];
            $tonotif[$user['ID']] = [];
            $tonotif[$user['ID']] = [
                'TG_id' => $user["TG_id"],
                'reward' => 0
            ];
        }
    }
    // Pay....

    $perone = n(($total_took * 0.9) / count($toreward));
    foreach ($toreward as $key => $lucky) {
        $tonotif[$lucky['ID']]['reward'] += $perone;
        $bal = $db->prepare(
            "UPDATE `users` SET `balance`=balance+:bal WHERE `ID`=:id AND token=:token"
        );
        $bal->bindParam(":token", $token);
        $bal->bindParam(":bal", $perone);
        $bal->bindParam(":id", $lucky["ID"]);
        $bal->execute();
    }

    foreach ($tonotif as $tn) {
        $client->sendMessage(
            $tn['TG_id'],
            "On the battle you have earned " .
                n($tn['reward']) .
                " " .
                $bot['currency'],
            "HTML"
        );
        //$client->sendMessage($log, $tn['username'].": On the battle you have earned ".n($tn['reward'])." ".$bot['currency'], "HTML");
    }

    /*  LET THE BATTLE END  */

    $client->editMessageText($chat_id, $message_id, null, $text, "HTML", true);
}

$db->query("UPDATE `users` SET `fightfor`=0 WHERE 1");
