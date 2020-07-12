<?php
// In this example I assume that you have an $user varible.
//$user = new user($user_id, 2);
// ... and included classes
//include getcwd() . "inc/class.php";


$user_info = [
    "version" => rand(1,2), // Randomly pick login method
    "id" => 2,              // Database user ID
    "telegram_id" => 123123 // Telegrm user ID
];

try {
    switch ($user_info['version']) {
        case '1':
            // THIS SHOULD NOT BE USED USED AS IT IS @deprecated
            // Telegram provide way of checking if ID is correct
            include getcwd() . "/inc/check.php";
            $user = new user($user['telegram_id'],1);
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

// Add free coin to user balance
$user->balance = $user->balance + 1;

// Save data to database

$user->save();