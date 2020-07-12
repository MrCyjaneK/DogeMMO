<?php
// In this example I assume that you have an $user varible.
//$user = new user($user_id, 2);
// ... and included classes
//include getcwd() . "inc/class.php";

/*
  Let's assume that our user have
  item with in_shop_id equal to nice number.
*/
$item_owned_by_user = "69";

/*
  We will initialize item class like this
*/
$item_info = new item($item_owned_by_user);
// Now you can do whatever you like with that object :)

$capacity = $item_info->userCapacity($user->ID);
$attack = $item_info->attack;