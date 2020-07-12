<?php
chdir("..");
header("Access-Control-Allow-Origin: *");
session_name('st34lm3');
session_start();
require_once getcwd() . "/tg/vendor/autoload.php";
use TuriBot\Client;
include getcwd() . '/inc/config.php';
include getcwd() . '/inc/strings.php';
include getcwd() . '/inc/db.php';
include getcwd() . '/inc/translation.php';
$token = '690195901';
$config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
$config->bindParam(":token", $token);
$config->execute();
$config = $config->fetchObject();
include getcwd() . "/inc/class.php";
try {
    switch ($_SESSION['version']) {
        case '1':
            include getcwd() . "/inc/check.php";
            $user = new user($_SESSION['user']['id'],1);
            break;

        case '2':
            $user = new user($_SESSION['ID'],2);
            break;

        default:
            die("Critical error, case not provided.");
            break;
    }
} catch (Exception $e) {
    die(print_r($e));
}

$quests = $db->prepare(
    "SELECT * FROM `active_quests` WHERE user_id=:user_id AND isweb=1"
);
$quests->bindParam(":user_id", $user->ID);
$quests->execute();
$quests = $quests->fetchAll();
if (isset($_SESSION['cancel']) && $_SESSION['cancel'] == 'cancel') {
    echo "cancel";
    $_SESSION['cancel'] = "a";
    die();
}
//print_r($quests);
foreach ($quests as $quest) {
    //print_r($quest);
    $config = $db->prepare("SELECT * FROM `botconfig` WHERE token=:token");
    $config->bindParam(":token", $quest['token']);
    $config->execute();
    $config = $config->fetchObject();
    $client = new Client($config->apikey, false);
    $user = $db->prepare("SELECT * FROM `users` WHERE ID=:id");
    $user->bindParam(":id", $quest['user_id']);
    $user->execute();
    $user = $user->fetchObject();
    $questconfig = $db->prepare("SELECT * FROM `quests` WHERE ID=:id");
    $questconfig->bindParam(":id", $quest['quest_id']);
    $questconfig->execute();
    $questconfig = $questconfig->fetchObject();
    if ($quest["timestarted"] + 60 * $questconfig->minutes - time() < 0) {
        $textq = $db->prepare(
            "SELECT * FROM `quests_text` WHERE token=:token AND reward_from=:rewards_items"
        );
        $textq->bindParam(":token", $config->token);
        $textq->bindParam(":rewards_items", $questconfig->rewards_items);
        $textq->execute();
        $textq = $textq->fetchAll();
        $randid = array_rand($textq);
        $txt = $textq[$randid]['string'];
        $name = "QUEST->TEXT->".$textq[$randid]['ID'];
        $quest_rewards = explode("|", $questconfig->rewards_items);
        $zero = 0;
        $text = getString($name,$user->langcode,$zero,$txt);
        $rewarded_stuff = [];
        foreach ($quest_rewards as $key => $qreward) {
            $rewards = $db->prepare(
                "SELECT * FROM shop_items WHERE inshop=:inshop AND token=:token"
            );
            $rewards->bindParam(":token", $config->token);
            $rewards->bindParam(":inshop", $qreward);
            $rewards->execute();
            $rewards = $rewards->fetchAll();
            $chance = explode("|", $questconfig->rewards_chance)[$key];
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
        $rewardtext = "<b>Received items:</b>\n";
        foreach ($rewarded_stuff as $key => $value) {
            try {
                $rewardtext .=
                    "<code>    - </code>" .
                    $value['text'] .
                    " - " .
                    $value['total'];
                $insertreward = $db->prepare(
                    "INSERT INTO `user_items`(`in_shop_id`, `in_shop_name`, `owned_by`, `capacity`, `token`) VALUES (:in_shop_id, :in_shop_name, :owned_by, :capacity, :token)"
                );
                $insertreward->bindParam(":in_shop_id", $key);
                $insertreward->bindParam(":in_shop_name", $value['inshop']);
                $insertreward->bindParam(":owned_by", $user->ID);
                $insertreward->bindParam(":capacity", $value['total']);
                $insertreward->bindParam(":token", $config->token);
                $insertreward->execute();
            } catch (Exception $e) {
                echo "An error occured (It's normal in development version)\n\nMost likely you haven't received your reward, but it shouldn't be problem, in production it works.\n";
            }
        }
        $addxp = $questconfig->minutes * rand(1, 3) * 0.130419;
        $strtosend =
            getString("QUEST->NOTIF->QUEST",$user->langcode,0,"Quest")." " .
            $questconfig->name .
            " ".
            getString("QUEST->NOTIF->COMPLETED",$user->langcode,0,"completed").
            "\n" .
            "$text\n" .
            $rewardtext .
            "\n🏆xp: " .
            $addxp;
        echo $strtosend;
        $updatexp = $db->prepare(
            "UPDATE `users` SET `xp`=xp+:addxp WHERE ID=:id"
        );
        $updatexp->bindParam(":id", $user->ID);
        $updatexp->bindParam(":addxp", $addxp);
        $updatexp->execute();
        $delete = $db->prepare("DELETE FROM `active_quests` WHERE ID=:id");
        $delete->bindParam(":id", $quest['ID']);
        $delete->execute();
        $_SESSION['cancel'] = "cancel";
    } else {
        echo getString("QUEST->NAME->".$questconfig->ID,$user->langcode,0,$questconfig->name) .
            " " .
            gmdate(
                "H:i:s",
                $quest["timestarted"] + 60 * $questconfig->minutes - time()
            );
    }
    die();
}
echo "false";
