<?php
chdir('..');
include getcwd() . '/inc/config.php';
include getcwd() . '/inc/db.php';
$input = file_get_contents('php://input');
$input = json_decode($input);
//print_r($input);
if (isset($input->username) && isset($input->password) && isset($input->password_enc) &&
    !empty($input->username) && !empty($input->password) && !empty($input->password_enc)) {
    $username = strtolower($input->username);
    $password = $input->password;
    $password_enc = $input->password_enc;
    $check = sha256(
        sha256(
            sha256($username)."|".sha256($password)
        ).'|'.
        sha256(
            sha256($username)."|".sha256($password)
        )
    );
    if ($check != $password_enc) {
        echo json_encode([
            "ok" => false,
            'message' => "Hashed password doesn't match! Please contact our support, or stop if you are just trying to be funny."
        ]);
        die();
    }

    $q = $db->prepare("SELECT * FROM `users` WHERE `username`=:username");
    $q->bindParam(":username", $username);
    $q->execute();
    $q = $q->fetchAll();
    if ($q != []) {
        echo json_encode([
            "ok" => false,
            'message' => "Oups! Accout with given username already exist"
        ]);
        die();
    }

    $q = $db->prepare("INSERT INTO `users`(`username`, `password`) VALUES (:username, :password)");
    $q->bindParam(":username", $username);
    $q->bindParam(":password", $password_enc);
    $q->execute();

    echo json_encode([
        'ok' => true,
        'message' => "Account created, you can now login."
    ]);
    die();
} else {
    echo json_encode([
        "ok" => false,
        'message' => "One of inputs is missing"
    ]);
    die();
}
function sha256($s) {
    return hash('sha256', $s);
}
