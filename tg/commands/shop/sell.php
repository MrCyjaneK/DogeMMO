<?php
$exploded = explode("_", $text);
if ($exploded[0] == "/sell") {
    $chat_id = $client->easy->chat_id;
    $message_id = $client->easy->message_id;
    $text = $client->easy->text;
    $name = $client->easy->first_name;
    $user = user($chat_id, $name);
    $amount = 1;
    $expl = explode(' ', $text);
    if (count($expl) > 1 && is_numeric($expl[1])) {
        $amount = $expl[1];
    }
    $wanttosell = [
        "ID" => $exploded[1],
        "amount" => $amount
    ];
    $useritems = $db->prepare(
        "SELECT * FROM `user_items` WHERE owned_by=:owned AND token=:token AND ID=:id"
    );
    $useritems->bindParam(":owned", $user->ID);
    $useritems->bindParam(":token", $token);
    $useritems->bindParam(":id", $wanttosell['ID']);
    $useritems->execute();
    $useritems = $useritems->fetchAll();
    if ($useritems == []) {
        $client->sendMessage(
            $chat_id,
            "I'm quite sure that you don't have thing like this",
            "HTML"
        );
        die();
    }

    $shopitems = $db->prepare(
        "SELECT * FROM `shop_items` WHERE token=:token AND ID=:id"
    );
    $shopitems->bindParam(":token", $token);
    $shopitems->bindParam(":id", $useritems[0]["in_shop_id"]);
    $shopitems->execute();
    $shopitems = $shopitems->fetchAll();

    if ($amount > $useritems[0]['capacity']) {
        $client->sendMessage($chat_id, "It's too much...");
        die();
    }

    $pricesold = $shopitems[0]['price'] * $amount * 0.5;
    $string = "Sold for " . n($pricesold) . " " . $config->currency;

    if ($useritems[0]['capacity'] - $amount <= 0) {
        $delete = $db->prepare("DELETE FROM `user_items` WHERE ID=:id");
        $delete->bindParam(":id", $useritems[0]['ID']);
        $delete->execute();
        $string .= "\nThat was last " . $shopitems[0]['name'] . " you had.";
    } else {
        $update = $db->prepare(
            "UPDATE `user_items` SET `capacity`=capacity-:totalsold WHERE ID=:id"
        );
        $update->bindParam(":id", $useritems[0]['ID']);
        $update->bindParam(":totalsold", $amount);
        $update->execute();
    }
    $update_balance = $db->prepare(
        "UPDATE `users` SET `balance`=balance+:pricesold WHERE ID=:id"
    );
    $update_balance->bindParam(":pricesold", $pricesold);
    $update_balance->bindParam(":id", $user->ID);
    $update_balance->execute();
    $client->sendMessage($chat_id, $string, "HTML");
    //$client->sendMessage($chat_id, '$useritems: '.print_r($useritems,1),"HTML",null,null,null);
    //$client->sendMessage($chat_id, '$shopitems: '.print_r($shopitems,1),"HTML",null,null,null);
}
