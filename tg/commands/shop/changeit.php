<?php
$exploded = explode("_", $text);
if ($exploded[0] == "/changeit") {
    $user = user($chat_id);
    $check = $db->prepare(
        "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND token=:token AND ID=:id"
    );
    $check->bindParam(":owned_by", $user->ID);
    $check->bindParam(":token", $token);
    $check->bindParam(":id", $exploded[1]);
    $check->execute();
    $check = $check->fetchAll();

    if ($check == []) {
        $client->sendMessage($chat_id, "That's not your item.");
        die();
    }

    $shop = $db->prepare("SELECT * FROM `shop_items` WHERE ID=:in_shop_id");
    $shop->bindParam(":in_shop_id", $check[0]['in_shop_id']);
    $shop->execute();
    $shop = $shop->fetchAll();

    if ($shop[0]['can_eq'] == 0) {
        $client->sendMessage(
            $chat_id,
            "Equipping " .
                $shop[0]['name'] .
                " is not possible, you would look stupid with it in your hands"
        );
        die();
    }

    //$client->sendMessage($chat_id, print_r($check,1));
    if ($check[0]['in_eq'] != 1) {
        $allitems = $db->prepare(
            "SELECT * FROM `shop_items` WHERE token=:token AND type=:type"
        );
        $allitems->bindParam(":token", $token);
        $allitems->bindParam(":type", $shop[0]['type']);
        $allitems->execute();
        foreach ($allitems as $i) {
            $c = $db->prepare(
                "SELECT * FROM `user_items` WHERE owned_by=:owned_by AND token=:token AND in_shop_id=:in_shop_id"
            );
            $c->bindParam(":owned_by", $user->ID);
            $c->bindParam(":token", $token);
            $c->bindParam(":in_shop_id", $i['ID']);
            $c->execute();
            $c = $c->fetchAll();
            if ($c[0]['in_eq'] == 1) {
                $u = $db->prepare(
                    "UPDATE user_items SET `in_eq`=0 WHERE ID=:id"
                );
                $u->bindParam(":id", $c[0]['ID']);
                $u->execute();
                $client->sendMessage(
                    $user->TG_id,
                    "Unequipped " . print_r($i['name'], 1) . "."
                );
            }
        }

        $update_eq = $db->prepare(
            "UPDATE user_items SET `in_eq`=:in_eq WHERE ID=:id AND token=:token"
        );
        $update_eq->bindParam(":token", $token);
        $update_eq->bindParam(":id", $exploded[1]);
        $true = 1;
        $update_eq->bindParam(":in_eq", $true);
        $update_eq->execute();

        $client->sendMessage($chat_id, "Equipped");
    } else {
        /* Check if user already have item with same type */
        /*
	 $item_type = $db->prepare("SELECT * FROM `shop_items` WHERE ID=:in_shop_id");
	 $item_type->bindParam(":in_shop_id", $check[0]["in_shop_id"]);
	 $item_type->execute();
	 $item_type = $item_type->fetchAll();
	 $item_type = $item_type[0]['type'];
	 
	 $all_items = $db->prepare("SELECT * FROM `shop_items` WHERE type=:type");
	 $all_items->bindParam();
	 
	 $client->sendMessage($chat_id, print_r($check,1));
	 */
        /* END of check */
        $update_eq = $db->prepare(
            "UPDATE user_items SET `in_eq`=:in_eq WHERE ID=:id AND token=:token"
        );
        $update_eq->bindParam(":token", $token);
        $update_eq->bindParam(":id", $exploded[1]);
        $false = 0;
        $update_eq->bindParam(":in_eq", $false);
        $update_eq->execute();
        $client->sendMessage($chat_id, "Unequipped");
    }
    $update_user = $db->prepare(
        "UPDATE `users` SET `balance`=`balance`-:price WHERE token=:token AND ID=:id"
    );
    $update_user->bindParam(":price", $wanttobuy["price"]);
    $update_user->bindParam(":token", $token);
    $update_user->bindParam(":id", $user->ID);
    //$update_user->execute();
}
