<?php
if ($argv[1] != "secret") {
    die("Ummm.. how about no?");
}
require_once __DIR__ . "/vendor/autoload.php";
include 'inc/db.php';
include 'inc/fn.php';
include 'inc/strings.php';
include "inc/user.php";
require_once 'inc/easybitcoin.php';
use TuriBot\Client;
/* D E P O S I T S */
$deposits = $db->prepare("SELECT * FROM `users` WHERE lvl>4");
$deposits->execute();
$deposits = $deposits->fetchAll();
foreach ($deposits as $deposit) {
    $token = $deposit['token'];
    include "inc/cur.php";
    $bitcoin = new Bitcoin($rpcuser, $rpcpass, $rpchost, $rpcport);
    $balance = $bitcoin->getreceivedbyaddress($deposit['depositaddress']);
    // Make sure that you have deposited...
    if ($balance > 0) {
        // Credit balance to user
        $credit = $db->prepare(
            "UPDATE `users` SET `balance`=`balance`+:balance, depositaddress=:depoadd WHERE ID=:id"
        );
        $credit->bindParam(":balance", $balance);
        $depoadd = $bitcoin->getnewaddress();
        $credit->bindParam(":depoadd", $depoadd);
        $credit->bindParam(":id", $deposit['ID']);
        $credit->execute();
        // Fetch bot token
        $config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
        $config->bindParam(":token", $deposit['token']);
        $config->execute();
        $config = $config->fetchObject();
        // Tell user that we have received his deposit
        $client = new Client($config->apikey, false);
        $user = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
        $user->bindParam(":id", $deposit['ID']);
        $user->execute();
        $user = $user->fetchObject();
        $client->sendMessage(
            $user->TG_id,
            "Deposit of " .
                n($balance) .
                " " .
                $config->currency .
                " got credited. Your old address is not valid anymore, use new address: <code>$depoadd</code>",
            "HTML"
        );
    }
}
/* Q U E S T S */
$quests = $db->prepare("SELECT * FROM `active_quests`");
$quests->execute();
$quests = $quests->fetchAll();
foreach ($quests as $quest) {
    // Fetch bot configuration
    // NOTE: It should be moved to place when we send notifcation to user
    $config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
    $config->bindParam(":token", $quest['token']);
    $config->execute();
    $config = $config->fetchObject();
    $client = new Client($config->apikey, false);
    // Fetch info about user
    $user = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $user->bindParam(":id", $quest['user_id']);
    $user->execute();
    $user = $user->fetchObject();
    // Fetch info about bot configuration
    $questconfig = $db->prepare("SELECT * FROM `quests` WHERE ID=:id");
    $questconfig->bindParam(":id", $quest['quest_id']);
    $questconfig->execute();
    $questconfig = $questconfig->fetchObject();
    // If quest was started from web interface add 5 minutes more time,
    //because web have its own quest handling method
    if ($quest['isweb'] == 1) {
        $quest['timestarted'] = $quest['timestarted'] + 300;
    }
    // Quest is finished
    if ($quest["timestarted"] + 60 * $questconfig->minutes - time() < 0) {
        // Fetch quests_text
        $text = $db->prepare(
            "SELECT * FROM `quests_text` WHERE token=:token AND reward_from=:rewards_items"
        );
        $text->bindParam(":token", $config->token);
        $text->bindParam(":rewards_items", $questconfig->rewards_items);
        $text->execute();
        $text = $text->fetchAll();
        // ...and randomize it
        $text = print_r($text[array_rand($text)]['string'], 1);
        // Fetch all possible rewards
        $quest_rewards = explode("|", $questconfig->rewards_items);
        // And make array of items that user will receive
        $rewarded_stuff = [];
        foreach ($quest_rewards as $key => $qreward) {
            // What can we reward?
            // Fetch all rewards
            $rewards = $db->prepare(
                "SELECT * FROM shop_items WHERE inshop=:inshop AND token=:token"
            );
            $rewards->bindParam(":token", $config->token);
            $rewards->bindParam(":inshop", $qreward);
            $rewards->execute();
            $rewards = $rewards->fetchAll();
            // Get chance of reward
            $chance = explode("|", $questconfig->rewards_chance)[$key];
            // There is hard-coded max number of items from one category that can be rewarded
            //It's 10, and there are 10 $chance% of getting item.
            for ($i = 1; $i <= 10; $i++) {
                if ($chance > rand(0, 100)) {
                    $rand_reward_id = $rewards[array_rand($rewards)];
                    $rand_reward = $rewards[array_rand($rewards)];
                    $rewarded_stuff[$rand_reward['ID']] = [
                        "text" => $rand_reward['emoji'] . $rand_reward['name'],
                        "total" =>
                            round(
                                $rewarded_stuff[$rand_reward['ID']]['total']
                            ) + 1,
                        "inshop" => $rand_reward['inshop']
                    ];
                }
            }
        }
        // Generate text
        $rewardtext = "<b>Received items:</b>\n";
        foreach ($rewarded_stuff as $key => $value) {
            $rewardtext .=
                "<code>    - </code>" .
                $value['text'] .
                " - " .
                $value['total'];
            // Insert reward to user inventory
            //TODO: add merge for existing items
            //(use UPDATE and insert only if needed)
            $insertreward = $db->prepare(
                "INSERT INTO `user_items`(`in_shop_id`, `in_shop_name`, `owned_by`, `capacity`, `token`) VALUES (:in_shop_id, :in_shop_name, :owned_by, :capacity, :token)"
            );
            $insertreward->bindParam(":in_shop_id", $key);
            $insertreward->bindParam(":in_shop_name", $value['inshop']);
            $insertreward->bindParam(":owned_by", $user->ID);
            $insertreward->bindParam(":capacity", $value['total']);
            $insertreward->bindParam(":token", $config->token);
            $insertreward->execute();
        }
        $addxp = $questconfig->minutes * rand(1, 3) * 0.130419;
        $strtosend =
            "<b>Quest " .
            $questconfig->name .
            " completed</b>\n" .
            "$text\n" .
            $rewardtext .
            " /inv\n🏆xp: " .
            $addxp;
        // Update user XP
        $updatexp = $db->prepare(
            "UPDATE `users` SET `xp`=xp+:addxp WHERE ID=:id"
        );
        $updatexp->bindParam(":id", $user->ID);
        $updatexp->bindParam(":addxp", $addxp);
        $updatexp->execute();
        // Quest completed, delete info from active_quests
        $delete = $db->prepare("DELETE FROM `active_quests` WHERE ID=:id");
        $delete->bindParam(":id", $quest['ID']);
        $delete->execute();
        $client->sendMessage($user->TG_id, $strtosend, "HTML");
    }
}
/* Merge all duplicated items to one */
// This is hot-fix
// It is suposed to merger all items that are >1 times in inventory to one
$allitems = $db->prepare("SELECT * FROM `user_items` WHERE 1=1");
$allitems->execute();
$allitems = $allitems->fetchAll();
$done = [];
foreach ($allitems as $item) {
    if (in_array($item['ID'], $done)) {
    } else {
        $total_start = $item['capacity'];
        $getall = $db->prepare(
            "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND token=:token AND in_shop_id=:in_shop_id"
        );
        $getall->bindParam(":owned_by", $item['owned_by']);
        $getall->bindParam(":token", $item['token']);
        $getall->bindParam(":in_shop_id", $item["in_shop_id"]);
        $getall->execute();
        $getall = $getall->fetchAll();
        $total = 0;
        $ids = [];
        foreach ($getall as $one) {
            $ids[] = $one["ID"];
            $done[] = $one['ID'];
            $total += $one['capacity'];
        }
        if ($total != $total_start) {
            foreach ($ids as $id) {
                $delete = $db->prepare("DELETE FROM `user_items` WHERE id=:id");
                $delete->bindParam(":id", $id);
                $delete->execute();
            }
            $insert = $db->prepare(
                "INSERT INTO `user_items`(`in_shop_id`, `in_shop_name`, `owned_by`, `capacity`, `token`) VALUES ( :in_shop_id, :in_shop_name, :owned_by, :capacity, :token)"
            );
            $insert->bindParam(":owned_by", $item['owned_by']);
            $insert->bindParam(":token", $item['token']);
            $insert->bindParam(":in_shop_id", $item["in_shop_id"]);
            $insert->bindParam(":in_shop_name", $item["in_shop_name"]);
            $insert->bindParam(":capacity", $total);
            $insert->execute();
        }
    }
}
/* Update user's level */
$allusers = $db->prepare("SELECT * FROM `users` WHERE 1=1");
$allusers->execute();
$allusers = $allusers->fetchAll();
foreach ($allusers as $user) {
    $levels = $db->prepare("SELECT * FROM `levels` WHERE token=:token");
    $levels->bindParam(":token", $user['token']);
    $levels->execute();
    $levels = $levels->fetchAll();
    foreach ($levels as $level) {
        if ($user['xp'] > $level['min_xp']) {
            if ($user['lvl'] < $level['lvl_id']) {
                $updateuser = $db->prepare(
                    "UPDATE `users` SET `lvl`=:lvl WHERE ID=:id"
                );
                $updateuser->bindParam(":id", $user['ID']);
                $updateuser->bindParam(":lvl", $level['lvl_id']);
                $updateuser->execute();
                $config = $db->prepare(
                    "SELECT * FROM `botconfig` WHERE token=:token"
                );
                $config->bindParam(":token", $quest['token']);
                $config->execute();
                $config = $config->fetchObject();
                $client = new Client($config->apikey, false);
                $client->sendMessage(
                    $chat_id,
                    "You are now level " . round($level['lvl_id'])
                );
            }
        }
    }
}
/* H E A L   U S E R S */
$db->query("UPDATE `users` SET `hp`=hp+ROUND(RAND() * (2), 4) WHERE hp<100");
$notify = $db->prepare("SELECT * FROM `users` WHERE hp>100");
$notify->execute();
$notify = $notify->fetchAll();
foreach ($notify as $user) {
    $config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
    $config->bindParam(":token", $user['token']);
    $config->execute();
    $config = $config->fetchObject();
    $client = new Client($config->apikey, false);
    $client->sendMessage($user['TG_id'], "Your hp is now full");
}
$db->query("UPDATE `users` SET `hp`=100 WHERE hp>100");
