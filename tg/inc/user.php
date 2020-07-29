<?php
// Fetch use info by telegram id
function user($id, $name = 'not provided', $lastname = 'null', $username = null)
{
    global $db;
    global $token;
    global $text;
    global $__DIR__;
    $inviter = 0;
    if (strlen($text) > 10) {
        $txxt = explode(" ", $text);
        $inviter = $txxt[1];
    }
    $user = $db->prepare(
        "SELECT * FROM `users` WHERE `TG_id`=:id and `token`=:token"
    );
    $user->bindParam(':id', $id);
    $user->bindParam(':token', $token);
    $user->execute();
    $user = $user->fetchObject();
    if (empty($user)) {
        $user = $db->prepare("
INSERT
INTO
  `users`(
  `TG_id`,
  `token`,
  `inviter`,
  `depositaddress`,
  `balance`,
  `username`
  )
VALUES(
  :id,
  :token,
  :inviter,
  :depositadd,
  :bal,
  :username
)");
        $gen_uname = substr(hash('gost', $name . time()), 0, 15);
        $user->bindParam(":username", $gen_uname);
        $user->bindParam(':id', $id);
        $user->bindParam(':token', $token);
        $user->bindParam(':inviter', $inviter);
        $zero = 0;
        $user->bindParam(':bal', $zero);
        $add = 'error occured';
        require_once $__DIR__ . '/inc/easybitcoin.php';
        include $__DIR__ . '/inc/cur.php';
        $bitcoin = new Bitcoin($rpcuser, $rpcpass, $rpchost, $rpcport);
        $add = $bitcoin->getnewaddress();
        $user->bindParam(':depositadd', $add);
        $user->execute();
        $user = $db->prepare(
            "SELECT * FROM `users` WHERE `TG_id`=:id and `token`=:token"
        );
        $user->bindParam(':token', $token);
        $user->bindParam(':id', $id);
        $user->execute();
        $user = $user->fetchObject();
    }

    if (!empty($user->teamid)) {
        $teams = $db->prepare(
            "SELECT * FROM `teams` WHERE token=:token AND ID=:id"
        );
        $teams->bindParam(":token", $token);
        $teams->bindParam(":id", $user->teamid);
        $teams->execute();
        $teams = $teams->fetchObject();
        $user->username = $teams->emoji . " " . $user->username;
    }

    return $user;
}
